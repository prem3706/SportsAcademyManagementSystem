<x-layout>

    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <!-- Title Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: #0f172a; font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">Academy Dashboard</h4>
                    <p class="text-secondary small mb-0">Overview and real-time statistics of Sports Academy Management System</p>
                </div>
                <div class="text-secondary small fw-semibold bg-white px-3 py-1.5 rounded-3 shadow-sm border border-light d-flex align-items-center gap-1.5">
                    <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                    <span style="color: #475569;">Live Updates</span>
                </div>
            </div>

            <!-- Hybrid Theme Summary Cards Row (No Charts) -->
            <div class="row g-3 mb-4">
                
                <!-- Card 1: Players (Indigo Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card bg-white border border-light rounded-3 shadow-sm h-100 position-relative overflow-hidden" style="border-left: 4px solid #4f46e5 !important;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1" style="font-size: 10.5px; letter-spacing: 0.5px; color: #64748b;">Active Players</div>
                                <div class="fs-3 fw-bold mb-0" style="color: #0f172a; font-family: 'Inter', sans-serif;">{{ $total_players }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #e0e7ff; color: #4f46e5;">
                                <i class="bi bi-people-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Income (Emerald Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card bg-white border border-light rounded-3 shadow-sm h-100 position-relative overflow-hidden" style="border-left: 4px solid #10b981 !important;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1" style="font-size: 10.5px; letter-spacing: 0.5px; color: #64748b;">Fees Collected</div>
                                <div class="fs-3 fw-bold mb-0" style="color: #0f172a; font-family: 'Inter', sans-serif;">₹{{ number_format($total_fees_paid, 0) }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #d1fae5; color: #10b981;">
                                <i class="bi bi-currency-rupee fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Batches (Amber Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card bg-white border border-light rounded-3 shadow-sm h-100 position-relative overflow-hidden" style="border-left: 4px solid #f59e0b !important;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1" style="font-size: 10.5px; letter-spacing: 0.5px; color: #64748b;">Active Batches</div>
                                <div class="fs-3 fw-bold mb-0" style="color: #0f172a; font-family: 'Inter', sans-serif;">{{ $total_batches }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #fef3c7; color: #d97706;">
                                <i class="bi bi-calendar3 fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Pending (Rose Accent) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card bg-white border border-light rounded-3 shadow-sm h-100 position-relative overflow-hidden" style="border-left: 4px solid #f43f5e !important;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold text-secondary text-uppercase mb-1" style="font-size: 10.5px; letter-spacing: 0.5px; color: #64748b;">Pending Collections</div>
                                <div class="fs-3 fw-bold mb-0" style="color: #0f172a; font-family: 'Inter', sans-serif;">₹{{ number_format($total_fees_pending, 0) }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #ffe4e6; color: #e11d48;">
                                <i class="bi bi-exclamation-circle-fill fs-5"></i>
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
                        <div class="card-header border-0 bg-transparent pt-3 px-3 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">Recent Fee Payments</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Latest fee transactions recorded in system</p>
                            </div>
                            <a href="{{ route('player-fees.index') }}" class="btn btn-dark btn-sm fw-semibold px-3 rounded-2" style="font-size: 11.5px; background-color: #0f172a; border: none;">
                                View All
                            </a>
                        </div>
                        <div class="card-body p-2">
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
                                                <td class="py-2 px-3 fw-semibold text-dark">{{ $fee->player ? ($fee->player->firstname . ' ' . $fee->player->lastname) : 'Unknown' }}</td>
                                                <td class="py-2 px-3 text-secondary">
                                                    @if($fee->start_date && $fee->end_date)
                                                        {{ $fee->start_date->format('d M y') }} - {{ $fee->end_date->format('d M y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="py-2 px-3 fw-bold text-dark">₹{{ number_format($fee->total_amt, 0) }}</td>
                                                <td class="py-2 px-3"><span class="badge bg-light text-dark border border-secondary-subtle px-2 py-0.5" style="font-size: 9.5px;">{{ strtoupper($fee->payment_type) }}</span></td>
                                                <td class="py-2 px-3 text-center">
                                                    @if($fee->status === 'paid')
                                                        <span class="badge bg-success px-2 py-1 text-white" style="border-radius:12px; font-size: 9.5px; font-weight: 700;">Paid</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark px-2 py-1" style="border-radius:12px; font-size: 9.5px; font-weight: 700;">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-secondary">No recent transactions found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Financial Performance Summary -->
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-3" style="color: #1e293b;">Fee Collections Status</h6>
                            
                            @php
                                $total_billed = $total_fees_paid + $total_fees_pending;
                                $collection_percentage = $total_billed > 0 ? round(($total_fees_paid / $total_billed) * 100) : 0;
                            @endphp

                            <div class="d-flex justify-content-between align-items-center mb-1.5">
                                <span class="small fw-semibold text-secondary">Collection Rate</span>
                                <span class="small fw-bold text-dark">{{ $collection_percentage }}% Collected</span>
                            </div>
                            <div class="progress mb-3" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                                <div class="progress-bar bg-dark" role="progressbar" style="width: {{ $collection_percentage }}%; background-color: #0f172a !important;" aria-valuenow="{{ $collection_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            
                            <div class="row text-center mt-2.5">
                                <div class="col-6 border-end">
                                    <div class="small text-secondary mb-0.5">Realized Revenue</div>
                                    <div class="fw-bold text-success" style="font-size: 14px;">₹{{ number_format($total_fees_paid, 0) }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-secondary mb-0.5">Outstanding Balance</div>
                                    <div class="fw-bold text-danger" style="font-size: 14px;">₹{{ number_format($total_fees_pending, 0) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Section (Active Batches & New Trainees) -->
                <div class="col-lg-4">
                    
                    <!-- Card 1: Active Batches / Classes Schedule -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header border-0 bg-transparent pt-3 px-3 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">Training Batches</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Active schedules and programs</p>
                            </div>
                            <a href="{{ route('batches.index') }}" class="btn btn-light btn-sm fw-semibold text-dark px-3 rounded-2 border border-light" style="font-size: 11.5px;">
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
                                                        {{ $batch->sport ? $batch->sport->name : 'N/A' }} ({{ $batch->level ? $batch->level->name : 'N/A' }})
                                                    </div>
                                                </td>
                                                <td class="py-2 px-2 text-end text-secondary" style="font-size: 11px;">
                                                    <i class="bi bi-clock me-1 text-primary"></i>{{ \Carbon\Carbon::parse($batch->start_time)->format('h:i A') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-3 text-secondary">No active training batches</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: New Trainees -->
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header border-0 bg-transparent pt-3 px-3 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0" style="color: #1e293b;">New Trainees</h6>
                                <p class="text-secondary small mb-0" style="font-size: 11px;">Newly registered players</p>
                            </div>
                            <a href="{{ route('players.index') }}" class="btn btn-light btn-sm fw-semibold text-dark px-3 rounded-2 border border-light" style="font-size: 11.5px;">
                                Manage
                            </a>
                        </div>
                        <div class="card-body p-2">
                            <ul class="list-group list-group-flush">
                                @forelse($recent_players as $player)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-1 border-light">
                                        <div class="d-flex align-items-center">
                                            <!-- Colored initials badge -->
                                            <div class="rounded-circle me-2.5 d-inline-flex align-items-center justify-content-center bg-dark text-white fw-bold" style="width: 32px; height: 32px; font-size: 11px; min-width: 32px; background-color: #f1f5f9 !important; color: #475569 !important; border: 1px solid #e2e8f0;">
                                                {{ strtoupper(substr($player->firstname, 0, 1) . substr($player->lastname, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark" style="font-size: 12.5px;">{{ $player->firstname }} {{ $player->lastname }}</div>
                                                <div class="text-muted small" style="font-size: 10.5px;">{{ $player->email ?? 'No email' }}</div>
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-secondary border border-secondary-subtle px-2 py-0.5 text-uppercase" style="font-size: 9px; font-weight: 600;">
                                            {{ $player->gender ?? 'N/A' }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center py-3 text-secondary">No recent players registered</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-layout>
