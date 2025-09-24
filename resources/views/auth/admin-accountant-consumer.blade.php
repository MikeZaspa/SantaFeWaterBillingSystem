<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Santa Fe Water Billing System - Billing Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
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
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
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
        
        .sidebar-header .logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto;
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

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s;
            padding: 20px;
        }
        
        .header {
            height: 70px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 100;
            background: white;
            padding: 0 20px;
            border-radius: 8px;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            padding: 25px;
            margin-top: 25px;
            border: 1px solid rgba(0, 0, 0, 0.04);
            width: 100%;
            overflow: hidden;
        }

        .table-title {
            color: var(--primary-dark);
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-title h3 {
            font-weight: 600;
            margin: 0;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            --bs-table-striped-bg: rgba(211, 47, 47, 0.02);
            --bs-table-hover-bg: rgba(211, 47, 47, 0.05);
            margin-bottom: 0;
            width: 100%;
            table-layout: auto;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom-width: 2px;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-color: rgba(0, 0, 0, 0.03);
            white-space: nowrap;
        }

       /* Enhanced Badge Styles */
.badge {
    font-weight: 600;
    padding: 6px 12px;
    font-size: 0.75rem;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.badge-paid {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.15) 0%, rgba(40, 167, 69, 0.1) 100%);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.1);
}

.badge-unpaid {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.15) 0%, rgba(220, 53, 69, 0.1) 100%);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.1);
}

.badge-overdue {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(255, 193, 7, 0.1) 100%);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
    box-shadow: 0 2px 4px rgba(255, 193, 7, 0.1);
}

/* Hover effects for better interactivity */
.badge-paid:hover {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(40, 167, 69, 0.15) 100%);
    transform: translateY(-1px);
}

.badge-unpaid:hover {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(220, 53, 69, 0.15) 100%);
    transform: translateY(-1px);
}

