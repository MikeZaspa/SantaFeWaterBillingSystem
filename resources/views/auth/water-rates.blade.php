<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santa Fe Water Billing System - Water Rates</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #d32f2f;
            --primary-light: #ff6659;
            --primary-dark: #9a0007;
            --sidebar-bg: linear-gradient(180deg, #d32f2f 0%, #9a0007 100%);
            --sidebar-text: rgba(255,255,255,0.9);
            --sidebar-hover: rgba(255,255,255,0.1);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            width: 280px;
            background: #f8f9fa;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            color: black;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu .nav-link {
            color: gray;
            padding: 0.75rem 1.5rem;
            margin: 0 0.5rem;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .sidebar-menu .nav-link:hover {
            color: white;
            background: blue;
            transform: translateX(5px);
        }
        
        .sidebar-menu .nav-link.active {
            color: white;
            background: blue;
        }
        
        .sidebar-menu .nav-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .login-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Water Rates specific styles */
        .form-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .page-title {
            color: #0d6efd;
            margin-bottom: 20px;
        }
        
        .rate-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .section-header {
            background-color: #0d6efd;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            text-align: center;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .no-rates {
            padding: 15px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }
        
        .sequence-number {
            width: 50px;
            text-align: center;
        }
        
        .rate-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .rate-tab {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid transparent;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            margin: 0 5px;
        }
        
        .rate-tab.active {
            background-color: #0d6efd;
            color: white;
            border-color: #dee2e6;
        }
        
        .back-button-container {
            margin-bottom: 20px;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 280px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <div class="sidebar-header text-center">
        <img src="{{ asset('image/santafe.png') }}" class="login-logo img-fluid mb-3">
        <h1 class="h5">Santa Fe Water Billing</h1>
    </div>
    
    <nav class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link " href="admin-accountant-dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-accountant-consumer">
                    <i class="bi bi-people"></i> Billing
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="admin-consumer-form">
                    <i class="bi bi-person-gear"></i> Manage Accounts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="water-rates">
                    <i class="bi bi-cash-coin"></i> Water Rates
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="admin-accountant-reports">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="paymentVerificationSection">
                    <i class="bi bi-credit-card"></i> Payment Verification
                </a>
            </li>
        </ul>
    </nav>
</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container py-4">
            <h1 class="page-title text-center">Water Rates Management</h1>
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Add/Edit Form -->
            <div class="form-container">
                <h4 id="form-title">{{ isset($waterRate) ? 'Edit Water Rate' : 'Add New Water Rate' }}</h4>
                <form method="POST" action="{{ isset($waterRate) ? route('water-rates.update', $waterRate->id) : route('water-rates.store') }}">
                    @csrf
                    @if(isset($waterRate))
                        @method('PUT')
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="residential" {{ isset($waterRate) && $waterRate->type == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ isset($waterRate) && $waterRate->type == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="institutional" {{ isset($waterRate) && $waterRate->type == 'institutional' ? 'selected' : '' }}>Institutional</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="range" class="form-label">Range (cubic meters)</label>
                            <input type="text" class="form-control" id="range" name="range" 
                                   value="{{ $waterRate->range ?? old('range') }}" 
                                   placeholder="e.g. 0-10, 11-20, etc." required>
                        </div>
                        <div class="col-md-4">
                            <label for="amount" class="form-label">Amount (₱)</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                                   value="{{ $waterRate->amount ?? old('amount') }}" 
                                   placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        @if(isset($waterRate))
                            <a href="{{ route('water-rates.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        @endif
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            
            <!-- Rate Tabs Navigation -->
            <div class="rate-tabs">
                <div class="rate-tab active" data-tab="residential">Residential</div>
                <div class="rate-tab" data-tab="commercial">Commercial</div>
                <div class="rate-tab" data-tab="institutional">Institutional</div>
            </div>
            
            <!-- Rates Tables Grouped by Type -->
            <div class="rate-section" id="residential-rates">
                <div class="section-header">Residential Rates</div>
                @if($rates->where('type', 'residential')->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th class="sequence-number">#</th>
                                <th>Range (m³)</th>
                                <th>Amount (₱)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $residentialCount = 1; @endphp
                            @foreach($rates->where('type', 'residential')->sortBy('range') as $rate)
                            <tr>
                                <td class="sequence-number">{{ $residentialCount++ }}</td>
                                <td>{{ $rate->range }}</td>
                                <td>₱{{ number_format($rate->amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('water-rates.edit', $rate->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                            data-bs-target="#confirm-modal" data-id="{{ $rate->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="no-rates">No residential rates found</div>
                @endif
            </div>

            <div class="rate-section" id="commercial-rates" style="display: none;">
                <div class="section-header">Commercial Rates</div>
                @if($rates->where('type', 'commercial')->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th class="sequence-number">#</th>
                                <th>Range (m³)</th>
                                <th>Amount (₱)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $commercialCount = 1; @endphp
                            @foreach($rates->where('type', 'commercial')->sortBy('range') as $rate)
                            <tr>
                                <td class="sequence-number">{{ $commercialCount++ }}</td>
                                <td>{{ $rate->range }}</td>
                                <td>₱{{ number_format($rate->amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('water-rates.edit', $rate->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                            data-bs-target="#confirm-modal" data-id="{{ $rate->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="no-rates">No commercial rates found</div>
                @endif
            </div>

            <div class="rate-section" id="institutional-rates" style="display: none;">
    <div class="section-header">Institutional Rates</div>
    @if($rates->where('type', 'institutional')->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th class="sequence-number">#</th>
                    <th>Range (m³)</th>
                    <th>Amount (₱)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $institutionalCount = 1; @endphp
                @foreach($rates->where('type', 'institutional')->sortBy('range') as $rate)
                <tr>
                    <td class="sequence-number">{{ $institutionalCount++ }}</td>
                    <td>{{ $rate->range }}</td>
                    <td>
                        @if($rate->range === '0-5')
                            Free
                        @else
                            ₱{{ number_format($rate->amount, 2) }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('water-rates.edit', $rate->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                data-bs-target="#confirm-modal" data-id="{{ $rate->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="no-rates">No institutional rates found</div>
    @endif
</div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirm-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this water rate?
                </div>
                <div class="modal-footer">
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Handle tab switching
        $('.rate-tab').click(function() {
            const tabId = $(this).data('tab');
            
            // Update active tab
            $('.rate-tab').removeClass('active');
            $(this).addClass('active');
            
            // Show corresponding section
            $('.rate-section').hide();
            $(`#${tabId}-rates`).show();
        });
        
        // Handle delete confirmation
        $('#confirm-modal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const rateId = button.data('id');
            const form = $('#delete-form');
            form.attr('action', '/water-rates/' + rateId);
        });

        // Mobile sidebar toggle
        $('#sidebarToggle').click(function() {
            $('.sidebar').toggleClass('active');
            $('.main-content').toggleClass('active');
        });
    </script>
</body>
</html>