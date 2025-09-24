<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Santa Fe Water Billing System - Disconnections</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="icon" type="image/png" href="{{ asset('image/santafe.png') }}">
    <style>
        /* Copy all the CSS styles from your previous HTML file */
        :root {
            --primary-color: #d32f2f;
            --primary-light: #ff6659;
            --primary-dark: #9a0007;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
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
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
        }
        
        .status-disconnected {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-reconnected {
            background-color: #d1edff;
            color: #0c5460;
        }
        
        /* Add all other CSS styles from your previous file */
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
                <a class="nav-link" href="{{ url('admin-plumber-dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin-plumber-consumer') }}">
                    <i class="bi bi-people"></i> Reading
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ url('admin-plumber-disconnection') }}">
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
        <h2 class="h5 mb-0 mobile-header-title">Disconnection Management</h2>
        
        <div class="ms-auto d-flex align-items-center">
            <div class="position-relative me-3 d-none d-sm-block">
                <i class="bi bi-bell fs-5"></i>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-none d-sm-inline">Plumber</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ url('admin-logout') }}">Sign out</a></li>
                </ul>
            </div>
        </div>
    </header>
    
    <div class="container-fluid mt-3 mt-md-4">
        <div class="row">
            <div class="col-12">
                <div class="card animate-fadein">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-2 mb-md-0">Disconnection Records</h5>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" id="refreshBtn">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Desktop Table View -->
                        <div class="table-responsive desktop-table-view">
                            <table class="table table-hover" id="disconnectionTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Consumer</th>
                                        <th>Meter No.</th>
                                        <th>Disconnection Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Disconnected By</th>
                                        <th>Reconnection Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-card-view" id="mobileDisconnectionCards">
                            <!-- Cards will be loaded via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reconnection Modal -->
<div class="modal fade" id="reconnectionModal" tabindex="-1" aria-labelledby="reconnectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reconnectionModalLabel">Reconnect Consumer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reconnect this consumer?</p>
                <div id="reconnectionDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmReconnect">Reconnect</button>
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
    // Mobile sidebar toggle functionality (same as previous)
    const sidebar = $('.sidebar');
    const mainContent = $('.main-content');
    const sidebarToggle = $('#sidebarToggle');
    const mobileOverlay = $('.mobile-overlay');
    
    sidebarToggle.on('click', function() {
        sidebar.toggleClass('active');
        mainContent.toggleClass('active');
        mobileOverlay.toggleClass('active');
        
        if (sidebar.hasClass('active')) {
            $('body').css('overflow', 'hidden');
        } else {
            $('body').css('overflow', '');
        }
    });
    
    mobileOverlay.on('click', function() {
        sidebar.removeClass('active');
        mainContent.removeClass('active');
        mobileOverlay.removeClass('active');
        $('body').css('overflow', '');
    });
    
    $('.sidebar-menu .nav-link').on('click', function() {
        if ($(window).width() < 992) {
            sidebar.removeClass('active');
            mainContent.removeClass('active');
            mobileOverlay.removeClass('active');
            $('body').css('overflow', '');
        }
    });
    
    $(window).on('resize', function() {
        if ($(window).width() >= 992) {
            sidebar.removeClass('active');
            mainContent.removeClass('active');
            mobileOverlay.removeClass('active');
            $('body').css('overflow', '');
        }
    });

   var table = $('#disconnectionTable').DataTable({
        processing: true,
        serverSide: false, // Changed to false for client-side processing
        responsive: true,
        ajax: {
            url: "/disconnections/data",
            type: "GET",
            dataType: "json",
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'consumer',
                render: function(data) {
                    if (!data) return 'N/A';
                    let name = data.first_name || '';
                    if (data.middle_name) name += ' ' + data.middle_name;
                    name += ' ' + (data.last_name || '');
                    if (data.suffix) name += ' ' + data.suffix;
                    return name.trim() || 'N/A';
                }
            },
            { 
                data: 'consumer.meter_no',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'disconnection_date',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('en-US') : 'N/A';
                }
            },
            { 
                data: 'reason',
                render: function(data) {
                    return data || 'Non-payment';
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    const badgeClass = data === 'disconnected' ? 'status-disconnected' : 'status-reconnected';
                    return `<span class="badge status-badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'disconnected_by_user',
                render: function(data) {
                    return data ? data.name : 'N/A';
                }
            },
            { 
                data: 'reconnection_date',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('en-US') : '-';
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (row.status === 'reconnected') {
                        return '<span class="text-muted">Reconnected</span>';
                    }
                    
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-success reconnect-btn" data-id="${data}">
                                <i class="bi bi-plug"></i> Reconnect
                            </button>
                            <button class="btn btn-sm btn-info history-btn" data-consumer="${row.consumer_id}">
                                <i class="bi bi-clock-history"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            lengthMenu: "Show _MENU_ entries",
            search: "Search:",
            info: "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    });

    // Refresh button
    $('#refreshBtn').click(function() {
        table.ajax.reload();
        showToast('Data refreshed successfully', 'success');
    });

    // Reconnect consumer
    $(document).on('click', '.reconnect-btn', function() {
        const disconnectionId = $(this).data('id');
        
        Swal.fire({
            title: 'Reconnect Consumer?',
            text: 'Are you sure you want to reconnect this consumer?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reconnect!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/disconnections/' + disconnectionId + '/reconnect',
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');
                            table.ajax.reload();
                        } else {
                            showToast(response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        showToast('Failed to reconnect consumer', 'error');
                    }
                });
            }
        });
    });

    // View history
    $(document).on('click', '.history-btn', function() {
        const consumerId = $(this).data('consumer');
        // Implement history view logic here
        showToast('History feature coming soon', 'info');
    });

    // Helper function for notifications
    function showToast(message, type = 'info') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    // Set up CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>
</body>
</html>