.badge-overdue:hover {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.2) 0%, rgba(255, 193, 7, 0.15) 100%);
    transform: translateY(-1px);
}
        /* Button Styles */
        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-action i {
            font-size: 0.9rem;
        }

        .btn-action:hover {
            transform: scale(1.1);
        }

        .btn-action + .btn-action {
            margin-left: 8px;
        }

        /* Modal Styles */
        .modal-header {
            background-color: white;
            color: black;
        }

        .modal-footer .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .modal-footer .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .form-label.required:after {
            content: " *";
            color: var(--primary-color);
        }

        /* Receipt Modal Styles */
        .receipt-container {
            font-family: 'Courier New', monospace;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: white;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #ddd;
            padding-bottom: 15px;
        }
        .receipt-details {
            margin-bottom: 15px;
        }
        .receipt-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            border-top: 2px dashed #ddd;
            padding-top: 15px;
            font-size: 0.9em;
            color: #666;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .main-content.active {
                margin-left: 280px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadein {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .login-logo {
            width: 100px;       
            height: 100px;      
            border-radius: 50%; 
            object-fit: cover;  
        }

        /* Print styles for receipt */
@media print {
    body * {
        visibility: hidden;
    }
    #receiptContent, #receiptContent * {
        visibility: visible;
    }
    #receiptContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}

/* Penalty indicator */
.penalty-indicator {
    color: #dc3545;
    font-size: 0.8em;
    font-weight: bold;
}
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header text-center">
        <img src="{{ asset('image/santafe.png') }}" class="login-logo img-fluid mb-3">
        <h1 class="h5">Santa Fe Water Billing</h1>
    </div>
    
    <nav class="sidebar-menu">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="admin-accountant-dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <!-- Billing -->
            <li class="nav-item">
                <a class="nav-link active" href="admin-accountant-consumer">
                    <i class="bi bi-people"></i> Billing
                </a>
            </li>
            
            <!-- Manage Accounts -->
            <li class="nav-item">
                <a class="nav-link" href="admin-consumer-form">
                    <i class="bi bi-person-gear"></i> Manage Accounts
                </a>
            </li>

            <!-- Water Rates -->
            <li class="nav-item">
                <a class="nav-link" href="water-rates">
                    <i class="bi bi-cash-coin"></i> Water Rates
                </a>
            </li>

            <!-- Reports -->
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
    <!-- Header -->
    <header class="header d-flex align-items-center">
        <button id="sidebarToggle" class="btn d-lg-none me-3">
            <i class="bi bi-list"></i>
        </button>
       
        <div class="ms-auto d-flex align-items-center">
            <div class="position-relative me-3">
                <i class="bi bi-bell fs-5"></i>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" id="logoutBtn">
                            <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
   
    <div class="table-container animate-fadein">
        <div class="table-title">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="mb-0">Billing Management</h3>
                <button class="btn btn-primary" id="addBillingBtn" data-bs-toggle="modal" data-bs-target="#billingModal">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Create New Billing
                </button>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search consumer...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="month" class="form-control" id="monthFilter">
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="billingTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Consumer</th>
                        <th>Type</th>
                        <th>Meter No.</th>
                        <th>Due Date</th>
                        <th>Previous Reading</th>
                        <th>Current Reading</th>
                        <th>Consumption</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Billing data will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Simplified Billing Modal -->
<div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Create New Billing</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="billingForm">
                    <input type="hidden" id="billingId">
                    
                    <!-- Consumer Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Consumer</label>
                        <div class="dropdown">
                            <button class="form-select text-start" type="button" id="consumerDropdownButton" data-bs-toggle="dropdown">
                                <span id="selectedConsumerText">Select Consumer</span>
                            </button>
                            <input type="hidden" id="consumer_id" required>
                            <ul class="dropdown-menu w-100 p-0">
                                <li class="p-2">
                                    <input type="text" class="form-control" id="consumerSearch" placeholder="Search...">
                                </li>
                                <li><hr class="my-1"></li>
                                <div id="consumerOptions" style="max-height: 200px; overflow-y: auto;">
                                    <div class="text-center p-3">
                                        <div class="spinner-border spinner-border-sm"></div>
                                        <span>Loading consumers...</span>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>

                    <!-- Type and Meter No. -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Type</label>
                            <input type="text" class="form-control" id="type" placeholder="Auto-filled from consumer" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meter No.</label>
                            <input type="text" class="form-control" id="meterNumber" placeholder="Enter meter number" readonly>
                        </div>
                    </div>

                    <!-- Billing Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Due Date</label>
                            <input type="date" class="form-control" id="dueDate" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <!-- Changed from dropdown to read-only input -->
                            <input type="text" class="form-control" id="status" readonly value="unpaid">
                        </div>
                    </div>

                    <!-- Water Readings -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Previous (m³)</label>
                            <input type="number" class="form-control" id="previousReading" step="0.01" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Current (m³)</label>
                            <input type="number" class="form-control" id="currentReading" step="0.01" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Consumption (m³)</label>
                            <input type="number" class="form-control" id="consumption" readonly>
                        </div>
                    </div>

                    <!-- Amounts -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="totalAmount" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBilling">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="paymentForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Process Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="paymentBillingId" name="billing_id">
          <div class="mb-3">
            <label>Amount Due</label>
            <input type="text" id="paymentAmountDue" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label>Payment Amount</label>
            <input type="number" id="paymentAmount" class="form-control" min="0" step="0.01" required>
          </div>
          <div class="mb-3">
            <label>Change</label>
            <input type="text" id="paymentChange" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label>Payment Date</label>
            <input type="date" id="paymentDate" name="payment_date" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit Payment</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="receiptModalLabel">Payment Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="receipt-container" id="receiptContent">
          <!-- Receipt content will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="printReceiptBtn">Print Receipt</button>
      </div>
    </div>
  </div>
</div>



<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Moment.js for date handling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable with payment method column
    const table = $('#billingTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('accountant.billings.data') }}",
            type: 'GET',
            data: function(d) {
                d.status = $('#statusFilter').val();
                d.month = $('#monthFilter').val();
                d.payment_method = $('#paymentMethodFilter').val();
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            { 
                data: 'consumer', 
                name: 'consumer.first_name',
                render: function(data, type, row) {
                    return data ? data.first_name + ' ' + data.last_name : 'N/A';
                }
            },
            { data: 'consumer_type', name: 'consumer_type' },
            { data: 'meter_no', name: 'meter_no' },
            { 
                data: 'due_date', 
                name: 'due_date',
                render: function(data) {
                    return data ? moment(data).format('MMM D, YYYY') : '';
                }
            },
            { data: 'previous_reading', name: 'previous_reading' },
            { data: 'current_reading', name: 'current_reading' },
            { data: 'consumption', name: 'consumption' },
            { 
                data: 'total_amount', 
                name: 'total_amount',
                render: function(data, type, row) {
                    // Handle various data types and edge cases
                    let amount = 0;
                    
                    if (typeof data === 'number') {
                        amount = data;
                    } else if (typeof data === 'string') {
                        // Remove currency symbol and commas if present
                        const cleanString = data.replace(/[₱,]/g, '').trim();
                        amount = parseFloat(cleanString) || 0;
                    } else if (data) {
                        amount = parseFloat(data) || 0;
                    }
                    
                    // Format with Philippine Peso symbol
                    let formattedAmount = '₱' + amount.toFixed(2);
                    
                    // Add penalty indicator if applicable
                    if (row.penalty_amount && row.penalty_amount > 0) {
                        formattedAmount += ' <span class="penalty-indicator">(+₱' + row.penalty_amount.toFixed(2) + ' penalty)</span>';
                    }
                    
                    return formattedAmount;
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    let badgeClass = '';
                    let icon = '';
                    
                    if (data === 'paid') {
                        badgeClass = 'badge-paid';
                        icon = '<i class="bi bi-check-circle-fill"></i>';
                    } 
                    else if (data === 'unpaid') {
                        badgeClass = 'badge-unpaid';
                        icon = '<i class="bi bi-exclamation-circle-fill"></i>';
                    }
                    else if (data === 'overdue') {
                        badgeClass = 'badge-overdue';
                        icon = '<i class="bi bi-clock-fill"></i>';
                    }
                    else {
                        badgeClass = 'badge-secondary';
                        icon = '<i class="bi bi-question-circle-fill"></i>';
                    }
                    
                    return `<span class="badge ${badgeClass}">${icon} ${data.toUpperCase()}</span>`;
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let paymentButton = '';
                    
                    // Only show payment button if status is unpaid or overdue
                    if (row.status === 'unpaid' || row.status === 'overdue') {
                        paymentButton = `<button class="btn btn-sm btn-success payment-btn" data-id="${data}">
                            <i class="bi bi-cash-coin"></i> Pay
                        </button>`;
                    }
                    
                    return `
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${data}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                        ${paymentButton}
                        <button class="btn btn-sm btn-info receipt-btn" data-id="${data}">
                            <i class="bi bi-receipt"></i> Receipt
                        </button>
                    </div>
                    `;
                }
            }
        ]
    });

    // Apply filters
    $('#statusFilter, #monthFilter').change(function() {
        table.ajax.reload();
    });

    // Search button
    $('#searchBtn').click(function() {
        table.search($('#searchInput').val()).draw();
    });

    // Initialize modal when opened
    $('#billingModal').on('show.bs.modal', function() {
        resetForm();
        fetchConsumers();
    });


    $(document).on('click', '.receipt-btn', function(e) {
    e.preventDefault();
    const billingId = $(this).data('id');
    
    // Show loading state
    $('#receiptModal').modal('show');
    $('#receiptContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Generating receipt...</p>
        </div>
    `);
    
    // Fetch billing details for receipt
    $.ajax({
        url: `/accountant/billings/${billingId}/receipt`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const billing = response.data;
                generateReceipt(billing);
            } else {
                $('#receiptContent').html(`
                    <div class="text-center py-4 text-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        <p class="mt-2">Failed to generate receipt: ${response.message}</p>
                </div>
                `);
            }
        },
        error: function(xhr) {
            console.error('Error fetching receipt data:', xhr.responseText);
            $('#receiptContent').html(`
                <div class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    <p class="mt-2">Failed to load receipt data. Please try again.</p>
                </div>
            `);
        }
    });
});

// Function to generate receipt HTML based on the provided template
function generateReceipt(billing) {
    const paymentDate = billing.payment_date ? new Date(billing.payment_date) : new Date();
    const readingDate = new Date(billing.reading_date || billing.due_date);
    
    // Format dates
    const formattedPaymentDate = paymentDate.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).replace(/\//g, '-');
    
    const formattedReadingDate = readingDate.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).replace(/\//g, '-');
    
    const nextMonth = new Date(readingDate);
    nextMonth.setMonth(nextMonth.getMonth() + 1);
    const formattedNextMonth = nextMonth.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).replace(/\//g, '-');
    
    // Calculate due date (22nd of current month)
    const dueDate = new Date(readingDate);
    dueDate.setDate(22);
    const formattedDueDate = dueDate.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).replace(/\//g, '-');
    
    // Calculate disconnection date (25th of current month)
    const disconnectionDate = new Date(readingDate);
    disconnectionDate.setDate(25);
    const formattedDisconnectionDate = disconnectionDate.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).replace(/\//g, '-');
    
    // Build consumer name
    const consumer = billing.consumer;
    let consumerName = consumer.first_name || '';
    if (consumer.middle_name) consumerName += ' ' + consumer.middle_name;
    consumerName += ' ' + (consumer.last_name || '');
    if (consumer.suffix) consumerName += ' ' + consumer.suffix;
    
    // Generate bill number starting from 0001
    // If billing.id is not starting from 1, we'll calculate the sequential number
    const billNumber = String(billing.sequential_number || billing.id).padStart(4, '0');
    
    // Get current month name for bill month
    const monthNames = ["January", "February", "March", "April", "May", "June",
                       "July", "August", "September", "October", "November", "December"];
    const billMonth = `${readingDate.getDate()}-${monthNames[readingDate.getMonth()].substring(0, 3)}`;
    
    // Calculate total amount with penalty
    const totalAmount = parseFloat(billing.total_amount) + (billing.penalty_amount || 0);
    
    const receiptHTML = `
        <div class="receipt-container" style="font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 15px; border: 1px solid #000; background-color: white;">
            <div class="receipt-header" style="text-align: center; margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 10px;">
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                    <img src="{{ asset('image/santafe.png') }}" style="width: 60px; height: 60px; margin-right: 15px;" alt="Santa Fe Logo">
                    <div>
                        <h4 style="margin: 5px 0; font-size: 18px;">Santa Fe Water System and Management Board</h4>
                        <p style="margin: 3px 0; font-size: 14px;">Santa Fe New Municipal Hall</p>
                    </div>
                </div>
                <p style="margin: 3px 0; font-size: 14px;">PooC, Santa Fe, Cebu 6047</p>
                <p style="margin: 3px 0; font-size: 14px;">CONTACT NO. 09469615234/09305694771</p>
            </div>
            
            <div style="text-align: center; margin: 10px 0;">
                <h3 style="margin: 5px 0; text-decoration: underline;">STATEMENT OF ACCOUNT</h3>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 12px;">
                <tr>
                    <td style="width: 30%; padding: 2px;"><strong>Account Type</strong></td>
                    <td style="width: 35%; padding: 2px;">${consumer.consumer_type || 'RESIDENTIAL'}</td>
                    <td style="width: 15%; padding: 2px;"><strong>Meter No.</strong></td>
                    <td style="width: 20%; padding: 2px;">${consumer.meter_no || 'N/A'}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>Bill Num</strong></td>
                    <td style="padding: 2px;">: ${billNumber}</td>
                    <td style="padding: 2px;"><strong>Brand</strong></td>
                    <td style="padding: 2px;">:</td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>Name</strong></td>
                    <td style="padding: 2px;">: ${consumerName.trim()}</td>
                    <td style="padding: 2px;"><strong>Bill Month</strong></td>
                    <td style="padding: 2px;">${billMonth}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>Address</strong></td>
                    <td style="padding: 2px;" colspan="3">: ${consumer.address || 'PooC, Santa Fe, Cebu'}</td>
                </tr>
            </table>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 12px;">
                <tr>
                    <td colspan="2" style="padding: 2px;"><strong>Reading</strong></td>
                    <td colspan="2" style="padding: 2px;"><strong>CHARGES</strong></td>
                </tr>
                <tr>
                    <td style="width: 15%; padding: 2px;"><strong>From</strong></td>
                    <td style="width: 35%; padding: 2px;">: ${formattedReadingDate}</td>
                    <td style="width: 25%; padding: 2px;"><strong>Current :</strong></td>
                    <td style="width: 25%; padding: 2px; text-align: right;">${parseFloat(billing.total_amount).toFixed(2)}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>To</strong></td>
                    <td style="padding: 2px;">: ${formattedNextMonth}</td>
                    <td style="padding: 2px;"><strong>Past Due :</strong></td>
                    <td style="padding: 2px; text-align: right;">0.00</td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>Previous</strong></td>
                    <td style="padding: 2px;">: ${parseFloat(billing.previous_reading).toFixed(0)}</td>
                    <td style="padding: 2px;"><strong>Penalty :</strong></td>
                    <td style="padding: 2px; text-align: right;">${billing.penalty_amount ? parseFloat(billing.penalty_amount).toFixed(2) : '0.00'}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>Present</strong></td>
                    <td style="padding: 2px;">: ${parseFloat(billing.current_reading).toFixed(0)}</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>Usage</strong></td>
                    <td style="padding: 2px;">: ${parseFloat(billing.consumption).toFixed(0)}</td>
                    <td style="padding: 2px;"></td>
                    <td style="padding: 2px;"></td>
                </tr>
            </table>
            
            <div style="text-align: right; margin: 15px 0; border-top: 1px solid #000; padding-top: 5px;">
                <p style="margin: 5px 0; font-weight: bold;">TOTAL BEFORE DUE DATE : Php ${parseFloat(billing.total_amount).toFixed(2)}</p>
                ${billing.penalty_amount && billing.penalty_amount > 0 ? 
                `<p style="margin: 5px 0; font-weight: bold; color: #dc3545;">PENALTY : Php ${parseFloat(billing.penalty_amount).toFixed(2)}</p>
                <p style="margin: 5px 0; font-weight: bold;">TOTAL AMOUNT DUE : Php ${totalAmount.toFixed(2)}</p>` : ''}
            </div>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 12px;">
                <tr>
                    <td style="padding: 2px;"><strong>DUE DATE</strong></td>
                    <td style="padding: 2px;">: ${formattedDueDate}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;"><strong>DISCONNECTION DATE</strong></td>
                    <td style="padding: 2px;">: ${formattedDisconnectionDate}</td>
                </tr>
            </table>
            
            <div style="text-align: center; margin: 15px 0; font-style: italic;">
                <p style="margin: 5px 0; font-size: 12px;">"AYAW SAYANGI ANG TUBIG KAY ANG TUBIG KINABUHI"</p>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <p style="margin: 3px 0; font-size: 11px; font-weight: bold;">FOR DISCONNECTION</p>
                <p style="margin: 3px 0; font-size: 11px;">months</p>
            </div>
            
            <div class="receipt-footer" style="text-align: center; margin-top: 20px; border-top: 1px dashed #000; padding-top: 10px; font-size: 10px;">
                <p style="margin: 3px 0;">Thank you for your payment!</p>
                <p style="margin: 3px 0;">Santa Fe Water System and Management Board</p>
            </div>
        </div>
    `;
    
    $('#receiptContent').html(receiptHTML);
}

// Handle print receipt button
$('#printReceiptBtn').click(function() {
    const printWindow = window.open('', '_blank');
    const receiptContent = document.getElementById('receiptContent').innerHTML;
    
    // For printing, we need to handle the image path differently
    const printContent = receiptContent.replace(
        'src="{{ asset('image/santafe.png') }}"',
        'src="/image/santafe.png"'
    );
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print Receipt</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
                @media print {
                    body {
                        padding: 0;
                    }
                    .receipt-container {
                        box-shadow: none;
                        border: 1px solid #000;
                        max-width: 100%;
                    }
                    img {
                        max-width: 60px;
                        height: auto;
                    }
                }
            </style>
        </head>
        <body>
            ${printContent}
            <script>
                window.onload = function() {
                    // Add a small delay to ensure images are loaded before printing
                    setTimeout(function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 100);
                    }, 500);
                }
            <\/script>
        </body>
        </html>
    `);
    
    printWindow.document.close();
});
    // Reset all form fields
    function resetForm() {
        $('#billingForm')[0].reset();
        $('#billingId').val('');
        $('#selectedConsumerText').text('Select Consumer');
        $('#consumer_id').val('');
        $('#type').val('');
        $('#meterNumber').val('');
        $('#previousReading').val('');
        $('#currentReading').val('');
        $('#consumption').val('');
        $('#totalAmount').val('');
        
        // Set default due date (15 days from today)
        const today = new Date();
        const dueDate = new Date(today);
        dueDate.setDate(dueDate.getDate() + 15);
        $('#dueDate').val(dueDate.toISOString().split('T')[0]);
    }

    // Function to fetch consumers and populate dropdown
    function fetchConsumers() {
        $.ajax({
            url: '/admin-consumer/create',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#consumerOptions').html(`
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-hourglass"></i>
                        <span class="ms-2">Loading consumers...</span>
                    </div>
                `);
            },
            success: function(response) {
                if (response && response.length > 0) {
                    populateConsumerOptions(response);
                    setupSearchFunctionality();
                } else {
                    $('#consumerOptions').html(`
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-info-circle"></i>
                            <span class="ms-2">No consumers found</span>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                console.error('Error loading consumers:', xhr.responseText);
                $('#consumerOptions').html(`
                    <div class="text-center py-3 text-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        <span class="ms-2">Failed to load consumers</span>
                    </div>
                `);
            }
        });
    }
    // Function to populate consumer options
    function populateConsumerOptions(consumers) {
        const optionsContainer = $('#consumerOptions');
        optionsContainer.empty();
        
        consumers.forEach((consumer) => {
            let fullName = consumer.first_name || '';
            if (consumer.middle_name) fullName += ' ' + consumer.middle_name;
            fullName += ' ' + (consumer.last_name || '');
            if (consumer.suffix) fullName += ' ' + consumer.suffix;
            
            const optionItem = $(`
                <li>
                    <a class="dropdown-item consumer-option" href="#" data-id="${consumer.id}">
                        ${fullName.trim()} 
                        <small class="text-muted d-block">Meter: ${consumer.meter_no || 'N/A'}</small>
                    </a>
                </li>
            `);
            
            optionItem.on('click', function(e) {
                e.preventDefault();
                selectConsumer(consumer);
            });
            
            optionsContainer.append(optionItem);
        });
    }

    // Function to handle consumer selection
    function selectConsumer(consumer) {
        let fullName = consumer.first_name || '';
        if (consumer.middle_name) fullName += ' ' + consumer.middle_name;
        fullName += ' ' + (consumer.last_name || '');
        if (consumer.suffix) fullName += ' ' + consumer.suffix;
        fullName = fullName.trim();
        
        // Update consumer display
        $('#selectedConsumerText').text(fullName);
        $('#consumer_id').val(consumer.id);
        $('#type').val(consumer.consumer_type || 'N/A');
        $('#meterNumber').val(consumer.meter_no || 'N/A');
        
        // Close dropdown
        $('.dropdown-menu').removeClass('show');
        
        // Fetch the consumer's last reading
        fetchLastReading(consumer.id);
    }

    // Function to fetch last reading data
    function fetchLastReading(consumerId) {
        $.ajax({
            url: `/billing/last-reading/${consumerId}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#previousReading').val('Loading...');
                $('#currentReading').val('Loading...');
                $('#consumption').val('Loading...');
            },
            success: function(response) {
                if (response.last_reading) {
                    const last = response.last_reading;
                    $('#previousReading').val(parseFloat(last.previous_reading).toFixed(2));
                    $('#currentReading').val(parseFloat(last.current_reading).toFixed(2));
                    calculateConsumption();
                } else {
                    $('#previousReading').val('0.00');
                    $('#currentReading').val('0.00');
                    $('#consumption').val('0.00');
                }
                calculateWaterBill();
            },
            error: function(xhr) {
                console.error('Error fetching last reading:', xhr.responseText);
                $('#previousReading').val('Error');
                $('#currentReading').val('Error');
                $('#consumption').val('Error');
                alert('Failed to retrieve meter readings.');
            }
        });
    }

    // Function to calculate consumption automatically
    function calculateConsumption() {
        const prevReading = parseFloat($('#previousReading').val()) || 0;
        const currReading = parseFloat($('#currentReading').val()) || 0;
        
        if (isNaN(prevReading)) {
            $('#consumption').val('0.00');
            return;
        }
        
        if (isNaN(currReading)) {
            $('#consumption').val('0.00');
            return;
        }
        
        if (currReading < prevReading) {
            alert('Current reading cannot be less than previous reading');
            $('#currentReading').val(prevReading.toFixed(2));
            $('#consumption').val('0.00');
            return;
        }
        
        const consumption = currReading - prevReading;
        $('#consumption').val(consumption.toFixed(2));
    }

    // Calculate water bill
    function calculateWaterBill() {
        const consumerType = $('#type').val();
        const consumption = parseFloat($('#consumption').val()) || 0;
        
        if (!consumerType || isNaN(consumption)) {
            return;
        }
        
        $.ajax({
            url: '/water-rates/calculate',
            type: 'POST',
            data: {
                type: consumerType,
                consumption: consumption,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#totalAmount').val(response.amount.toFixed(2));
                    calculateChange();
                } else {
                    alert(response.message || 'Error calculating water bill');
                    $('#totalAmount').val('0.00');
                }
            },
            error: function(xhr) {
                console.error('Error calculating water bill:', xhr.responseText);
                $('#totalAmount').val('0.00');
                alert('Failed to calculate water bill. Please try again.');
            }
        });
    }


    // Setup search functionality for consumers
    function setupSearchFunctionality() {
        $('#consumerSearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.consumer-option').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).parent().toggle(text.includes(searchTerm));
            });
        });
    }

    // Save billing (create or update)
    $('#saveBilling').click(function() {
        const formData = {
            consumer_id: $('#consumer_id').val(),
            current_reading: $('#currentReading').val(),
            due_date: $('#dueDate').val(),
            status: $('#status').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        const billingId = $('#billingId').val();
        const url = billingId ? `/accountant/billings/${billingId}` : '/accountant/billings';
        const method = billingId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#billingModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = '';
                    for (const field in errors) {
                        errorMessages += errors[field].join('<br>') + '<br>';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessages
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'An error occurred'
                    });
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
    const billingId = $(this).data('id');
    
    // Show loading state
    $('#billingModal').modal('show');
    $('#selectedConsumerText').html(`
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    $.ajax({
        url: `/accountant/billings/${billingId}/edit`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const billing = response.data.billing;
                const consumer = response.data.consumer;
                
                // Fill the form with consumer data
                $('#billingId').val(billing.id);
                
                // Build the full name with proper handling of optional fields
                let fullName = consumer.first_name || '';
                if (consumer.middle_name) fullName += ' ' + consumer.middle_name;
                fullName += ' ' + (consumer.last_name || '');
                if (consumer.suffix) fullName += ' ' + consumer.suffix;
                fullName = fullName.trim();
                
                $('#selectedConsumerText').text(fullName);
                $('#consumer_id').val(consumer.id);
                $('#type').val(consumer.consumer_type || 'N/A');
                $('#meterNumber').val(consumer.meter_no || 'N/A');
                
                // Fill billing data
                $('#dueDate').val(billing.due_date.split('T')[0]);
                $('#previousReading').val(parseFloat(billing.previous_reading).toFixed(2));
                $('#currentReading').val(parseFloat(billing.current_reading).toFixed(2));
                $('#consumption').val(parseFloat(billing.consumption).toFixed(2));
                $('#totalAmount').val(parseFloat(billing.total_amount).toFixed(2));
                $('#status').val(billing.status || 'unpaid');
                
                // Update modal title and button text
                $('#billingModal .modal-title').text('Edit Billing');
                $('#saveBilling').html('<i class="bi bi-save me-2"></i> Update Billing');
            }
        },
        error: function(xhr) {
            // Show error message in consumer display
            $('#selectedConsumerText').text('Error loading consumer');
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Failed to load billing data'
            });
        }
    });
});

    // Delete billing
    $(document).on('click', '.delete-btn', function() {
        const billingId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/accountant/billings/${billingId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message || 'Failed to delete billing'
                        });
                    }
                });
            }
        });
    });
});

