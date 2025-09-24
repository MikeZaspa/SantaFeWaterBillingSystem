<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Santa Fe Water Billing System - Accountants</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
           --primary-color: #0d6efd;
            --primary-light: #6a59ffff;
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
            background:  #f8f9fa;
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
            font-weight: 500;
            position: relative;
        }
        
        .sidebar-menu .nav-link.active::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 2px;
        }
        
        .sidebar-menu .nav-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Add Accountant Button Styles */
        #addAccountantBtn {
            background-color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 5px rgba(211, 47, 47, 0.2);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        #addAccountantBtn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(211, 47, 47, 0.3);
        }

        #addAccountantBtn:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(211, 47, 47, 0.2);
        }

        #addAccountantBtn i {
            font-size: 1.1rem;
            margin-right: 8px;
        }
                
        /* Enhanced Table Styles */
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

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Enhanced Badges */
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

        .badge i {
            font-size: 0.65rem;
        }

        .badge-status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .badge-status-inactive {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .badge-status-busy {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Action Buttons */
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

        /* Search Box */
        .search-box {
            min-width: 250px;
        }

        .search-box .form-control {
            border-right: 0;
        }

        .search-box .btn {
            border-left: 0;
            background-color: white;
        }

        .search-box .btn:hover {
            background-color: #f8f9fa;
        }

        /* Pagination */
        .dataTables_paginate .paginate_button {
            padding: 6px 12px;
            border-radius: 6px;
            margin: 0 2px;
            border: 1px solid transparent;
        }

        .dataTables_paginate .paginate_button.current {
            background: var(--primary-color);
            color: white !important;
            border-color: var(--primary-color);
        }

        .dataTables_paginate .paginate_button:hover {
            background: rgba(211, 47, 47, 0.1);
            color: var(--primary-color) !important;
            border-color: rgba(211, 47, 47, 0.2);
        }

        /* Info Text */
        .dataTables_info {
            padding-top: 12px !important;
            color: #6c757d !important;
            font-size: 0.875rem;
        }
        
        /* Modal Styles */
        .modal-header {
            background-color: var(--primary-color);
            color: white;
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
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        .login-logo {
            width: 100px;       
            height: 100px;      
            border-radius: 50%; 
            object-fit: cover;  
        }

        /* Better table alignment */
        .table td, .table th {
            vertical-align: middle;
        }

        /* Ensure table cells don't wrap unnecessarily */
        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        
        /* Password toggle */
        .password-toggle {
            cursor: pointer;
        }
        
        /* Password confirmation styling */
        .password-confirm-group {
            margin-top: 10px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header text-center">
        <img src="{{ asset('image/santafe.png') }}" class="login-logo img-fluid mb-3">
        <h1 class="h5">Santa Fe Water Billing</h1>
    </div>
    <nav class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="admin-dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-consumer">
                    <i class="bi bi-people"></i> Manage Consumers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-plumber">
                    <i class="bi bi-wrench"></i> Manage Plumber
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="admin-accountant">
                    <i class="bi bi-cash-stack"></i> Manage Accountant
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
                <h3 class="mb-0">Accountant Management</h3>
                <button class="btn btn-primary" id="addAccountantBtn" data-bs-toggle="modal" data-bs-target="#accountantModal">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Add New Accountant
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="accountantsTable">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Middle Name</th>
                        <th>Suffix</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th width="120">Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accountants as $accountant)
                    <tr id="accountantRow_{{ $accountant->id }}">
                        <td class="fw-semibold">{{ $accountant->id }}</td>
                        <td>{{ $accountant->username }}</td>
                        <td>{{ $accountant->first_name }}</td>
                        <td>{{ $accountant->last_name }}</td>
                        <td>{{ $accountant->middle_name }}</td>
                        <td>{{ $accountant->suffix }}</td>
                        <td>{{ $accountant->contact_number }}</td>
                        <td>{{ $accountant->address }}</td>  
                        <td>
                            <span class="badge 
                                @if($accountant->status == 'active') badge-status-active
                                @elseif($accountant->status == 'inactive') badge-status-inactive
                                @else badge-status-busy @endif">
                                <i class="bi 
                                    @if($accountant->status == 'active') bi-check-circle
                                    @elseif($accountant->status == 'inactive') bi-pause-circle
                                    @else bi-hourglass @endif"></i>
                                {{ ucfirst($accountant->status) }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <button class="btn btn-action btn-warning edit-accountant" data-id="{{ $accountant->id }}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-action btn-danger delete-accountant" data-id="{{ $accountant->id }}" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Accountant Modal (Add/Edit) -->
<div class="modal fade" id="accountantModal" tabindex="-1" aria-labelledby="accountantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Accountant</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="accountantForm">
                    <input type="hidden" id="accountantId">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label required">Username</label>
                                <input type="text" class="form-control" id="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label" id="passwordLabel">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password">
                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text" id="passwordHelp">Leave blank to keep current password</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Confirmation Field (only shown when adding new accountant) -->
                    <div class="row password-confirm-group" id="passwordConfirmGroup">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    <button class="btn btn-outline-secondary password-toggle-confirm" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="firstName" class="form-label required">First Name</label>
                        <input type="text" class="form-control" id="firstName" required>
                    </div>
                    <div class="mb-3">
                        <label for="middleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middleName">
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label required">Last Name</label>
                        <input type="text" class="form-control" id="lastName" required>
                    </div>
                    <div class="mb-3">
                        <label for="suffix" class="form-label">Suffix</label>
                        <input type="text" class="form-control" id="suffix" placeholder="e.g., Jr., Sr., III">
                    </div>

                    <div class="mb-3">
                        <label for="contactNumber" class="form-label required">Contact Number</label>
                        <input type="tel" class="form-control" id="contactNumber" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label required">Address</label>
                        <input type="text" class="form-control" id="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label required">Status</label>
                        <select class="form-select" id="status" required>
                            <option value="" selected disabled>Select status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAccountant">Save Accountant</button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this accountant? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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
        
        // Password toggle functionality
        $('.password-toggle').click(function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
        
        // Password confirmation toggle functionality
        $('.password-toggle-confirm').click(function() {
            const passwordInput = $('#password_confirmation');
            const icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });

        // Initialize DataTable
        $('#accountantsTable').DataTable({
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            language: {
                search: "",
                searchPlaceholder: "Search accountants...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                emptyTable: "<div class='text-center'>No data available in table</div>",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            initComplete: function() {
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-select');
            }
        });

        // Reset form when modal is closed
        $('#accountantModal').on('hidden.bs.modal', function() {
            $('#accountantForm')[0].reset();
            $('#accountantId').val('');
            $('#modalTitle').text('Add New Accountant');
            $('#passwordLabel').text('Password');
            $('#passwordHelp').text('Leave blank to keep current password');
            $('#password').attr('placeholder', '');
            $('#passwordConfirmGroup').show();
        });

        // Add Accountant button click
        $('#addAccountantBtn').click(function() {
            $('#modalTitle').text('Add New Accountant');
            $('#accountantId').val('');
            $('#passwordLabel').text('Password');
            $('#passwordHelp').text('');
            $('#password').attr('placeholder', 'Enter password').prop('required', true);
            $('#passwordConfirmGroup').show();
        });

        // Save Accountant (Add/Edit)
        $('#saveAccountant').click(function() {
            // Get all form values
            const formData = {
                username: $('#username').val().trim(),
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
                first_name: $('#firstName').val().trim(),
                middle_name: $('#middleName').val().trim(),
                last_name: $('#lastName').val().trim(),
                suffix: $('#suffix').val().trim(),
                contact_number: $('#contactNumber').val().trim(),
                address: $('#address').val().trim(),
                status: $('#status').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            // Basic validation
            if (!formData.username || !formData.first_name || !formData.last_name || 
                !formData.contact_number || !formData.address || !formData.status) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill all required fields'
                });
                return;
            }

            // For new accountant, password is required
            const accountantId = $('#accountantId').val();
            if (!accountantId && !formData.password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Password is required for new accountants'
                });
                return;
            }
            
            // For new accountant, password confirmation is required
            if (!accountantId && formData.password !== formData.password_confirmation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Password confirmation does not match'
                });
                return;
            }

            // If editing and password is empty, remove it from form data
            if (accountantId && !formData.password) {
                delete formData.password;
                delete formData.password_confirmation;
            }

            // Username validation (alphanumeric and underscores)
            const usernameRegex = /^[a-zA-Z0-9_]+$/;
            if (!usernameRegex.test(formData.username)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Username can only contain letters, numbers, and underscores'
                });
                return;
            }

            // Phone number validation
            const phoneRegex = /^09\d{9}$/;
            if (!phoneRegex.test(formData.contact_number)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter a valid phone number (09XXXXXXXXX)'
                });
                return;
            }

            const url = accountantId ? `/admin-accountant/${accountantId}` : '/admin-accountant';
            const method = accountantId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function(response) {
                    $('#accountantModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'Something went wrong!';
                    if (xhr.responseJSON?.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        });

        // Edit Accountant
        $(document).on('click', '.edit-accountant', function() {
            const accountantId = $(this).data('id');
            
            $.ajax({
                url: `/admin-accountant/${accountantId}/edit`,
                type: 'GET',
                success: function(response) {
                    $('#modalTitle').text('Edit Accountant');
                    $('#accountantId').val(response.id);
                    $('#username').val(response.username);
                    $('#firstName').val(response.first_name);
                    $('#middleName').val(response.middle_name);
                    $('#lastName').val(response.last_name);
                    $('#suffix').val(response.suffix);
                    $('#contactNumber').val(response.contact_number);
                    $('#address').val(response.address);
                    $('#status').val(response.status);
                    
                    // Update password field for editing
                    $('#passwordLabel').text('Password');
                    $('#passwordHelp').text('');
                    $('#password').attr('placeholder', 'Enter new password to change').prop('required', false);
                    $('#passwordConfirmGroup').hide();
                    
                    $('#accountantModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Failed to fetch accountant data'
                    });
                }
            });
        });

        // Delete Accountant
        let deleteAccountantId = null;

        $(document).on('click', '.delete-accountant', function() {
            deleteAccountantId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        $('#confirmDelete').click(function() {
            if (!deleteAccountantId) return;
            
            $.ajax({
                url: `/admin-accountant/${deleteAccountantId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#accountantRow_' + deleteAccountantId).remove();
                        deleteAccountantId = null;
                    });
                },
                error: function(xhr) {
                    $('#deleteModal').modal('hide');
                    let errorMessage = xhr.responseJSON?.message || 'Failed to delete accountant';
                    
                    if (xhr.responseJSON?.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        });

        // Toggle sidebar on mobile
        $('#sidebarToggle').click(function() {
            $('.sidebar').toggleClass('active');
            $('.main-content').toggleClass('active');
        });
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