<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santa Fe Water Billing - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #d32f2f;
            --primary-light: #ff6659;
            --primary-dark: #9a0007;
            --sidebar-bg: #f8f9fa;
            --sidebar-text: rgba(0,0,0,0.8);
            --sidebar-hover: rgba(0,0,255,0.1);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
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
            border-bottom: 1px solid rgba(0,0,0,0.1);
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
            width: calc(100% - 280px);
        }
        
        .header {
            height: 70px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 100;
            background: white;
            padding: 0 15px;
        }
        
        .metric-card {
            border-radius: 10px;
            transition: all 0.3s;
            border-left: 4px solid;
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .metric-card.completed {
            border-left-color: #28A745;
        }
        
        .metric-card.pending {
            border-left-color: #FFC107;
        }
        
        .metric-card.overdue {
            border-left-color: #DC3545;
        }
        
        .metric-card.total {
            border-left-color: #17A2B8;
        }
        
        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .metric-icon.completed {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28A745;
        }
        
        .metric-icon.pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #FFC107;
        }
        
        .metric-icon.overdue {
            background-color: rgba(220, 53, 69, 0.1);
            color: #DC3545;
        }
        
        .metric-icon.total {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17A2B8;
        }
        
        .login-logo {
            width: 100px;       
            height: 100px;      
            border-radius: 50%; 
            object-fit: cover;  
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card h3 {
            font-weight: 700;
            color: #2c3e50;
        }

        .card h6 {
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }
        
        /* Status colors */
        .text-completed { color: #28a745; }
        .text-pending { color: #ffc107; }
        .text-overdue { color: #dc3545; }
        .text-total { color: #17a2b8; }
        
        .bg-completed { background-color: rgba(40, 167, 69, 0.1); }
        .bg-pending { background-color: rgba(255, 193, 7, 0.1); }
        .bg-overdue { background-color: rgba(220, 53, 69, 0.1); }
        .bg-total { background-color: rgba(23, 162, 184, 0.1); }
        
        /* Mobile overlay styles */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
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
        
        /* Responsive styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .main-content.active {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
            
            .login-logo {
                width: 70px;
                height: 70px;
            }
            
            .sidebar-menu .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 768px) {
            /* Make metric cards stack vertically */
            .row.g-4.mb-4 {
                flex-direction: column;
            }
            
            .row.g-4.mb-4 > div {
                width: 100%;
                margin-bottom: 15px;
            }
            
            /* Make charts stack vertically */
            .row.g-4 {
                flex-direction: column;
            }
            
            .row.g-4 > div {
                width: 100%;
                margin-bottom: 15px;
            }
            
            /* Adjust chart container height */
            .chart-container {
                height: 250px;
            }
            
            /* Header title adjustments */
            .header h2 {
                font-size: 1rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 150px;
            }
            
            /* Adjust dropdown menu */
            .dropdown-menu {
                position: absolute;
                right: 0;
                left: auto;
            }
            
            /* Adjust chart tooltips for mobile */
            .chartjs-tooltip {
                transform: scale(0.8);
                transform-origin: center center;
            }
        }
        
        @media (max-width: 576px) {
            .mobile-header-title {
                font-size: 0.9rem;
                max-width: 120px;
            }
            
            .position-relative.me-3 {
                display: none !important;
            }
            
            .header {
                padding: 0 10px;
            }
            
            .dropdown-toggle span {
                display: none;
            }
            
            .card-body {
                padding: 1rem;
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
                <a class="nav-link active" href="admin-plumber-dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-plumber-consumer">
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
    
    <!-- Dashboard Content -->
    <div class="container-fluid p-3 p-md-4">
        <div class="row g-4 mb-4">
            <!-- Completed Readings Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Completed Readings</h6>
                                <h3>{{ $completedCount }}</h3>
                                <small class="text-success">
                                    <i class="bi bi-check-circle"></i> Readings with both current and previous values
                                </small>
                            </div>
                            <div class="bg-completed p-3 rounded">
                                <i class="bi bi-check-circle-fill text-completed fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Readings Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Readings</h6>
                                <h3>{{ $totalCount }}</h3>
                                <small class="text-info">
                                    <i class="bi bi-list-check"></i> All meter readings
                                </small>
                            </div>
                            <div class="bg-total p-3 rounded">
                                <i class="bi bi-list-check text-total fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Completed Readings by Month</h5>
                        <div class="chart-container">
                            <canvas id="completedReadingsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Consumption Trend (m³)</h5>
                        <div class="chart-container">
                            <canvas id="consumptionTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileOverlay = document.querySelector('.mobile-overlay');
        
        // Toggle sidebar on mobile
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            
            // Prevent scrolling when sidebar is open
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        
        // Close sidebar when clicking on overlay
        mobileOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            mainContent.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Close sidebar when clicking on a nav link (for mobile)
        document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('active');
                    mainContent.classList.remove('active');
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
        
        // Initialize charts
        const consumptionCtx = document.getElementById('consumptionTrendChart').getContext('2d');
        const consumptionChart = new Chart(consumptionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Water Consumption (m³)',
                    data: @json($consumptionData),
                    backgroundColor: 'rgba(23, 162, 184, 0.2)',
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw} m³`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cubic Meters (m³)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });

        const completedCtx = document.getElementById('completedReadingsChart').getContext('2d');
        const completedChart = new Chart(completedCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Completed Readings',
                    data: @json($completedData),
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Readings'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            // Close sidebar if window is resized to desktop size
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('active');
                mainContent.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Update charts on resize
            consumptionChart.resize();
            completedChart.resize();
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
            window.location.href = '/login';
        },
        error: function(xhr) {
            // If AJAX fails, still redirect to login
            window.location.href = '/login';
        }
    });
    
    // Alternative: Simple redirect (if no server-side logout needed)
    // window.location.href = '/login';
}
</script>

</body>
</html>