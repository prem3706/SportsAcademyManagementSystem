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
            <div class="row g-3 mb-4">

                <!-- Card 1: Players (Indigo Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-indigo border-0 h-100">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Players</div>
                                <div class="fs-2 fw-bold mb-0 text-dark">{{ $total_players }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #e0e7ff; color: #4f46e5; width: 56px; height: 56px; border-radius: 16px;">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Coaches (Emerald Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-success border-0 h-100">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Coaches</div>
                                <div class="fs-2 fw-bold mb-0 text-dark">{{ $total_coaches }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #d1fae5; color: #10b981; width: 56px; height: 56px; border-radius: 16px;">
                                <i class="bi bi-person-badge-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Batches (Amber Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-warning border-0 h-100">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Batches</div>
                                <div class="fs-2 fw-bold mb-0 text-dark">{{ $total_batches }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #fef3c7; color: #d97706; width: 56px; height: 56px; border-radius: 16px;">
                                <i class="bi bi-calendar3 fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Sports (Purple Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card dashboard-stat-card stat-card-purple border-0 h-100">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1"
                                    style="font-size: 10.5px; letter-spacing: 0.5px;">Total Sports</div>
                                <div class="fs-2 fw-bold mb-0 text-dark">{{ $total_sports }}</div>
                            </div>
                            <div class="icon-box" style="background-color: #f3e8ff; color: #8b5cf6; width: 56px; height: 56px; border-radius: 16px;">
                                <i class="bi bi-trophy-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Row 2: Charts & Financial Summary -->
            <div class="row g-4 mb-4">
                <!-- Monthly Revenue Collection Chart (Bar) -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-1">
                            <h6 class="fw-bold mb-0" style="color: #1e293b;">Monthly Collections Overview</h6>
                            <p class="text-secondary small mb-0" style="font-size: 11px;">Paid vs Outstanding collections (Last 6 Months)</p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div style="height: 310px; position: relative;">
                                <canvas id="monthlyCollectionsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Health Card -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-1">
                            <h6 class="fw-bold mb-0" style="color: #1e293b;">Financial Summary</h6>
                            <p class="text-secondary small mb-0" style="font-size: 11px;">Real-time billing & expense analytics</p>
                        </div>
                        <div class="card-body px-2 pb-3">
                            <div class="d-flex flex-column h-100 justify-content-between">
                                <!-- Month Fees Collected -->
                                <div class="finance-widget-item">
                                    <div class="d-flex align-items-center">
                                        <div class="finance-icon-circle" style="background-color: #ffe4e6; color: #e11d48;">
                                            <i class="bi bi-currency-rupee"></i>
                                        </div>
                                        <div>
                                            <div class="text-secondary small fw-semibold" style="font-size: 11px;">Month Fees Collected</div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-dark fs-6">₹{{ number_format($month_fees_collected, 0) }}</div>
                                </div>

                                <!-- Month Expenses -->
                                <div class="finance-widget-item">
                                    <div class="d-flex align-items-center">
                                        <div class="finance-icon-circle" style="background-color: #fce7f3; color: #db2777;">
                                            <i class="bi bi-journal-minus"></i>
                                        </div>
                                        <div>
                                            <div class="text-secondary small fw-semibold" style="font-size: 11px;">Month Expenses</div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-dark fs-6">₹{{ number_format($month_expenses, 0) }}</div>
                                </div>

                                <!-- Net Monthly Balance -->
                                <div class="finance-widget-item">
                                    <div class="d-flex align-items-center">
                                        <div class="finance-icon-circle" style="background-color: #e0f2fe; color: #0ea5e9;">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        <div>
                                            <div class="text-secondary small fw-semibold" style="font-size: 11px;">Net Monthly Balance</div>
                                        </div>
                                    </div>
                                    <div class="fw-bold fs-6 {{ $net_monthly_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                        ₹{{ number_format($net_monthly_balance, 0) }}
                                    </div>
                                </div>

                                <!-- Total Expenses -->
                                <div class="finance-widget-item">
                                    <div class="d-flex align-items-center">
                                        <div class="finance-icon-circle" style="background-color: #f1f5f9; color: #1e293b;">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                        <div>
                                            <div class="text-secondary small fw-semibold" style="font-size: 11px;">Total Expenses</div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-dark fs-6">₹{{ number_format($total_expenses, 0) }}</div>
                                </div>

                                <!-- Outstanding Fees (Total) -->
                                <div class="finance-widget-item">
                                    <div class="d-flex align-items-center">
                                        <div class="finance-icon-circle" style="background-color: #fef3c7; color: #d97706;">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </div>
                                        <div>
                                            <div class="text-secondary small fw-semibold" style="font-size: 11px;">Outstanding Fees</div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-dark fs-6">₹{{ number_format($total_fees_pending, 0) }}</div>
                                </div>

                                <!-- Outstanding Monthly Fees -->
                                <div class="finance-widget-item">
                                    <div class="d-flex align-items-center">
                                        <div class="finance-icon-circle" style="background-color: #fee2e2; color: #ef4444;">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div>
                                            <div class="text-secondary small fw-semibold" style="font-size: 11px;">Month Outstanding</div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-dark fs-6">₹{{ number_format($total_monthly_fees_pending, 0) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Split Layout (Un-merged separate cards) -->
            <div class="row g-4">

                <!-- Left Section: Payments & Billing -->
                <div class="col-lg-8">
                    
                    <!-- Card 1: Recent Fee Payments -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Recent Fee Payments</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Latest fee transactions recorded in system</p>
                            </div>
                            <a href="{{ route('player-fees.index') }}" class="btn btn-dark btn-sm fw-semibold px-3 rounded-2" style="font-size: 11.5px; background-color: #0f172a; border: none; height: 32px; line-height: 20px;">
                                View All
                            </a>
                        </div>
                        <div class="card-body px-2 pb-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12px;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3" style="font-size: 10.5px; color: #475569;">Player</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3" style="font-size: 10.5px; color: #475569;">Period</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3" style="font-size: 10.5px; color: #475569;">Amount</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3" style="font-size: 10.5px; color: #475569;">Method</th>
                                            <th class="text-uppercase text-secondary fw-semibold py-2 px-3 text-center" style="font-size: 10.5px; color: #475569;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_fees as $fee)
                                            <tr style="transition: background-color 0.1s ease;">
                                                <td class="py-2.5 px-3 fw-semibold text-dark">
                                                    {{ $fee->player ? $fee->player->firstname . ' ' . $fee->player->lastname : 'Unknown' }}
                                                </td>
                                                <td class="py-2.5 px-3 text-secondary">
                                                    @if ($fee->start_date && $fee->end_date)
                                                        {{ $fee->start_date->format('d M y') }} - {{ $fee->end_date->format('d M y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="py-2.5 px-3 fw-bold text-dark">₹{{ number_format($fee->total_amt, 0) }}</td>
                                                <td class="py-2.5 px-3"><span class="badge bg-light text-dark border border-secondary-subtle px-2 py-0.5" style="font-size: 9.5px;">{{ strtoupper($fee->payment_type) }}</span></td>
                                                <td class="py-2.5 px-3 text-center">
                                                    @if ($fee->status === 'paid')
                                                        <span class="badge bg-success px-2 py-1 text-white" style="border-radius:12px; font-size: 9.5px; font-weight: 700;">Paid</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark px-2 py-1" style="border-radius:12px; font-size: 9.5px; font-weight: 700;">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-secondary">No recent transactions found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Unpaid Players -->
                    <div class="card border-0 shadow-sm rounded-4" id="unpaidPlayersCard" data-ajax-url="{{ route('dashboard') }}">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" id="unpaidCardTitle">Unpaid Players ({{ \Carbon\Carbon::createFromDate($unpaid_year, $unpaid_month, 1)->format('F Y') }})</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Active trainees with pending fees for this month</p>
                            </div>
                            <!-- Filters -->
                            <div class="d-flex gap-1.5">
                                <select id="unpaidMonthFilter" class="form-select form-select-sm no-select2 bg-white" style="width: auto; height: 32px; font-size: 12px; border-radius: 6px; padding: 2px 24px 2px 8px; border: 1px solid #ced4da;">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $m == $unpaid_month ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <select id="unpaidYearFilter" class="form-select form-select-sm no-select2 bg-white" style="width: auto; height: 32px; font-size: 12px; border-radius: 6px; padding: 2px 24px 2px 8px; border: 1px solid #ced4da;">
                                    @foreach (range(now()->year - 2, now()->year + 2) as $y)
                                        <option value="{{ $y }}" {{ $y == $unpaid_year ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0 dataTable', 'id' => 'datatable']) }}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Section: Insights & Operations -->
                <div class="col-lg-4">
                    
                    <!-- Card 1: Sport Share (Doughnut Chart) -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-1">
                            <h6 class="fw-bold mb-0" style="color: #1e293b;">Sport Share</h6>
                            <p class="text-secondary small mb-0" style="font-size: 11px;">Realized earnings share per sport</p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div style="height: 250px; width: 100%; position: relative;" class="d-flex align-items-center justify-content-center">
                                <canvas id="sportRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Active Batches -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">Training Batches</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Active schedules and programs</p>
                            </div>
                            <a href="{{ route('batches.index') }}" class="btn btn-light btn-sm fw-semibold text-dark px-2.5 rounded-2 border border-light" style="font-size: 11px; height: 30px; line-height: 18px;">
                                View All
                            </a>
                        </div>
                        <div class="card-body px-2 pb-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12px;">
                                    <tbody>
                                        @forelse($recent_batches as $batch)
                                            <tr>
                                                <td class="py-2.5 px-2">
                                                    <div class="fw-semibold text-dark">{{ $batch->name }}</div>
                                                    <div class="text-muted" style="font-size: 10.5px;">
                                                        {{ $batch->sport ? $batch->sport->name : 'N/A' }} ({{ $batch->level ? $batch->level->name : 'N/A' }})
                                                    </div>
                                                </td>
                                                <td class="py-2.5 px-2 text-end text-secondary" style="font-size: 11px;">
                                                    <i class="bi bi-clock me-1 text-primary"></i>{{ \Carbon\Carbon::parse($batch->start_time)->format('h:i A') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-4 text-secondary">No active training batches</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: New Trainees -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">New Trainees</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Newly registered players</p>
                            </div>
                            <a href="{{ route('players.index') }}" class="btn btn-light btn-sm fw-semibold text-dark px-2.5 rounded-2 border border-light" style="font-size: 11px; height: 30px; line-height: 18px;">
                                Manage
                            </a>
                        </div>
                        <div class="card-body px-2 pb-3">
                            <ul class="list-group list-group-flush bg-transparent">
                                @forelse($recent_players as $player)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-1 border-light bg-transparent">
                                        <div class="d-flex align-items-center">
                                            <div class="trainee-avatar me-2.5">
                                                {{ strtoupper(substr($player->firstname, 0, 1) . substr($player->lastname, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark" style="font-size: 12px;">{{ $player->firstname }} {{ $player->lastname }}</div>
                                                <div class="text-secondary" style="font-size: 10px;">{{ $player->email ?? 'No email' }}</div>
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-secondary border border-secondary-subtle px-2 py-0.5 text-uppercase" style="font-size: 8.5px; font-weight: 600;">
                                            {{ $player->gender ?? 'N/A' }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center py-4 text-secondary bg-transparent">No recent players registered</li>
                                @endforelse
                            </ul>
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
