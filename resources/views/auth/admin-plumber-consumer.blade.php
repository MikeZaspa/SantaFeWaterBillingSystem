<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Santa Fe Water Billing System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="icon" type="image/png" href="image/santafe.png">
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
        
        .sidebar {
            width: 280px;
            background: #f8f9fa;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1050;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            transform: translateX(-100%);
        }
        
        .sidebar.active {
            transform: translateX(0);
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
        
        .main-content {
            min-height: 100vh;
            transition: all 0.3s ease;
            padding: 20px;
            width: 100%;
        }
        
        .main-content.active {
            margin-left: 280px;
            width: calc(100% - 280px);
        }
        
        .header {
            height: 70px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1040;
            background: white;
            padding: 0 15px;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1rem;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
            width: 100%;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.25);
        }
        
        .login-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Mobile overlay styles */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Mobile menu toggle button */
        .mobile-menu-toggle {
            font-size: 1.5rem;
            padding: 0.25rem 0.5rem;
            border: none;
            background: transparent;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadein {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        /* Decimal alignment */
        .decimal-align {
            text-align: right;
            padding-right: 20px !important;
        }
        
        /* Action buttons */
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin-right: 0.25rem;
        }
        
        /* Date column */
        .date-column {
            white-space: nowrap;
        }
        
        /* Custom scrollbar for consumer options */
        #consumerOptions::-webkit-scrollbar {
            width: 8px;
        }
        
        #consumerOptions::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        #consumerOptions::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        #consumerOptions::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* For Firefox */
        #consumerOptions {
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }
        
        /* Mobile Card View Styles */
        .mobile-card-view {
            display: none;
        }
        
        .billing-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 15px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        
        .billing-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .billing-card-header {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .billing-card-body {
            padding: 15px;
        }
        
        .billing-card-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .billing-card-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .billing-card-label {
            font-weight: 500;
            color: #6c757d;
            flex: 1;
        }
        
        .billing-card-value {
            flex: 2;
            text-align: right;
        }
        
        .billing-card-actions {
            padding: 10px 15px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        
        /* Responsive styles */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 280px;
                width: calc(100% - 280px);
            }
        }
        
        @media (max-width: 991px) {
            .desktop-table-view {
                display: none;
            }
            
            .mobile-card-view {
                display: block;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .card-header h5 {
                margin-bottom: 0;
            }
            
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }
            
            .decimal-align {
                padding-right: 10px !important;
            }
            
            .btn-action {
                padding: 0.2rem 0.4rem;
                font-size: 0.8rem;
            }
            
            .modal-dialog {
                margin: 0.5rem auto;
            }
            
            .modal-content {
                border-radius: 0;
            }
            
            .modal-body {
                padding: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .header {
                height: 60px;
                padding: 0 10px;
            }
            
            .sidebar-header {
                padding: 1rem;
            }
            
            .login-logo {
                width: 70px;
                height: 70px;
            }
            
            .sidebar-menu .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            .mobile-header-title {
                font-size: 0.9rem;
                max-width: 120px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            .position-relative.me-3 {
                display: none !important;
            }
            
            .dropdown-toggle span {
                display: none;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table th, .table td {
                padding: 0.3rem;
                font-size: 0.8rem;
            }
            
            .btn-action {
                padding: 0.15rem 0.3rem;
                font-size: 0.75rem;
            }
            
            .modal-dialog {
                width: 95%;
                max-width: none;
                margin: 0.5rem auto;
            }
            
            .modal-body {
                padding: 0.75rem;
            }
            
            .form-control, .form-select {
                font-size: 0.9rem;
                padding: 0.375rem 0.75rem;
            }
            
            .billing-card-header {
                padding: 10px 12px;
            }
            
            .billing-card-body {
                padding: 12px;
            }
            
            .billing-card-actions {
                padding: 8px 12px;
            }
        }
        
        /* DataTables responsive */
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            width: 150px !important;
        }
        
        @media (max-width: 767px) {
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: left;
            }
            
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0;
                margin-top: 0.5em;
            }
            
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none;
                text-align: center;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Mobile Overlay -->
<div class="mobile-overlay"></div>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header text-center">
        <img src="{{ asset('image/santafe.png') }}" class="login-logo img-fluid mb-3">
        <h1 class="h5">Santa Fe Water Billing</h1>
    </div>
    
    <nav class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="admin-plumber-dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="admin-plumber-consumer">
                    <i class="bi bi-people"></i> Reading
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-plumber-disconnection">
                    <i class="bi bi-x-circle"></i> Disconnection
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <header class="header d-flex align-items-center">
        <button id="sidebarToggle" class="btn d-lg-none me-3 mobile-menu-toggle">
            <i class="bi bi-list"></i>
        </button>
        <h2 class="h5 mb-0 mobile-header-title">Reading Dashboard Overview</h2>
        
        <div class="ms-auto d-flex align-items-center">
            <div class="position-relative me-3 d-none d-sm-block">
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
    
    <div class="container-fluid mt-3 mt-md-4">
        <div class="row">
            <div class="col-12">
                <div class="card animate-fadein">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-2 mb-md-0">Water Consumption Records</h5>
                        <button class="btn btn-primary btn-sm btn-md" data-bs-toggle="modal" data-bs-target="#addBillingModal">
                            <i class="bi bi-plus-circle me-1 me-md-2"></i>Add Reading
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Desktop Table View -->
                        <div class="table-responsive desktop-table-view">
                            <table class="table table-hover" id="billingTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Consumer</th>
                                        <th>Type</th>
                                        <th>Meter No.</th>
                                        <th class="decimal-align">Previous</th>
                                        <th class="decimal-align">Current</th>
                                        <th class="decimal-align">Consumption</th>
                                        <th class="date-column">Reading Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-card-view" id="mobileBillingCards">
                            <!-- Cards will be loaded via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Billing Modal -->
<div class="modal fade" id="addBillingModal" tabindex="-1" aria-labelledby="addBillingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBillingModalLabel">Add New Water Reading</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="billingForm">
                    @csrf
                    <input type="hidden" id="billing_id" name="billing_id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="consumerDropdown" class="form-label">Select Consumer</label>
                            <div class="dropdown" id="consumerDropdown">
                                <button class="form-select text-start" type="button" id="consumerDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="selectedConsumerText">Select Consumer</span>
                                </button>
                                <input type="hidden" id="consumer_id" name="consumer_id" required>
                                <ul class="dropdown-menu w-100" aria-labelledby="consumerDropdownButton">
                                    <li class="px-3 py-2">
                                        <input type="text" class="form-control" id="consumerSearch" placeholder="Search consumers..." autocomplete="off">
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <div id="consumerOptions" style="max-height: 200px; overflow-y: auto; overflow-x: hidden;">
                                        <!-- Options will be loaded here dynamically -->
                                    </div>
                                </ul>
                            </div>
                            <div id="consumerDisplayContainer" style="display: none;">
                                <input type="text" class="form-control" id="consumerDisplay" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="consumerType" class="form-label">Consumer Type</label>
                            <input type="text" class="form-control" id="consumerType" name="consumer_type" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="meterNumber" class="form-label">Meter Number</label>
                            <input type="text" class="form-control" id="meterNumber" name="meter_no" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="readingDate" class="form-label">Reading Date</label>
                            <input type="date" class="form-control" id="readingDate" name="reading_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="previousReading" class="form-label">Previous (cu.m)</label>
                            <input type="number" step="0.01" class="form-control decimal-input" id="previousReading" name="previous_reading" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="currentReading" class="form-label">Current (cu.m)</label>
                            <input type="number" step="0.01" class="form-control decimal-input" id="currentReading" name="current_reading" required>
                        </div>
                        <div class="col-md-4">
                            <label for="consumption" class="form-label">Consumption (cu.m)</label>
                            <input type="number" step="0.01" class="form-control decimal-input" id="consumption" name="consumption" readonly>
                        </div>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Current reading must be greater than previous reading.
                    </div>       
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBilling">Save Reading</button>
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

<script>
$(document).ready(function() {
    // Mobile sidebar toggle functionality
    const sidebar = $('.sidebar');
    const mainContent = $('.main-content');
    const sidebarToggle = $('#sidebarToggle');
    const mobileOverlay = $('.mobile-overlay');
    
    sidebarToggle.on('click', function() {
        sidebar.toggleClass('active');
        mainContent.toggleClass('active');
        mobileOverlay.toggleClass('active');
        
        // Prevent scrolling when sidebar is open
        if (sidebar.hasClass('active')) {
            $('body').css('overflow', 'hidden');
        } else {
            $('body').css('overflow', '');
        }
    });
    
    // Close sidebar when clicking on overlay
    mobileOverlay.on('click', function() {
        sidebar.removeClass('active');
        mainContent.removeClass('active');
        mobileOverlay.removeClass('active');
        $('body').css('overflow', '');
    });
    
    // Close sidebar when clicking on a nav link (for mobile)
    $('.sidebar-menu .nav-link').on('click', function() {
        if ($(window).width() < 992) {
            sidebar.removeClass('active');
            mainContent.removeClass('active');
            mobileOverlay.removeClass('active');
            $('body').css('overflow', '');
        }
    });
    
    // Handle window resize
    $(window).on('resize', function() {
        // Close sidebar if window is resized to desktop size
        if ($(window).width() >= 992) {
            sidebar.removeClass('active');
            mainContent.removeClass('active');
            mobileOverlay.removeClass('active');
            $('body').css('overflow', '');
        }
    });

    // Initialize DataTable with responsive settings
    var table = $('#billingTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('billings.index') }}",
            type: "GET",
            dataType: "json"
        },
        columns: [
            { 
                data: null,
                name: 'DT_RowIndex',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'consumer',
                name: 'consumer.first_name',
                render: function(data, type, row) {
                    if (!data) return 'N/A';
                    let name = data.first_name || '';
                    if (data.middle_name) name += ' ' + data.middle_name;
                    name += ' ' + (data.last_name || '');
                    if (data.suffix) name += ' ' + data.suffix;
                    return name.trim() || 'N/A';
                }
            },  
            { data: 'consumer_type', name: 'consumer_type' },
            { data: 'meter_no', name: 'meter_no' },
            { 
                data: 'previous_reading', 
                name: 'previous_reading',
                className: 'decimal-align',
                render: function(data) {
                    return data ? parseFloat(data).toFixed(2) : '0.00';
                }
            },
            { 
                data: 'current_reading', 
                name: 'current_reading',
                className: 'decimal-align',
                render: function(data) {
                    return data ? parseFloat(data).toFixed(2) : '0.00';
                }
            },
            { 
                data: 'consumption', 
                name: 'consumption',
                className: 'decimal-align',
                render: function(data) {
                    return data ? parseFloat(data).toFixed(2) : '0.00';
                }
            },
            { 
                data: 'reading_date', 
                name: 'reading_date',
                className: 'date-column',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) : 'N/A';
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary btn-action edit-btn" data-id="${data}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-action delete-btn" data-id="${data}" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
        createdRow: function(row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        },
        language: {
            lengthMenu: "Show _MENU_ entries",
            search: "Search:",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function(settings) {
            // Update mobile cards when table data changes
            updateMobileCards(settings.json.data);
        }
    });

    // Function to update mobile cards with data
    function updateMobileCards(data) {
        const cardsContainer = $('#mobileBillingCards');
        cardsContainer.empty();
        
        if (!data || data.length === 0) {
            cardsContainer.html(`
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                    <p>No billing records found</p>
                </div>
            `);
            return;
        }
        
        data.forEach((item, index) => {
            const consumerName = item.consumer ? 
                `${item.consumer.first_name || ''} ${item.consumer.middle_name || ''} ${item.consumer.last_name || ''} ${item.consumer.suffix || ''}`.trim() : 
                'N/A';
                
            const readingDate = item.reading_date ? 
                new Date(item.reading_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                }) : 
                'N/A';
                
            const card = `
                <div class="billing-card" data-id="${item.id}">
                    <div class="billing-card-header">
                        <span>#${index + 1}</span>
                        <span class="badge bg-secondary">${item.consumer_type || 'N/A'}</span>
                    </div>
                    <div class="billing-card-body">
                        <div class="billing-card-row">
                            <span class="billing-card-label">Consumer:</span>
                            <span class="billing-card-value">${consumerName}</span>
                        </div>
                        <div class="billing-card-row">
                            <span class="billing-card-label">Meter No:</span>
                            <span class="billing-card-value">${item.meter_no || 'N/A'}</span>
                        </div>
                        <div class="billing-card-row">
                            <span class="billing-card-label">Previous:</span>
                            <span class="billing-card-value">${item.previous_reading ? parseFloat(item.previous_reading).toFixed(2) : '0.00'} cu.m</span>
                        </div>
                        <div class="billing-card-row">
                            <span class="billing-card-label">Current:</span>
                            <span class="billing-card-value">${item.current_reading ? parseFloat(item.current_reading).toFixed(2) : '0.00'} cu.m</span>
                        </div>
                        <div class="billing-card-row">
                            <span class="billing-card-label">Consumption:</span>
                            <span class="billing-card-value">${item.consumption ? parseFloat(item.consumption).toFixed(2) : '0.00'} cu.m</span>
                        </div>
                        <div class="billing-card-row">
                            <span class="billing-card-label">Reading Date:</span>
                            <span class="billing-card-value">${readingDate}</span>
                        </div>
                    </div>
                    <div class="billing-card-actions">
                        <button class="btn btn-sm btn-primary btn-action edit-btn" data-id="${item.id}" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action delete-btn" data-id="${item.id}" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            cardsContainer.append(card);
        });
    }

    // Set today's date as default
    $('#readingDate').val(new Date().toISOString().split('T')[0]);

    // Calculate consumption when current reading changes
    $('#currentReading').on('input', function() {
        const prev = parseFloat($('#previousReading').val()) || 0;
        const current = parseFloat($(this).val()) || 0;
        const consumption = current - prev;
        
        if (current > prev) {
            $('#consumption').val(consumption.toFixed(2));
            $(this).removeClass('is-invalid');
        } else {
            $('#consumption').val('0.00');
            $(this).addClass('is-invalid');
        }
    });

    // Fetch consumers when the modal is shown
    $('#addBillingModal').on('show.bs.modal', function() {
        fetchConsumers();
    });

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
                populateConsumerOptions(response);
                setupSearchFunctionality();
            },
            error: function(xhr) {
                console.error('Error loading consumers:', xhr.responseText);
                $('#consumerOptions').html(`
                    <div class="text-center py-3 text-danger">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span class="ms-2">Failed to load consumers</span>
                    </div>
                `);
                showErrorToast('Failed to load consumers. Please try again.');
            }
        });
    }

    // Populate consumer options in dropdown
    function populateConsumerOptions(consumers) {
        const optionsContainer = $('#consumerOptions');
        optionsContainer.empty();
        
        if (consumers.length === 0) {
            optionsContainer.html(`
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-info-circle-fill"></i>
                    <span class="ms-2">No consumers found</span>
                </div>
            `);
            return;
        }

        consumers.forEach((consumer, index) => {
            const fullName = formatConsumerName(consumer);
            const option = $(`
                <li class="dropdown-item consumer-option" data-id="${consumer.id}" data-type="${consumer.consumer_type}" data-meter="${consumer.meter_no}">
                    <div class="d-flex align-items-center">
                        <span class="me-2 text-muted">${index + 1}.</span>
                        <div class="consumer-name">${fullName}</div>
                    </div>
                </li>
            `);
            
            option.on('click', function() {
                selectConsumer(consumer);
            });
            
            optionsContainer.append(option);
        });
    }

    // Format consumer name with middle name and suffix if available
    function formatConsumerName(consumer) {
        let fullName = consumer.first_name || '';
        if (consumer.middle_name) fullName += ' ' + consumer.middle_name;
        fullName += ' ' + (consumer.last_name || '');
        if (consumer.suffix) fullName += ' ' + consumer.suffix;
        return fullName.trim();
    }

    // Setup search functionality
    function setupSearchFunctionality() {
        const searchInput = $('#consumerSearch');
        const options = $('.consumer-option');
        
        searchInput.on('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            options.each(function() {
                const optionText = $(this).text().toLowerCase();
                $(this).toggle(optionText.includes(searchTerm));
            });
        });
        
        // Clear search when dropdown is closed
        $('#consumerDropdownButton').on('hidden.bs.dropdown', function() {
            searchInput.val('');
            options.show();
        });
    }

    // Handle consumer selection
    function selectConsumer(consumer) {
        const fullName = formatConsumerName(consumer);
        
        $('#selectedConsumerText').text(fullName);
        $('#consumer_id').val(consumer.id);
        $('#consumerType').val(consumer.consumer_type || '');
        $('#meterNumber').val(consumer.meter_no || '');
        
        // Close the dropdown
        $('#consumerDropdownButton').dropdown('toggle');
        
        // Fetch the last reading for this consumer
        fetchLastReading(consumer.id);
    }

    // Fetch last reading for selected consumer
    function fetchLastReading(consumerId) {
        $('#previousReading').val('Loading...');
        $('#currentReading').prop('disabled', true);
        $('.last-reading-info').remove();
        
        $.ajax({
            url: `/consumers/${consumerId}/last-reading`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let message = '';
                if (response.last_reading) {
                    const lastReading = parseFloat(response.last_reading.current_reading).toFixed(2);
                    $('#previousReading').val(lastReading);
                    const lastDate = new Date(response.last_reading.reading_date).toLocaleDateString();
                    message = `Last reading was on ${lastDate}`;
                } else {
                    $('#previousReading').val('0.00');
                    message = 'No previous reading found';
                }
                
                $('#previousReading').after(
                    `<small class="text-muted form-text last-reading-info">${message}</small>`
                );
                $('#currentReading').prop('disabled', false).focus();
            },
            error: function(xhr) {
                console.error('Error loading last reading:', xhr.responseText);
                $('#previousReading').val('0.00');
                $('#currentReading').prop('disabled', false);
                showErrorToast('Failed to load previous reading');
            }
        });
    }

    // Reset form when modal is closed
    $('#addBillingModal').on('hidden.bs.modal', function() {
        $('#billingForm')[0].reset();
        $('#billing_id').val('');
        $('#selectedConsumerText').text('Select Consumer');
        $('#consumer_id').val('');
        $('#consumerSearch').val('');
        $('#consumerType').val('');
        $('#consumption').val('0.00');
        $('#currentReading').removeClass('is-invalid');
        $('.last-reading-info').remove();
        $('.consumer-option').show();
        resetReadingFields();
        $('#addBillingModalLabel').text('Add New Water Reading');
        $('#saveBilling').text('Save Reading');
        
        // Restore the dropdown
        $('#consumerDropdown').show();
        $('#consumerDisplayContainer').hide();
    });

    function resetReadingFields() {
        $('#meterNumber').val('');
        $('#consumerType').val('');
        $('#previousReading').val('0.00');
        $('#currentReading').val('').removeClass('is-invalid');
        $('#consumption').val('0.00');
        $('.last-reading-info').remove();
    }

    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Save billing (create or update)
    $('#saveBilling').click(function() {
        const formData = $('#billingForm').serialize();
        const billingId = $('#billing_id').val();
        const url = billingId ? `/billings/${billingId}` : '/billings';
        const method = billingId ? 'PUT' : 'POST';
        const prev = parseFloat($('#previousReading').val()) || 0;
        const current = parseFloat($('#currentReading').val()) || 0;
        
        if (current <= prev) {
            showErrorAlert('Validation Error', 'Current reading must be greater than previous reading');
            return;
        }

        $.ajax({
            url: url,
            type: method,
            data: formData,
            beforeSend: function() {
                $('#saveBilling').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
                );
            },
            complete: function() {
                $('#saveBilling').prop('disabled', false).html(billingId ? 'Update Reading' : 'Save Reading');
            },
            success: function(response) {
                showSuccessAlert('Success!', response.message);
                $('#addBillingModal').modal('hide');
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                if (xhr.status === 419) { // CSRF token mismatch
                    showErrorAlert('Session Expired', 'Your session has expired. Please refresh the page and try again.');
                } else if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = Object.values(errors).flat();
                    showErrorAlert('Validation Error', errorMessages.join('<br>'));
                } else {
                    showErrorAlert('Error!', xhr.responseJSON?.message || 'An error occurred while saving the reading');
                }
            }
        });
    });

    // Edit billing record
    $(document).on('click', '.edit-btn', function() {
        const billingId = $(this).data('id');
        
        // Show loading state
        $('#consumerDropdown').hide();
        $('#consumerDisplayContainer').show().html(`
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        
        $.get(`/billings/${billingId}/edit`, function(response) {
            const billing = response.billing;
            const consumer = response.consumers.find(c => c.id === billing.consumer_id);
            
            // Update the hidden consumer_id field
            $('#consumer_id').val(billing.consumer_id);
            
            // Show the consumer display instead of dropdown
            $('#consumerDropdown').hide();
            $('#consumerDisplayContainer').show().html(`
                <input type="text" class="form-control" id="consumerDisplay" 
                    value="${formatConsumerName(consumer)}"
                       readonly>
            `);
            
            // Fill other form fields
            $('#billing_id').val(billing.id);
            $('#consumerType').val(billing.consumer_type || consumer.consumer_type);
            $('#meterNumber').val(billing.meter_no || consumer.meter_no);
            $('#previousReading').val(parseFloat(billing.previous_reading).toFixed(2));
            $('#currentReading').val(parseFloat(billing.current_reading).toFixed(2));
            $('#consumption').val(parseFloat(billing.consumption).toFixed(2));
            $('#readingDate').val(billing.reading_date.split('T')[0]);
            
            // Change UI text
            $('#addBillingModalLabel').text('Edit Water Reading');
            $('#saveBilling').text('Update Reading');
            
            $('#addBillingModal').modal('show');
            
        }).fail(function(xhr) {
            // Restore dropdown on error
            $('#consumerDropdown').show();
            $('#consumerDisplayContainer').hide();
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load billing data for editing'
            });
        });
    });
    

    // Delete billing record
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
                    url: `/billings/${billingId}`,
                    type: 'DELETE',
                    success: function(response) {
                        showSuccessAlert('Deleted!', response.message);
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) { // CSRF token mismatch
                            showErrorAlert('Session Expired', 'Your session has expired. Please refresh the page and try again.');
                        } else {
                            showErrorAlert('Error', xhr.responseJSON?.message || 'Failed to delete billing record');
                        }
                    }
                });
            }
        });
    });

    // Helper functions for notifications
    function showSuccessAlert(title, message) {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 3000
        });
    }

    function showErrorAlert(title, message) {
        Swal.fire({
            icon: 'error',
            title: title,
            html: message
        });
    }

    function showErrorToast(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }
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
});
</script>
</body>
</html>

