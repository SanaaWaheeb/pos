@php
    $today = now();
    $month = (int) request('month', $today->month);
    $year = (int) request('year', $today->year);
    $monthName = date('F', mktime(0, 0, 0, $month, 10));
@endphp

<div id="calendar-container" class="price-calendar border p-3 rounded" data-month="{{ $month }}" data-year="{{ $year }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button id="prev-month" class="btn btn-sm btn-outline-primary">&lt;</button>
        <span class="align-self-center fw-bold" id="month-year-label">{{ $monthName }} {{ $year }}</span>
        <button id="next-month" class="btn btn-sm btn-outline-primary">&gt;</button>
    </div>

    <div class="calendar-grid d-grid" id="calendar-grid" style="grid-template-columns: repeat(7, 1fr); gap: 5px;"></div>
</div>

<style>
    .price-calendar input[type="number"] {
        font-size: 12px;
        padding: 2px;
    }
    .price-calendar input[disabled] {
        background-color: #eaeaea;
        cursor: not-allowed;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('calendar-container');
        const grid = document.getElementById('calendar-grid');
        const label = document.getElementById('month-year-label');
        const prevBtn = document.getElementById('prev-month');
        const nextBtn = document.getElementById('next-month');
        const defaultInput = document.getElementById('default-price-input');

        let defaultPrice = parseFloat(defaultInput.value || 0);
        const priceMap = {}; // Tracks manually edited prices

        const renderCalendarGrid = (month, year) => {
            const firstDay = new Date(year, month - 1, 1);
            const firstWeekDay = (firstDay.getDay() + 6) % 7 + 1;
            const daysInMonth = new Date(year, month, 0).getDate();

            let html = '';
            const weekDays = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
            weekDays.forEach(day => {
                html += `<div class="text-center fw-bold border-bottom pb-1">${day}</div>`;
            });

            for (let i = 1; i < firstWeekDay; i++) {
                html += `<div></div>`;
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = `${year.toString().padStart(4, '0')}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const value = priceMap[date] ?? defaultPrice;

                // Disable if date is before today
                const today = new Date();
                const inputDate = new Date(`${date}T00:00:00`);
                const isPast = inputDate < new Date(today.getFullYear(), today.getMonth(), today.getDate());

                html += `
                    <div class="text-center border rounded p-1 bg-light">
                        <div class="fw-bold">${day}</div>
                        <input type="number" name="daily_prices[${date}]"
                            class="form-control form-control-sm mt-1 text-center price-input"
                            data-date="${date}"
                            value="${value}"
                            step="any"
                            ${isPast ? 'disabled' : ''} />
                    </div>
                `;
            }

            return html;
        };

        const attachInputListeners = () => {
            document.querySelectorAll('.price-input').forEach(input => {
                input.addEventListener('input', function () {
                    const date = this.dataset.date;
                    const val = this.value;
                    if (val !== '') {
                        priceMap[date] = parseFloat(val);
                    } else {
                        delete priceMap[date];
                    }
                });
            });
        };

        const loadCalendar = (month, year) => {
            grid.innerHTML = renderCalendarGrid(month, year);
            container.dataset.month = month;
            container.dataset.year = year;

            const newDate = new Date(year, month - 1);
            const monthName = newDate.toLocaleString('default', { month: 'long' });
            label.textContent = `${monthName} ${year}`;

            attachInputListeners();
        };

        prevBtn.addEventListener('click', e => {
            const today = new Date();
            const currentYear = today.getFullYear();
            const currentMonth = today.getMonth()+1;
            e.preventDefault();
            let m = parseInt(container.dataset.month) - 1;
            let y = parseInt(container.dataset.year);
            const isPast = (y < currentYear) || (y === currentYear && m < currentMonth);
            if (!isPast) {
                if (m < 1) { m = 12; y--; }
                loadCalendar(m, y);
            }
        });

        nextBtn.addEventListener('click', e => {
            e.preventDefault();
            let m = parseInt(container.dataset.month) + 1;
            let y = parseInt(container.dataset.year);
            if (m > 12) { m = 1; y++; }
            loadCalendar(m, y);
        });

        defaultInput.addEventListener('input', function () {
            defaultPrice = parseFloat(this.value || 0);
            document.querySelectorAll('.price-input').forEach(input => {
                const date = input.dataset.date;
                if (!priceMap[date] || input.value === '' || input.value == defaultPrice) {
                    input.value = defaultPrice;
                }
            });
        });

        loadCalendar(parseInt(container.dataset.month), parseInt(container.dataset.year));
    });
</script>