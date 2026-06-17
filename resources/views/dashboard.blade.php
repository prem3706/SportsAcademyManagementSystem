<x-layout>

    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <!-- Title Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Academy Dashboard</h4>
                    <p class="text-secondary small mb-0">Overview and real-time statistics of Sports Academy Management
                        System</p>
                </div>
                <div
                    class="text-secondary small fw-semibold bg-white px-3 py-1.5 rounded-3 shadow-sm border border-light d-flex align-items-center gap-1.5">
                    <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                    <span class="text-secondary">Live Updates</span>
                </div>
            </div>

            <!-- Premium Summary Cards Section -->
            <!-- Row 1: Academy Overview -->
            <div class="row g-3 mb-3">

                <!-- Card 1: Players (Indigo Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-indigo border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Players</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">{{ $total_players }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #e0e7ff; color: #4f46e5;">
                                <i class="bi bi-people-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Coaches (Emerald Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-success border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Coaches</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">{{ $total_coaches }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #d1fae5; color: #10b981;">
                                <i class="bi bi-person-badge-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Batches (Amber Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-warning border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Batches</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">{{ $total_batches }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #fef3c7; color: #d97706;">
                                <i class="bi bi-calendar3 fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Sports (Purple Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-purple border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Sports</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">{{ $total_sports }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #f3e8ff; color: #8b5cf6;">
                                <i class="bi bi-trophy-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Row 2: Financial Overview -->
            <div class="row g-3 mb-4">

                <!-- Card 5: Month Fees Collected (Rose Accent) -->
                <div class="col-sm-6 col-md-4 col-xl">
                    <div class="card dashboard-stat-card stat-card-danger border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Month Fees Collected</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">₹{{ number_format($month_fees_collected, 0) }}
                                </div>
                            </div>
                            <div class="icon-box" style="background-color: #ffe4e6; color: #e11d48;">
                                <i class="bi bi-currency-rupee fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Month Expenses (Pink Accent) -->
                <div class="col-sm-6 col-md-4 col-xl">
                    <div class="card dashboard-stat-card stat-card-pink border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Month Expenses</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">₹{{ number_format($month_expenses, 0) }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #fce7f3; color: #db2777;">
                                <i class="bi bi-journal-minus fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 7: Net Monthly Balance (Info Accent) -->
                <div class="col-sm-6 col-md-4 col-xl">
                    <div class="card dashboard-stat-card stat-card-info border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Net Monthly Balance</div>
                                <div
                                    class="fs-3 fw-bold mb-0 {{ $net_monthly_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₹{{ number_format($net_monthly_balance, 0) }}
                                </div>
                            </div>
                            <div class="icon-box" style="background-color: #e0f2fe; color: #0ea5e9;">
                                <i class="bi bi-wallet2 fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 8: Total Expenses (Dark Accent) -->
                <div class="col-sm-6 col-md-4 col-xl">
                    <div class="card dashboard-stat-card stat-card-dark border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Expenses</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">₹{{ number_format($total_expenses, 0) }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #f1f5f9; color: #1e293b;">
                                <i class="bi bi-receipt fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 9: Outstanding Fees (Warning Accent) -->
                {{-- <div class="col-sm-6 col-md-4 col-xl">
                    <div class="card dashboard-stat-card stat-card-warning border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Outstanding Fees</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">₹{{ number_format($total_fees_pending, 0) }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #fef3c7; color: #d97706;">
                                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl">
                    <div class="card dashboard-stat-card stat-card-warning border-0 h-100">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Outstanding This Monthly Fees</div>
                                <div class="fs-3 fw-bold mb-0 text-dark">₹{{ number_format($total_monthly_fees_pending, 0) }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #fef3c7; color: #d97706;">
                                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>

            <!-- Charts and Graphs Section -->
            <div class="row g-4 mb-4">
                <!-- Monthly Revenue Collection Chart (Bar) -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header border-0 bg-transparent pt-3 px-3 pb-1">
                            <h6 class="fw-bold mb-0" style="color: #1e293b;">Monthly Collections Overview</h6>
                            <p class="text-secondary small mb-0" style="font-size: 11px;">Paid vs Outstanding
                                collections (Last 6 Months)</p>
                        </div>
                        <div class="card-body p-3">
                            <div style="height: 300px; position: relative;">
                                <canvas id="monthlyCollectionsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sport-wise Revenue Doughnut Chart -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header border-0 bg-transparent pt-3 px-3 pb-1">
                            <h6 class="fw-bold mb-0" style="color: #1e293b;">Sport-Wise Revenue Distribution</h6>
                            <p class="text-secondary small mb-0" style="font-size: 11px;">Realized earnings share per
                                sport</p>
                        </div>
                        <div class="card-body p-3 d-flex align-items-center justify-content-center">
                            <div style="height: 260px; width: 100%; position: relative;">
                                <canvas id="sportRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Split Layout - SaaS Styled -->
            <div class="row g-4">

                <!-- Left Section (Recent Payments & Progress) -->
                <div class="col-lg-8">

                    <!-- Card 1: Recent Fee Payments -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div
                            class="card-header border-0 bg-transparent pt-3 px-3 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">Recent Fee Payments</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Latest fee transactions
                                    recorded in system</p>
                            </div>
                            <a href="{{ route('player-fees.index') }}"
                                class="btn btn-dark btn-sm fw-semibold px-3 rounded-2"
                                style="font-size: 11.5px; background-color: #0f172a; border: none;">
                                View All
                            </a>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12px;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3"
                                                style="font-size: 10.5px; color: #475569;">Player</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3"
                                                style="font-size: 10.5px; color: #475569;">Period</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3"
                                                style="font-size: 10.5px; color: #475569;">Amount</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3"
                                                style="font-size: 10.5px; color: #475569;">Method</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3 text-center"
                                                style="font-size: 10.5px; color: #475569;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_fees as $fee)
                                            <tr style="transition: background-color 0.1s ease;">
                                                <td class="py-2 px-3 fw-semibold text-dark">
                                                    {{ $fee->player ? $fee->player->firstname . ' ' . $fee->player->lastname : 'Unknown' }}
                                                </td>
                                                <td class="py-2 px-3 text-secondary">
                                                    @if ($fee->start_date && $fee->end_date)
                                                        {{ $fee->start_date->format('d M y') }} -
                                                        {{ $fee->end_date->format('d M y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="py-2 px-3 fw-bold text-dark">
                                                    ₹{{ number_format($fee->total_amt, 0) }}</td>
                                                <td class="py-2 px-3"><span
                                                        class="badge bg-light text-dark border border-secondary-subtle px-2 py-0.5"
                                                        style="font-size: 9.5px;">{{ strtoupper($fee->payment_type) }}</span>
                                                </td>
                                                <td class="py-2 px-3 text-center">
                                                    @if ($fee->status === 'paid')
                                                        <span class="badge bg-success px-2 py-1 text-white"
                                                            style="border-radius:12px; font-size: 9.5px; font-weight: 700;">Paid</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark px-2 py-1"
                                                            style="border-radius:12px; font-size: 9.5px; font-weight: 700;">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-secondary">No recent
                                                    transactions found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Unpaid Players (Current Month) -->
                    <div class="card border-0 shadow-sm rounded-3 mt-4" id="unpaidPlayersCard"
                        data-ajax-url="{{ route('dashboard') }}">
                        <div
                            class="card-header border-0 bg-transparent pt-3 px-3 pb-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;" id="unpaidCardTitle">Unpaid Players
                                    ({{ \Carbon\Carbon::createFromDate($unpaid_year, $unpaid_month, 1)->format('F Y') }})
                                </h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Active trainees with
                                    pending fees for this month</p>
                            </div>
                            <!-- Filters -->
                            <div class="d-flex gap-2">
                                <select id="unpaidMonthFilter" class="form-select form-select-sm no-select2 bg-white"
                                    style="width: auto; height: 32px; font-size: 12px; border-radius: 6px; padding: 2px 24px 2px 8px; border: 1px solid #ced4da;">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}"
                                            {{ $m == $unpaid_month ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <select id="unpaidYearFilter" class="form-select form-select-sm no-select2 bg-white"
                                    style="width: auto; height: 32px; font-size: 12px; border-radius: 6px; padding: 2px 24px 2px 8px; border: 1px solid #ced4da;">
                                    @foreach (range(now()->year - 2, now()->year + 2) as $y)
                                        <option value="{{ $y }}"
                                            {{ $y == $unpaid_year ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0 dataTable']) }}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Section (Active Batches & New Trainees) -->
                <div class="col-lg-4">

                    <!-- Card 1: Active Batches / Classes Schedule -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div
                            class="card-header border-0 bg-transparent pt-3 px-3 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">Training Batches</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Active schedules and
                                    programs</p>
                            </div>
                            <a href="{{ route('batches.index') }}"
                                class="btn btn-light btn-sm fw-semibold text-dark px-3 rounded-2 border border-light"
                                style="font-size: 11.5px;">
                                View All
                            </a>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12px;">
                                    <tbody>
                                        @forelse($recent_batches as $batch)
                                            <tr>
                                                <td class="py-2 px-2">
                                                    <div class="fw-semibold text-dark">{{ $batch->name }}</div>
                                                    <div class="text-muted" style="font-size: 10.5px;">
                                                        {{ $batch->sport ? $batch->sport->name : 'N/A' }}
                                                        ({{ $batch->level ? $batch->level->name : 'N/A' }})
                                                    </div>
                                                </td>
                                                <td class="py-2 px-2 text-end text-secondary"
                                                    style="font-size: 11px;">
                                                    <i
                                                        class="bi bi-clock me-1 text-primary"></i>{{ \Carbon\Carbon::parse($batch->start_time)->format('h:i A') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-3 text-secondary">No active training batches
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: New Trainees -->
                    <div class="card border-0 shadow-sm rounded-3">
                        <div
                            class="card-header border-0 bg-transparent pt-3 px-3 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">New Trainees</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Newly registered players
                                </p>
                            </div>
                            <a href="{{ route('players.index') }}"
                                class="btn btn-light btn-sm fw-semibold text-dark px-3 rounded-2 border border-light"
                                style="font-size: 11.5px;">
                                Manage
                            </a>
                        </div>
                        <div class="card-body p-2">
                            <ul class="list-group list-group-flush">
                                @forelse($recent_players as $player)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center py-2 px-1 border-light">
                                        <div class="d-flex align-items-center">
                                            <!-- Colored initials badge -->
                                            <div class="trainee-avatar me-2.5">
                                                {{ strtoupper(substr($player->firstname, 0, 1) . substr($player->lastname, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark" style="font-size: 12.5px;">
                                                    {{ $player->firstname }} {{ $player->lastname }}</div>
                                                <div class="text-muted small" style="font-size: 10.5px;">
                                                    {{ $player->email ?? 'No email' }}</div>
                                            </div>
                                        </div>
                                        <span
                                            class="badge bg-light text-secondary border border-secondary-subtle px-2 py-0.5 text-uppercase"
                                            style="font-size: 9px; font-weight: 600;">
                                            {{ $player->gender ?? 'N/A' }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center py-3 text-secondary">No recent players
                                        registered</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <!-- Load Chart.js from CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $(document).ready(function() {
                // 1. Monthly Revenue Collections Chart (Bar Chart)
                const monthlyData = @json($monthly_earnings);
                const months = monthlyData.map(d => d.month);
                const paidAmounts = monthlyData.map(d => d.paid);
                const pendingAmounts = monthlyData.map(d => d.pending);

                const ctxMonthly = document.getElementById('monthlyCollectionsChart').getContext('2d');
                new Chart(ctxMonthly, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                                label: 'Paid (₹)',
                                data: paidAmounts,
                                backgroundColor: '#10b981',
                                borderRadius: 6,
                                borderSkipped: false,
                            },
                            {
                                label: 'Pending (₹)',
                                data: pendingAmounts,
                                backgroundColor: '#f43f5e',
                                borderRadius: 6,
                                borderSkipped: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 11,
                                        family: 'Inter'
                                    }
                                }
                            },
                            tooltip: {
                                padding: 10,
                                cornerRadius: 8,
                                font: {
                                    family: 'Inter'
                                },
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.dataset.label.replace(' (₹)', '') + ': ₹' +
                                            context.raw.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 10.5,
                                        family: 'Inter'
                                    }
                                }
                            },
                            y: {
                                border: {
                                    dash: [4, 4]
                                },
                                grid: {
                                    color: '#e2e8f0'
                                },
                                ticks: {
                                    font: {
                                        size: 10.5,
                                        family: 'Inter'
                                    },
                                    callback: function(value) {
                                        return '₹' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });

                // 2. Sport-wise Revenue Distribution (Doughnut Chart)
                const sportsData = @json($sports_earnings);
                const sportNames = sportsData.map(d => d.name);
                const sportEarnings = sportsData.map(d => d.earnings);

                const ctxSports = document.getElementById('sportRevenueChart').getContext('2d');
                new Chart(ctxSports, {
                    type: 'doughnut',
                    data: {
                        labels: sportNames,
                        datasets: [{
                            data: sportEarnings,
                            backgroundColor: [
                                '#4f46e5', // Indigo
                                '#10b981', // Emerald
                                '#f59e0b', // Amber
                                '#ec4899', // Pink
                                '#06b6d4' // Cyan
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 10,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 11,
                                        family: 'Inter'
                                    }
                                }
                            },
                            tooltip: {
                                padding: 10,
                                cornerRadius: 8,
                                font: {
                                    family: 'Inter'
                                },
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ': ₹' + context.raw.toLocaleString();
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            });
        </script>
    @endpush
</x-layout>
