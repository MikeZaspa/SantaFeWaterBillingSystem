<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Santa Fe Water Billing System - Online Billing</title>
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

        /* Badge Styles */
        .badge {
            font-weight: 500;
            padding: 6px 10px;
            font-size: 0.75rem;
            border-radius: 4px;
            text-transform: capitalize;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .badge-paid {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .badge-unpaid {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .badge-overdue {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .badge-pending {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
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
        
        /* Payment method badges */
        .badge-online {
            background-color: rgba(111, 66, 193, 0.1);
            color: #6f42c1;
        }
        
        .badge-cash {
            background-color: rgba(32, 201, 151, 0.1);
            color: #20c997;
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
                <a class="nav-link" href="admin-accountant-consumer">
                    <i class="bi bi-people"></i> Billing
                </a>
            </li>

            <!-- Online Billing -->
            <li class="nav-item">
                <a class="nav-link active" href="online-billing">
                    <i class="bi bi-globe"></i> Online Billing
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
                    <span>Accountant</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="admin-logout">Sign out</a></li>
                </ul>
            </div>
        </div>
    </header>
   
    <div class="table-container animate-fadein">
        <div class="table-title">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="mb-0"> Online Billing Management</h3>
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
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="paymentMethodFilter">
                    <option value="">All Payment Methods</option>
                    <option value="gcash">GCash</option>
                    <option value="paymaya">Maya</option>
                </select>
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
    // Initialize DataTable
    const table = $('#billingTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("online-billing.data") }}',
            data: function (d) {
                d.status = $('#statusFilter').val();
                d.payment_method = $('#paymentMethodFilter').val();
                d.search = $('#searchInput').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
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
                data: 'reading_date', 
                name: 'reading_date',
                render: function(data) {
                    return data ? moment(data).format('MMM D, YYYY') : 'N/A';
                }
            },
            { data: 'previous_reading', name: 'previous_reading' },
            { data: 'current_reading', name: 'current_reading' },
            { data: 'consumption', name: 'consumption' },
            { 
                data: 'total_amount', 
                name: 'total_amount',
                render: function(data) {
                    return data ? '₱' + parseFloat(data).toFixed(2) : '₱0.00';
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data) {
                    let badgeClass = 'badge-secondary';
                    if (data === 'paid') badgeClass = 'badge-paid';
                    else if (data === 'unpaid') badgeClass = 'badge-unpaid';
                    else if (data === 'overdue') badgeClass = 'badge-overdue';
                    else if (data === 'pending') badgeClass = 'badge-pending';
                    
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-action btn-sm btn-primary view-btn" data-id="${data}" title="View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-action btn-sm btn-warning edit-btn" data-id="${data}" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-action btn-sm btn-danger delete-btn" data-id="${data}" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    // Apply filters
    $('#statusFilter, #paymentMethodFilter').change(function() {
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
        url: '/online-billing/consumers',
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
});
</script>
</body>
</html>