// Handle payment button click
$(document).on('click', '.payment-btn', function(e) {
    e.preventDefault();
    const billingId = $(this).data('id');
    
    console.log('Payment button clicked for billing ID:', billingId); // Debug log

    // Show modal and reset fields
    $('#paymentModal').modal('show');
    $('#paymentAmountDue').val('Loading...');
    $('#paymentAmount').val('');
    $('#paymentChange').val('₱0.00');
    $('#paymentBillingId').val(billingId); // Set the billing ID immediately
    $('#paymentDate').val(new Date().toISOString().split('T')[0]);

    // Load billing details via AJAX
    $.ajax({
        url: `/accountant/billings/${billingId}/details`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Billing details response:', response); // Debug log
            if (response.success) {
                const billing = response.data;
                const totalAmount = parseFloat(billing.total_amount);
                const penaltyAmount = parseFloat(billing.penalty_amount || 0);
                const totalDue = totalAmount + penaltyAmount;
                console.log('Total amount:', totalAmount, 'Penalty:', penaltyAmount, 'Total Due:', totalDue); // Debug log

                $('#paymentBillingId').val(billing.id);
                $('#paymentAmountDue').val('₱' + totalDue.toFixed(2));
            } else {
                console.error('Failed to load billing details');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load billing data.'
                });
                $('#paymentModal').modal('hide');
            }
        },
        error: function(xhr) {
            console.error('AJAX error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Failed to load billing data.'
            });
            $('#paymentModal').modal('hide');
        }
    });
});

