<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogSocial;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;

class BlogController extends Controller
{
    public function __construct()
    {
        if(\Auth::check())
        {
            \App::setLocale(\Auth::user()->lang);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plan = Plan::find(\Auth::user()->plan);
        if(\Auth::user()->can('Manage Blog') && $plan->blog == 'on')
        {
            $store_id = Auth::user()->current_store;
            $blogs    = Blog::where('store_id', $store_id)->where('created_by', \Auth::user()->creatorId())->get();

            return view('blog.index', compact('blogs', 'store_id'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('Create Blog')){
            return view('blog.create');
        } 
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Blog')){
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required|max:120',
                                   'blog_cover_image'=>'mimes:jpeg,png,jpg,gif,svg,pdf,doc|max:20480',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return redirect()->back()->with('error', $messages->first());
            }
            if(!empty($request->blog_cover_image))
            {
                $extension           = $request->file('blog_cover_image')->getClientOriginalExtension();

                if($request->hasFile('blog_cover_image'))
                {
                    //storage limit
                    $image_size = $request->file('blog_cover_image')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if($result==1){
                        $fileNameToStoreBlog = 'blog' . '_' . time() . '.' . $extension;
                        $settings = Utility::getStorageSetting();
                        if($settings['storage_setting']=='local'){
                            $dir        = 'uploads/blog_cover_image/';
                        }
                        else{
                            $dir        = 'uploads/blog_cover_image/';
                        }
                        $path = Utility::upload_file($request,'blog_cover_image',$fileNameToStoreBlog,$dir,[]);
            
                        if($path['flag'] == 1){
                            $url = $path['url'];
                        }else{
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                    }
                }
    
            }

            $blog                   = new Blog();
            $blog->title            = $request->title;
            $blog->blog_cover_image = !empty($fileNameToStoreBlog) ? $fileNameToStoreBlog : 'default.jpg';
            $blog->detail           = $request->detail;
            $blog->store_id         = \Auth::user()->current_store;
            $blog->created_by       = \Auth:: user()->creatorId();
            $blog->save();
            return redirect()->back()->with('success', __('Blog Successfully added!') . ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));            
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Blog $blog
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Blog $blog
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        if(\Auth::user()->can('Edit Blog')){
            return view('blog.edit', compact('blog'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Blog $blog
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        if(\Auth::user()->can('Edit Blog')){
        $validator = \Validator::make(
            $request->all(), [
                               'title' => 'required|max:120',
                               'blog_cover_image'=>'mimes:jpeg,png,jpg,gif,svg,pdf,doc|max:20480',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        if(!empty($request->blog_cover_image))
        {
            $fileName = $blog->blog_cover_image !== 'default.jpg' ? $blog->blog_cover_image : '' ;
            $filePath ='uploads/blog_cover_image/'. $fileName;
            
        

            $image_size = $request->file('blog_cover_image')->getSize();
            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if($result==1){
                Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                $extension           = $request->file('blog_cover_image')->getClientOriginalExtension();
                $fileNameToStoreBlog = 'blog' . '_' . time() . '.' . $extension;
                $settings = Utility::getStorageSetting();
                if($settings['storage_setting']=='local'){
                    $dir        = 'uploads/blog_cover_image/';
                }
                else{
                    $dir        = 'uploads/blog_cover_image/';
                }
                $path = Utility::upload_file($request,'blog_cover_image',$fileNameToStoreBlog,$dir,[]);
                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
                       
            // $dir                 = storage_path('uploads/store_logo/');
            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }
            // $path = $request->file('blog_cover_image')->storeAs('uploads/store_logo/', $fileNameToStoreBlog);

        }

        $blog->title = $request->title;
        if(!empty($fileNameToStoreBlog))
        {
            $blog->blog_cover_image = $fileNameToStoreBlog;
        }
        $blog->detail = $request->detail;
        $blog->update();

        return redirect()->back()->with('success', __('Blog Successfully Updated!') . ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));    
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Blog $blog
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if(\Auth::user()->can('Delete Blog')){
            $fileName = $blog->blog_cover_image !== 'default.jpg' ? $blog->blog_cover_image : '' ;
            $filePath ='uploads/blog_cover_image/'. $fileName;
            
            Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
            $blog->delete();

            return redirect()->back()->with('success', __('Blog Deleted!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function socialBlog()
    {
        $socialblog = BlogSocial::where('store_id', \Auth::user()->current_store)->first();
        if(!empty($socialblog))
        {
            return view('blog.socialblog', compact('socialblog'));
        }
        else
        {
            return view('blog.store_soicalblog');
        }
    }

    public function storeSocialblog(Request $request)
    {
        if(isset($request->blog_id) && !empty($request->blog_id))
        {
            $blogsocial = BlogSocial::find($request->blog_id);
        }
        else
        {
            $blogsocial = '';
        }

        if(empty($blogsocial))
        {
            $blogsocial                       = new BlogSocial();
            $blogsocial->enable_social_button = isset($request->enable_social_button) ? 'on' : 'off';
            $blogsocial->enable_email         = isset($request->enable_email) ? 'on' : 'off';
            $blogsocial->enable_twitter       = isset($request->enable_twitter) ? 'on' : 'off';
            $blogsocial->enable_facebook      = isset($request->enable_facebook) ? 'on' : 'off';
            $blogsocial->enable_googleplus    = isset($request->enable_googleplus) ? 'on' : 'off';
            $blogsocial->enable_linkedIn      = isset($request->enable_linkedIn) ? 'on' : 'off';
            $blogsocial->enable_pinterest     = isset($request->enable_pinterest) ? 'on' : 'off';
            $blogsocial->enable_stumbleupon   = isset($request->enable_stumbleupon) ? 'on' : 'off';
            $blogsocial->enable_whatsapp      = isset($request->enable_whatsapp) ? 'on' : 'off';
            $blogsocial->store_id             = \Auth::user()->current_store;
            $blogsocial->created_by           = \Auth:: user()->creatorId();
            $blogsocial->save();
        }
        else
        {
            $blogsocial->enable_social_button = isset($request->enable_social_button) ? 'on' : 'off';
            $blogsocial->enable_email         = isset($request->enable_email) ? 'on' : 'off';
            $blogsocial->enable_twitter       = isset($request->enable_twitter) ? 'on' : 'off';
            $blogsocial->enable_facebook      = isset($request->enable_facebook) ? 'on' : 'off';
            $blogsocial->enable_googleplus    = isset($request->enable_googleplus) ? 'on' : 'off';
            $blogsocial->enable_linkedIn      = isset($request->enable_linkedIn) ? 'on' : 'off';
            $blogsocial->enable_pinterest     = isset($request->enable_pinterest) ? 'on' : 'off';
            $blogsocial->enable_stumbleupon   = isset($request->enable_stumbleupon) ? 'on' : 'off';
            $blogsocial->enable_whatsapp      = isset($request->enable_whatsapp) ? 'on' : 'off';
            $blogsocial->store_id             = \Auth::user()->current_store;
            $blogsocial->created_by           = \Auth:: user()->creatorId();
            $blogsocial->update();
        }

        return redirect()->back()->with('success', __('Social Blog Successfully added!'));
    }
}
