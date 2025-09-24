<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Santa Fe Water Billing System - Account Management</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        /* Search and Add Button */
        .search-add-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-box {
            position: relative;
            min-width: 250px;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .search-box input {
            padding-left: 40px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            height: 40px;
        }
        
        .btn-add {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-add:hover {
            background-color: var(--primary-dark);
            color: white;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
        
        .dropdown-item.active {
            background-color: #0d6efd;
            color: white;
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
                <a class="nav-link active" href="admin-consumer-form">
                    <i class="bi bi-person-gear"></i> Manage Accounts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="water-rates">
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
    
    <!-- Account Management Content -->
    <div class="container-fluid animate-fadein">
        <div class="table-container">
            
            <div class="search-add-container">
                <div class="table-title">
                <h3>Account Management</h3>
            </div>
                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#accountModal">
                    <i class="bi bi-plus-lg"></i> Add New Account
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="accountsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Consumer</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Account Modal -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="accountModalLabel">Add New Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="accountForm">
                    <input type="hidden" id="accountId">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Consumer</label>
                        <div class="dropdown">
                            <button class="form-select text-start" type="button" id="consumerDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="selectedConsumerText">Select Consumer</span>
                            </button>
                            <input type="hidden" id="consumer_id" name="consumer_id" required>
                            <ul class="dropdown-menu w-100 p-0" aria-labelledby="consumerDropdownButton">
                                <li class="p-2">
                                    <input type="text" class="form-control" id="consumerSearch" placeholder="Search consumers..." autocomplete="off">
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
                    <div class="mb-3">
                        <label class="form-label fw-bold">Account Number</label>
                        <input type="text" class="form-control" id="username" name="username" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAccount">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this account? This action cannot be undone.</p>
                <input type="hidden" id="deleteAccountId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>


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
    // Fix the DataTable configuration
    const table = $('#accountsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/account-management',
            type: 'GET'
        },
        columns: [
            { 
                data: null,
                name: 'serial',
                orderable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { 
                data: 'consumer', 
                name: 'consumer.last_name',
                render: function(data, type, row) {
                    if (!data) return 'N/A';
                    let fullName = data.last_name || '';
                    if (fullName && data.first_name) fullName += ', ';
                    fullName += data.first_name || '';
                    if (data.middle_name) fullName += ' ' + data.middle_name.charAt(0) + '.';
                    if (data.suffix) fullName += ' ' + data.suffix;
                    return fullName.trim();
                }
            },
            { 
                data: 'username', 
                name: 'username'
            },
            { 
                data: null, 
                name: 'password',
                render: function() {
                    return '••••••••';
                }
            },
            { 
                data: 'id', 
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex gap-2">
                            <button class="btn-action btn-edit" data-id="${data}" title="Edit">
                                <i class="bi bi-pencil text-primary"></i>
                            </button>
                            <button class="btn-action btn-delete" data-id="${data}" title="Delete">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        responsive: true,
        order: [[1, 'asc']]
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Initialize event listeners
    $('#accountModal').on('show.bs.modal', function(e) {
        // Only fetch consumers when adding a new account
          if (!$('#accountId').val()) {
        // Add mode: reset fields and fetch consumers
        $('#accountForm')[0].reset();
        $('#accountModalLabel').text('Add New Account');
        $('#selectedConsumerText').text('Select Consumer');
        $('#consumer_id').val('');
        $('#username').val('');
        $('#consumerDropdownButton').prop('disabled', false);
        $('#consumer_id').prop('disabled', false);
        $('#username').prop('readonly', false);
        fetchConsumers();
    }
    });

    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('bi-eye bi-eye-slash');
    });

    $('#saveAccount').click(function() {
    const accountId = $('#accountId').val();
    let formData = {
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    if (accountId) {
        // Edit mode: only send password if filled
        if ($('#password').val()) {
            formData.password = $('#password').val();
        }
    } else {
        // Add mode: send all fields
        formData.consumer_id = $('#consumer_id').val();
        formData.username = $('#username').val();
        formData.password = $('#password').val();
    }

    const url = accountId ? `/account-management/${accountId}` : '/account-management';
    const method = accountId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: method,
        data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                table.ajax.reload();
                $('#accountModal').modal('hide');
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMsg = xhr.statusText;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMsg
                });
            }
        });
    });

    $(document).on('click', '.btn-edit', function() {
    const accountId = $(this).data('id');
    $.get(`/account-management/${accountId}/edit`, function(response) {
        if (response.success) {
            $('#accountModalLabel').text('Edit Account');
            $('#accountId').val(response.data.id);

            // Disable consumer selection and account number in edit mode
            $('#consumerDropdownButton').prop('disabled', true);
            $('#consumer_id').val(response.data.consumer_id).prop('disabled', true);
            $('#username').val(response.data.username).prop('readonly', true);

            // Set consumer name
            let fullName = response.data.consumer.last_name || '';
            if (fullName && response.data.consumer.first_name) fullName += ', ';
            fullName += response.data.consumer.first_name || '';
            if (response.data.consumer.middle_name) fullName += ' ' + response.data.consumer.middle_name.charAt(0) + '.';
            if (response.data.consumer.suffix) fullName += ' ' + response.data.consumer.suffix;
            $('#selectedConsumerText').text(fullName.trim());

            $('#password').val('');
            $('#accountModal').modal('show');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: response.message
            });
        }
    }).fail(function(xhr) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to load account data: ' + (xhr.responseJSON?.message || 'Unknown error')
        });
    });
});

    // Delete account
    $(document).on('click', '.btn-delete', function() {
        const accountId = $(this).data('id');
        $('#deleteAccountId').val(accountId);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        const accountId = $('#deleteAccountId').val();
        $.ajax({
            url: `/account-management/${accountId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                table.ajax.reload();
                $('#deleteModal').modal('hide');
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'An error occurred';
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMsg
                });
            }
        });
    });

    // Function to fetch consumers
    function fetchConsumers() {
        $.ajax({
            url: '/account-management/data',
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#consumerOptions').html(`
                    <div class="text-center p-3">
                        <div class="spinner-border spinner-border-sm"></div>
                        <span>Loading consumers...</span>
                    </div>
                `);
            },
            success: function(response) {
                if (Array.isArray(response)) {
                    populateConsumerOptions(response);
                    setupSearchFunctionality();
                } else if (response.success && Array.isArray(response.data)) {
                    populateConsumerOptions(response.data);
                    setupSearchFunctionality();
                } else {
                    showErrorInDropdown('No consumers found');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Failed to load consumers';
                showErrorInDropdown(errorMsg);
            }
        });
    }

    // Function to populate consumer options
    function populateConsumerOptions(consumers) {
        const optionsContainer = $('#consumerOptions');
        optionsContainer.empty();
        
        if (!consumers || consumers.length === 0) {
            showErrorInDropdown('No consumers available');
            return;
        }
        
        consumers.forEach((consumer) => {
            let fullName = consumer.last_name || '';
            if (fullName && consumer.first_name) fullName += ', ';
            fullName += consumer.first_name || '';
            if (consumer.middle_name) fullName += ' ' + consumer.middle_name.charAt(0) + '.';
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
        let fullName = consumer.last_name || '';
        if (fullName && consumer.first_name) fullName += ', ';
        fullName += consumer.first_name || '';
        if (consumer.middle_name) fullName += ' ' + consumer.middle_name.charAt(0) + '.';
        if (consumer.suffix) fullName += ' ' + consumer.suffix;
        
        $('#selectedConsumerText').text(fullName.trim());
        $('#consumer_id').val(consumer.id);
        $('#username').val(consumer.meter_no || '');
        
        // Close the dropdown
        $('.dropdown-menu').removeClass('show');
    }

    // Function to setup search functionality
    function setupSearchFunctionality() {
        $('#consumerSearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.consumer-option').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm));
            });
        });
    }

    // Helper function to show error in dropdown
    function showErrorInDropdown(message) {
        $('#consumerOptions').html(`
            <div class="text-center p-3 text-danger">
                <i class="bi bi-exclamation-circle"></i>
                <span>${message}</span>
            </div>
        `);
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