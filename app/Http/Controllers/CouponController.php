<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('Manage Coupans')){
            if(\Auth::user()->type == 'super admin')
            {
                $coupons = Coupon::get();
    
                return view('coupon.index', compact('coupons'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
       
    }


    public function create()
    {
        if(\Auth::user()->can('Create Coupans')){
            if(\Auth::user()->type == 'super admin')
            {
                return view('coupon.create');
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Coupans')){
            if(\Auth::user()->type == 'super admin')
            {
                $validator = \Validator::make(
                    $request->all(), [
                                    'name' => 'required|string|max:255',
                                    'discount' => 'required|numeric|min:0',
                                    'limit' => 'required|numeric|min:0',
                                    'code' => 'required',
                                ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $coupon           = new Coupon();
                $coupon->name     = $request->name;
                $coupon->discount = $request->discount;
                $coupon->limit    = $request->limit;
                $coupon->code     = strtoupper($request->code);

                $coupon->save();

                return redirect()->route('coupons.index')->with('success', __('Coupon successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Coupon $coupon)
    {
        if(\Auth::user()->can('Show Coupans')){
            $userCoupons = UserCoupon::where('coupon', $coupon->id)->get();

            return view('coupon.view', compact('userCoupons', 'coupon'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
      
    }


    public function edit(Coupon $coupon)
    {
        if(\Auth::user()->can('Edit Coupans')){
            if(\Auth::user()->type == 'super admin')
            {
                return view('coupon.edit', compact('coupon'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }


    public function update(Request $request, Coupon $coupon)
    {
        if(\Auth::user()->can('Edit Coupans')){
            if(\Auth::user()->type == 'super admin')
            {
                $validator = \Validator::make(
                    $request->all(), [
                                    'name' => 'required|string|max:255',
                                    'discount' => 'required|numeric|min:0',
                                    'limit' => 'required|numeric|min:0',
                                    'code' => 'required',
                                ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $coupon           = Coupon::find($coupon->id);
                $coupon->name     = $request->name;
                $coupon->discount = $request->discount;
                $coupon->limit    = $request->limit;
                $coupon->code     = $request->code;

                $coupon->save();

                return redirect()->route('coupons.index')->with('success', __('Coupon successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Coupon $coupon)
    {
        if(\Auth::user()->can('Delete Coupans')){
            if(\Auth::user()->type == 'super admin')
            {
                $coupon->delete();
    
                return redirect()->route('coupons.index')->with('success', __('Coupon successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
       
    }

    public function applyCoupon(Request $request)
    {
        $plan = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($request->plan_id));
        if($plan && $request->coupon != '')
        {
            $original_price = self::formatPrice($plan->price);
            $coupons        = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

            if(!empty($coupons))
            {
                $usedCoupun = $coupons->used_coupon();
                if($coupons->limit == $usedCoupun)
                {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($plan->price, \Utility::getValByName('decimal_number')),
                            'message' => __('This coupon code has expired.'),
                        ]
                    );
                }
                else
                {
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $plan_price     = $plan->price - $discount_value;
                    $price          = self::formatPrice($plan->price - $discount_value);
                    $discount_value = '-' . self::formatPrice($discount_value);

                    return response()->json(
                        [
                            'is_success' => true,
                            'discount_price' => $discount_value,
                            'final_price' => $price,
                            'price' => number_format($plan_price, Utility::getValByName('decimal_number')),
                            'message' => __('Coupon code has applied successfully.'),
                        ]
                    );
                }
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, Utility::getValByName('decimal_number')),
                        'message' => __('This coupon code is invalid or has expired.'),
                    ]
                );
            }
        }
    }

    public function formatPrice($price){
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        // return env('CURRENCY_SYMBOL') . number_format($price);
        return isset($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '$' . number_format($price);
    }
}
