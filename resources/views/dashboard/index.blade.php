@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-3 px-lg-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center mb-4">
        <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-3"
              style="width: 44px; height: 44px; background: var(--color-primary); color: #fff; font-size: 1.25rem;">
            <i class="ti ti-layout-dashboard"></i>
        </span>
        <div>
            <h4 class="fw-bold mb-0">Dashboard</h4>
            <small class="text-muted">Today's overview — {{ now()->format('d M Y') }}</small>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        {{-- Total Today --}}
        <div class="col-6 col-lg-3">
            <div class="card-main-content p-3 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                          style="width: 40px; height: 40px; background: rgba(0,84,166,0.1); color: var(--color-primary); font-size: 1.15rem;">
                        <i class="ti ti-clipboard-list"></i>
                    </span>
                    <div>
                        <div class="text-muted" style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">Today's Bookings</div>
                        <div class="fw-bold fs-4">{{ $todayTotal }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="col-6 col-lg-3">
            <div class="card-main-content p-3 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                          style="width: 40px; height: 40px; background: rgba(247,103,7,0.1); color: var(--color-warning); font-size: 1.15rem;">
                        <i class="ti ti-clock"></i>
                    </span>
                    <div>
                        <div class="text-muted" style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">Pending</div>
                        <div class="fw-bold fs-4">{{ $todayPending }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active --}}
        <div class="col-6 col-lg-3">
            <div class="card-main-content p-3 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                          style="width: 40px; height: 40px; background: rgba(66,153,225,0.1); color: var(--color-info); font-size: 1.15rem;">
                        <i class="ti ti-truck-delivery"></i>
                    </span>
                    <div>
                        <div class="text-muted" style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">Active</div>
                        <div class="fw-bold fs-4">{{ $todayActive }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed --}}
        <div class="col-6 col-lg-3">
            <div class="card-main-content p-3 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                          style="width: 40px; height: 40px; background: rgba(47,179,68,0.1); color: var(--color-success); font-size: 1.15rem;">
                        <i class="ti ti-circle-check"></i>
                    </span>
                    <div>
                        <div class="text-muted" style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">Completed</div>
                        <div class="fw-bold fs-4">{{ $todayCompleted }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resource Info Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card-main-content p-3 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                          style="width: 40px; height: 40px; background: rgba(108,122,156,0.1); color: var(--color-secondary); font-size: 1.15rem;">
                        <i class="ti ti-user"></i>
                    </span>
                    <div>
                        <div class="text-muted" style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">Drivers</div>
                        <div class="fw-bold fs-4">{{ $totalDrivers }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-main-content p-3 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                          style="width: 40px; height: 40px; background: rgba(214,57,57,0.1); color: var(--color-danger); font-size: 1.15rem;">
                        <i class="ti ti-truck"></i>
                    </span>
                    <div>
                        <div class="text-muted" style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">Forklifts</div>
                        <div class="fw-bold fs-4">{{ $totalForklifts }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Gemini : Stacked Bar Chart showing Total Pallets / Items Transported by Route (Origin to Destination) for Today, 7 Days, and 1 Month --}}
    <div class="card-main-content p-3 p-lg-4 mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
            <div>
                <h6 class="fw-bold mb-1 d-flex align-items-center">
                    <i class="ti ti-route-2 me-2 fs-5" style="color: var(--color-primary);"></i>
                    Total Pallets / Items Transported by Route (Origin to Destination)
                </h6>
                <div class="text-muted small">
                    <i class="ti ti-info-circle me-1"></i>
                    Identifies high-volume traffic corridors across warehouses to optimize staging areas.
                </div>
            </div>

            {{-- Period Filter Pills --}}
            <div class="btn-group btn-group-sm align-self-start align-self-sm-auto" role="group" aria-label="Route Timeframe">
                <button type="button" class="btn btn-action-primary active px-3" id="btn-period-today" onclick="switchRoutePeriod('today')">Today</button>
                <button type="button" class="btn btn-outline-secondary px-3" id="btn-period-week" onclick="switchRoutePeriod('week')">7 Days</button>
                <button type="button" class="btn btn-outline-secondary px-3" id="btn-period-month" onclick="switchRoutePeriod('month')">1 Month</button>
            </div>
        </div>

        {{-- Chart Canvas Container --}}
        <div class="position-relative" style="min-height: 320px; height: 350px;">
            <canvas id="routeTransportChart"></canvas>
            <div id="routeChartEmptyState" class="d-none position-absolute top-50 start-50 translate-middle text-center text-muted">
                <i class="ti ti-chart-bar-off mb-2 d-block" style="font-size: 2.2rem; color: #94a3b8;"></i>
                <div class="fw-semibold">No transport data for this period</div>
                <small class="text-muted">No route movement records found with recorded items</small>
            </div>
        </div>
    </div>

    {{-- Recent Transactions Table --}}
    <div class="card-main-content p-3 p-lg-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0"><i class="ti ti-history me-2 text-muted"></i>Recent Bookings Today</h6>
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>

        @if($recentTransactions->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="ti ti-inbox mb-2 d-block" style="font-size: 2rem;"></i>
                No bookings today yet.
            </div>
        @else
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Forklift</th>
                            <th>Driver</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Status</th>
                            <th class="text-end">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $txn)
                            <tr class="clickable-row" onclick="window.location='{{ route('transactions.show', $txn->transaction_id) }}'">
                                <td>
                                    {{ $txn->forklift->model ?? 'N/A' }}
                                    @if(!empty($txn->forklift->type))
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $txn->forklift->type }}</small>
                                    @endif
                                </td>
                                <td>{{ $txn->driver->name ?? 'Unassigned' }}</td>
                                <td>{{ $txn->warehouseFrom->name ?? '-' }}</td>
                                <td>{{ $txn->warehouseTo->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'Pending'    => 'background: var(--color-warning); color: #fff;',
                                            'Accepted'   => 'background: var(--color-info); color: #fff;',
                                            'In Transit' => 'background: var(--color-primary); color: #fff;',
                                            'Complete'   => 'background: var(--color-success); color: #fff;',
                                            'Rejected'   => 'background: var(--color-danger); color: #fff;',
                                        ];
                                        $style = $statusColors[$txn->status] ?? 'background: var(--color-inactive); color: #fff;';
                                    @endphp
                                    <span class="status-badge" style="{{ $style }}">{{ $txn->status }}</span>
                                </td>
                                <td class="text-end col-num text-muted" style="font-size: 0.85rem;">{{ $txn->created_at->format('H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- Gemini : Load Chart.js and render Stacked Bar Chart for Route Transport Volume --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const routeData = @json($routeTransportData ?? []);
        let routeChart = null;

        window.switchRoutePeriod = function (period) {
            // Update active pill button appearance
            ['today', 'week', 'month'].forEach(function (p) {
                const btn = document.getElementById('btn-period-' + p);
                if (btn) {
                    if (p === period) {
                        btn.className = 'btn btn-action-primary active px-3';
                    } else {
                        btn.className = 'btn btn-outline-secondary px-3';
                    }
                }
            });

            renderChart(period);
        };

        function renderChart(period) {
            const current = (routeData && routeData[period]) ? routeData[period] : { labels: [], pallets: [], items: [] };
            const canvas = document.getElementById('routeTransportChart');
            const emptyState = document.getElementById('routeChartEmptyState');

            if (!current.labels || current.labels.length === 0) {
                if (routeChart) {
                    routeChart.destroy();
                    routeChart = null;
                }
                canvas.style.display = 'none';
                emptyState.classList.remove('d-none');
                return;
            }

            canvas.style.display = 'block';
            emptyState.classList.add('d-none');

            if (routeChart) {
                routeChart.data.labels = current.labels;
                routeChart.data.datasets[0].data = current.pallets;
                routeChart.data.datasets[1].data = current.items;
                routeChart.update();
                return;
            }

            const ctx = canvas.getContext('2d');
            routeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: current.labels,
                    datasets: [
                        {
                            label: 'Pallets',
                            data: current.pallets,
                            backgroundColor: '#0054a6',
                            hoverBackgroundColor: '#003d7a',
                            borderRadius: 4,
                            stack: 'Stack 0',
                        },
                        {
                            label: 'Cartons / Items',
                            data: current.items,
                            backgroundColor: '#f76707',
                            hoverBackgroundColor: '#d95305',
                            borderRadius: 4,
                            stack: 'Stack 0',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: 8,
                                padding: 16,
                                font: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 12,
                                    weight: 600
                                },
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#182433',
                            titleFont: {
                                family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                size: 13,
                                weight: 700
                            },
                            bodyFont: {
                                family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                footer: function (items) {
                                    let total = 0;
                                    items.forEach(function (i) {
                                        total += Number(i.parsed.y) || 0;
                                    });
                                    return 'Total Transported: ' + total.toLocaleString() + ' units';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 11,
                                    weight: 600
                                },
                                maxRotation: 35,
                                minRotation: 0
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                precision: 0,
                                color: '#64748b',
                                font: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize with default 'today' period
        renderChart('today');
    });
</script>
@endsection