// Keep track of paid billing IDs
let paidBillings = new Set();

$('#paymentForm').on('submit', function(e) {
    e.preventDefault();

    const billingId = $('#paymentBillingId').val();
    const totalAmount = parseFloat($('#paymentAmountDue').val().replace(/[₱,]/g, '')) || 0;
    const formData = {
        billing_id: billingId,
        payment_amount: parseFloat($('#paymentAmount').val()),
        payment_date: $('#paymentDate').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    // ❌ Prevent duplicate payment
    if (paidBillings.has(billingId)) {
        Swal.fire('Error', 'This bill has already been paid.', 'error');
        return;
    }

    // Validate: must be a number and > 0
    if (isNaN(formData.payment_amount) || formData.payment_amount <= 0) {
        Swal.fire('Error', 'Please enter a valid amount', 'error');
        return;
    }

    // Validate: must not be less than amount due
    if (formData.payment_amount < totalAmount) {
        Swal.fire('Error', 'Payment cannot be less than the total amount due (₱' + totalAmount.toFixed(2) + ')', 'error');
        return;
    }

    // Submit
    const submitBtn = $(this).find('[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

    $.ajax({
        url: '/payments/process',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Mark this billing as paid
                paidBillings.add(billingId);

                $('#paymentModal').modal('hide');
                $('#billingTable').DataTable().ajax.reload(null, false);
                Swal.fire('Success', response.message, 'success');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Payment failed', 'error');
        },
        complete: function() {
            submitBtn.prop('disabled', false).html('Submit Payment');
        }
    });
});


// Handle real-time change calculation
$(document).on('input', '#paymentAmount', function() {
    const totalAmount = parseFloat($('#paymentAmountDue').val().replace(/[₱,]/g, '')) || 0;
    const paymentAmount = parseFloat($(this).val()) || 0;
    const change = paymentAmount - totalAmount;

    $('#paymentChange').val(change >= 0 ? '₱' + change.toFixed(2) : '₱0.00');
});

// Optional: Clear change when modal closes
$('#paymentModal').on('hidden.bs.modal', function () {
    $('#paymentChange').val('₱0.00');
    $('#paymentAmount').val('');
});

// Logout functionality
$('#logoutBtn').click(function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Sign Out?',
        text: 'Are you sure you want to sign out?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Sign Out',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform logout - you can customize this based on your authentication system
            performLogout();
        }
    });
});

function performLogout() {
    // Show loading state
    Swal.fire({
        title: 'Signing Out...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Example: Send logout request to server
    // Replace this with your actual logout endpoint
    $.ajax({
        url: '/logout', // Your logout route
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Redirect to login page
            window.location.href = '/admin-login';
        },
        error: function(xhr) {
            // If AJAX fails, still redirect to login
            window.location.href = '/admin-login';
        }
    });
    
    // Alternative: Simple redirect (if no server-side logout needed)
    // window.location.href = '/login';
}
</script>
</body>
</html>