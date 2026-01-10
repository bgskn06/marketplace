<x-app-layout>

    <style>
        .order-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* Soft background */
        .bg-primary-soft {
            background: rgba(13, 110, 253, .1);
            color: #0d6efd;
        }

        .bg-info-soft {
            background: rgba(13, 202, 240, .1);
            color: #0dcaf0;
        }

        .bg-warning-soft {
            background: rgba(255, 193, 7, .1);
            color: #ffc107;
        }

        .bg-success-soft {
            background: rgba(25, 135, 84, .1);
            color: #198754;
        }

        .bg-danger-soft {
            background: rgba(220, 53, 69, .1);
            color: #dc3545;
        }

        /* Date picker box */
        .date-box {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
        }

        /* Soft button */
        .btn-success-soft {
            background: rgba(32, 201, 151, 0.15);
            color: #20c997;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-success-soft:hover {
            background: rgba(32, 201, 151, 0.25);
        }

        /* Stat card */
        .stat-card {
            border: 1px solid #f1f3f5;
            border-radius: 10px;
            padding: 18px;
            height: 100%;
            background: #fff;
        }
    </style>

    <x-slot name="header">
        <h4 class="mb-0">Dashboard</h4>
    </x-slot>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Content Row -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- Header -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">Order Activity</h5>
                        <small class="text-muted">
                            Activities that you need to monitor to maintain your order
                        </small>
                    </div>

                    <!-- Stats -->
                    <div class="row text-center">

                        <!-- Item -->
                        <div class="col-xl-2 col-md-4 col-6 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-primary-soft">
                                    <x-general.icon icon="mdi:cart-outline" size="30" />
                                </div>
                                <small class="text-muted d-block">New order</small>
                                <h5 class="fw-bold mb-0">1.121</h5>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-info-soft">
                                    <x-general.icon icon="mdi:clipboard-check-outline" size="30" />
                                </div>
                                <small class="text-muted d-block">Order processed</small>
                                <h5 class="fw-bold mb-0">312</h5>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-warning-soft">
                                    <x-general.icon icon="mdi:package-variant-closed" size="30" />
                                </div>
                                <small class="text-muted d-block">Ready to ship</small>
                                <h5 class="fw-bold mb-0">601</h5>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-success-soft">
                                    <x-general.icon icon="mdi:truck-outline" size="30" />
                                </div>
                                <small class="text-muted d-block">Order shipped</small>
                                <h5 class="fw-bold mb-0">311</h5>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-danger-soft">
                                    <x-general.icon icon="mdi:alert-circle-outline" size="30" />
                                </div>
                                <small class="text-muted d-block">Order complained</small>
                                <h5 class="fw-bold mb-0">12</h5>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                        <h5 class="fw-bold mb-2 mb-md-0">Store Statistic</h5>

                        <div class="d-flex gap-2 align-items-center">
                            <div class="date-box">
                                <x-general.icon icon="mdi:calendar-month-outline" />
                                <span>November 15, 2022</span>
                            </div>
                            <span class="mx-1">–</span>
                            <div class="date-box">
                                <x-general.icon icon="mdi:calendar-month-outline" />
                                <span>November 22, 2022</span>
                            </div>

                            {{-- <button class="btn btn-success-soft ms-3">
                                <x-general.icon icon="mdi:download" />
                                Download report
                            </button> --}}
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="row g-3 mb-2">

                        <!-- Sales Potential -->
                        <div class="col-xl-4 col-md-6">
                            <div class="stat-card">
                                <small class="text-muted">Sales Potential</small>
                                <h4 class="fw-bold mt-2">RP32.100.289</h4>
                                <small class="text-success d-inline-flex align-items-center gap-1">
                                    <x-general.icon icon="mdi:arrow-up" />
                                    +25.56% from last 7 days
                                </small>
                            </div>
                        </div>

                        <!-- Products viewed -->
                        <div class="col-xl-4 col-md-6">
                            <div class="stat-card">
                                <small class="text-muted">Products viewed</small>
                                <h4 class="fw-bold mt-2">12.934</h4>
                                <small class="text-danger d-inline-flex align-items-center gap-1">
                                    <x-general.icon icon="mdi:arrow-down" />
                                    <span>-11.08% from last 7 days</span>
                                </small>
                            </div>
                        </div>

                        <!-- Product sold -->
                        <div class="col-xl-4 col-md-6">
                            <div class="stat-card">
                                <small class="text-muted">Product sold</small>
                                <h4 class="fw-bold mt-2">7.211</h4>
                                <small class="text-success d-inline-flex align-items-center gap-1">
                                    <x-general.icon icon="mdi:arrow-up" />
                                    +15.24% from last 7 days
                                </small>
                            </div>
                        </div>

                    </div>

                    <div class="">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Dropdown Header:</div>
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="myAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</x-app-layout>
