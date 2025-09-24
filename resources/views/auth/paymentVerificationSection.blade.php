<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Verification</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #d32f2f;
      --primary-light: #ff6659;
      --primary-dark: #9a0007;
      --sidebar-bg: linear-gradient(180deg, #d32f2f 0%, #9a0007 100%);
      --sidebar-text: rgba(255,255,255,0.9);
      --sidebar-hover: rgba(255,255,255,0.1);
      --primary: #4361ee;
      --secondary: #3f37c9;
      --success: #4cc9f0;
      --danger: #f72585;
      --warning: #f8961e;
      --light-bg: #f5f7fb;
      --gray: #6c757d;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      color: #333;
    }
    
    /* Sidebar Styles */
    .sidebar {
      width: 280px;
      background: #f8f9fa;
      position: fixed;
      height: 100%;
      overflow-y: auto;
      transition: all 0.3s;
      z-index: 1000;
      box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-header {
      padding: 1.5rem;
      color: black;
      border-bottom: 1px solid rgba(0,0,0,0.1);
      text-align: center;
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
    
    .login-logo {
      width: 100px;       
      height: 100px;      
      border-radius: 50%; 
      object-fit: cover;  
      margin-bottom: 15px;
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
      align-items: center;
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

    /* Payment Verification Styles */
    .table-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      padding: 25px;
      margin: 30px auto;
      max-width: 1400px;
    }

    .table-title {
      padding-bottom: 20px;
      margin-bottom: 25px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .table-title h3 {
      color: var(--primary);
      font-weight: 700;
      font-size: 1.5rem;
    }

    .form-control, .form-select {
      border-radius: 8px;
      padding: 10px 14px;
      border: 1px solid #ddd;
      box-shadow: none;
      font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }

    table thead th {
      background-color: var(--primary);
      color: white;
      font-weight: 600;
      padding: 14px 10px;
      border: none;
    }

    table tbody tr {
      transition: all 0.2s ease;
    }

    table tbody tr:hover {
      background-color: rgba(67, 97, 238, 0.05);
    }

    table tbody td {
      padding: 14px 10px;
      vertical-align: middle;
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 50px;
      font-size: 0.82rem;
      font-weight: 500;
    }

    .status-pending {
      background-color: rgba(248, 150, 30, 0.15);
      color: var(--warning);
    }

    .status-verified {
      background-color: rgba(76, 201, 240, 0.15);
      color: var(--success);
    }

    .status-rejected {
      background-color: rgba(247, 37, 133, 0.15);
      color: var(--danger);
    }

    .modal-content {
      border-radius: 12px;
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
      padding: 15px 25px;
      background-color: var(--primary);
      color: white;
    }

    .modal-body {
      padding: 25px;
    }

    .modal-footer {
      border-bottom-left-radius: 12px;
      border-bottom-right-radius: 12px;
      padding: 15px 25px;
    }

    .payment-details strong {
      min-width: 120px;
      color: var(--gray);
      display: inline-block;
    }

    #verifyProofImage {
      border-radius: 8px;
      border: 1px solid #eee;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      max-height: 300px;
      object-fit: contain;
    }

    .image-placeholder {
      height: 300px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8f9fa;
      border-radius: 8px;
      border: 2px dashed #dee2e6;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
      border-radius: 8px !important;
      padding: 6px 12px;
      margin: 0 3px;
      border: 1px solid #ddd;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: var(--primary);
      color: white !important;
      border: 1px solid var(--primary);
    }

    @media (max-width: 768px) {
      .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start;
      }

      .d-flex.justify-content-between .d-flex {
        margin-top: 15px;
        width: 100%;
      }

      #paymentSearch {
        margin-bottom: 10px;
      }
      
      .modal-dialog {
        margin: 1rem;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="sidebar-header text-center">
    <img src="{{ asset('image/santafe.png') }}" class="login-logo img-fluid">
    <h1 class="h5">Santa Fe Water Billing</h1>
  </div>
  
  <nav class="sidebar-menu">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="admin-accountant-dashboard">
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
        <a class="nav-link active" href="paymentVerificationSection">
          <i class="bi bi-credit-card"></i> Payment Verification
        </a>
      </li>
    </ul>
  </nav>
</div>

<!-- Main Content -->
<div class="main-content">
  <!-- Header -->
  <header class="header bg-white d-flex align-items-center px-3">
    <button id="sidebarToggle" class="btn d-lg-none me-3">
      <i class="bi bi-list"></i>
    </button>
    <h2 class="h5 mb-0">Payment Verification</h2>
    
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

  <!-- Payment Verification Content -->
  <div class="table-container animate-fadein" id="paymentVerificationSection">
    <div class="table-title">
      <div class="d-flex justify-content-between align-items-center w-100">
        <h3 class="mb-0"><i class="bi bi-credit-card"></i> Payment Verification</h3>
        <div class="d-flex">
          <input type="text" class="form-control me-2" id="paymentSearch" placeholder="Search payments...">
          <select class="form-select" id="paymentStatusFilter">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="verified">Verified</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover" id="paymentsTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Consumer</th>
            <th>Meter No.</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Reference No.</th>
            <th>Submitted</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- AJAX Data -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentVerificationModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-search"></i> Verify Payment</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <h6 class="text-primary fw-bold">Payment Details</h6>
            <div class="bg-light p-3 rounded">
              <p><strong>Consumer:</strong> <span id="verifyConsumer"></span></p>
              <p><strong>Meter No:</strong> <span id="verifyMeterNo"></span></p>
              <p><strong>Amount:</strong> ₱<span id="verifyAmount"></span></p>
              <p><strong>Method:</strong> <span id="verifyMethod"></span></p>
              <p><strong>Reference No:</strong> <span id="verifyReference"></span></p>
              <p><strong>Submitted:</strong> <span id="verifySubmitted"></span></p>
            </div>
          </div>
          <div class="col-md-6">
            <h6 class="text-primary fw-bold">Proof of Payment</h6>
            <div id="imageContainer">
              <img id="verifyProofImage" class="img-fluid rounded shadow-sm" alt="Proof of Payment">
            </div>
          </div>
        </div>
        <div class="mt-3">
          <label for="adminNotes" class="form-label fw-bold">Admin Notes</label>
          <textarea class="form-control" id="adminNotes" rows="3" placeholder="Add notes..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" id="rejectPaymentBtn"><i class="bi bi-x-circle"></i> Reject</button>
        <button class="btn btn-success" id="approvePaymentBtn"><i class="bi bi-check-circle"></i> Approve</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
  // Payment verification functionality
$(document).ready(function() {
    // Initialize payments table
    const paymentsTable = $('#paymentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/admin/payments/datatable",
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.log('DataTables error:', xhr.responseJSON);
                alert('Error loading data: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { 
                data: 'admin_consumer', 
                name: 'adminConsumer.first_name',
                render: function(data, type, row) {
                    // Handle both object and string formats
                    if (typeof data === 'object' && data !== null) {
                        return (data.first_name || '') + ' ' + (data.last_name || '');
                    }
                    return 'N/A';
                }
            },
            { 
                data: 'meter_no', 
                name: 'adminConsumer.meter_no',
                render: function(data, type, row) {
                    // Check if we have adminConsumer data in the row
                    if (row.admin_consumer && typeof row.admin_consumer === 'object') {
                        return row.admin_consumer.meter_no || 'N/A';
                    }
                    return data || 'N/A';
                }
            },
            { 
                data: 'amount', 
                name: 'amount',
                render: function(data) {
                    return '₱' + parseFloat(data).toFixed(2);
                }
            },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'reference_number', name: 'reference_number' },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data) {
                    return data ? moment(data).format('MMM D, YYYY h:mm A') : '';
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    let badgeClass = 'status-pending';
                    let statusText = 'Pending';
                    
                    if (data === 'verified') {
                        badgeClass = 'status-verified';
                        statusText = 'Verified';
                    } 
                    else if (data === 'rejected') {
                        badgeClass = 'status-rejected';
                        statusText = 'Rejected';
                    }
                    
                    return `<span class="status-badge ${badgeClass}">${statusText}</span>`;
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-info view-payment-btn" data-id="${data}">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </div>
                    `;
                }
            }
        ]
    });

    // Apply filters
    $('#paymentSearch').on('keyup', function() {
        paymentsTable.search(this.value).draw();
    });
    
    $('#paymentStatusFilter').change(function() {
        const status = $(this).val();
        paymentsTable.column(7).search(status).draw(); // Updated column index
    });

    // View payment details
    $(document).on('click', '.view-payment-btn', function() {
        const paymentId = $(this).data('id');
        
        // Show loading
        $('#paymentVerificationModal').modal('show');
        $('#verifyConsumer').text('Loading...');
        $('#verifyMeterNo').text('Loading...');
        
        // Show loading placeholder for image
        $('#imageContainer').html(`
            <div class="image-placeholder">
                <div class="text-center text-muted">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                    <p class="mt-2">Loading image...</p>
                </div>
            </div>
        `);
        
        // Fetch payment details
        $.ajax({
            url: `/admin/payments/${paymentId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const payment = response.data;
                    console.log("Payment details:", payment); // For debugging
                    
                    // Extract consumer information from multiple possible sources
                    let consumerName = 'N/A';
                    let meterNo = 'N/A';
                    
                    // Try to get consumer data from different possible locations
                    if (payment.admin_consumer) {
                        // If admin_consumer is available
                        consumerName = (payment.admin_consumer.first_name || '') + ' ' + (payment.admin_consumer.last_name || '');
                        meterNo = payment.admin_consumer.meter_no || 'N/A';
                    } else if (payment.bill && payment.bill.consumer) {
                        // If bill.consumer is available
                        consumerName = (payment.bill.consumer.first_name || '') + ' ' + (payment.bill.consumer.last_name || '');
                        meterNo = payment.bill.consumer.meter_no || 'N/A';
                    } else if (payment.consumer) {
                        // If consumer is directly available
                        consumerName = (payment.consumer.first_name || '') + ' ' + (payment.consumer.last_name || '');
                        meterNo = payment.consumer.meter_no || 'N/A';
                    }
                    
                    // Fill payment details
                    $('#verifyConsumer').text(consumerName.trim() || 'N/A');
                    $('#verifyMeterNo').text(meterNo);
                    $('#verifyAmount').text(parseFloat(payment.amount || 0).toFixed(2));
                    $('#verifyMethod').text(payment.payment_method || 'N/A');
                    $('#verifyReference').text(payment.reference_number || 'N/A');
                    $('#verifySubmitted').text(
                        payment.created_at ? 
                        moment(payment.created_at).format('MMM D, YYYY h:mm A') : 
                        'N/A'
                    );
                    
                    // Set proof image
                    if (payment.proof_image) {
                        // Create image element with error handling
                        const img = document.createElement('img');
                        img.id = 'verifyProofImage';
                        img.className = 'img-fluid rounded shadow-sm';
                        img.alt = 'Proof of Payment';
                        img.style.maxHeight = '300px';
                        img.style.objectFit = 'contain';
                        
                        img.onload = function() {
                            $('#imageContainer').html(img);
                        };
                        
                        img.onerror = function() {
                            $('#imageContainer').html(`
                                <div class="image-placeholder">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-x-circle" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Failed to load image</p>
                                        <small>Path: ${payment.proof_image}</small>
                                    </div>
                                </div>
                            `);
                        };
                        
                        // Set the image source - ensure correct path
                        const imagePath = `/storage/${payment.proof_image}`;
                        console.log("Loading image from:", imagePath);
                        img.src = imagePath;
                    } else {
                        $('#imageContainer').html(`
                            <div class="image-placeholder">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                    <p class="mt-2">No proof image available</p>
                                </div>
                            </div>
                        `);
                    }
                    
                    // Set admin notes if exists
                    $('#adminNotes').val(payment.admin_notes || '');
                    
                    // Enable/disable buttons based on status
                    if (payment.status !== 'pending') {
                        $('#approvePaymentBtn, #rejectPaymentBtn').prop('disabled', true);
                    } else {
                        $('#approvePaymentBtn, #rejectPaymentBtn').prop('disabled', false);
                    }
                    
                    // Store payment ID for verification
                    $('#approvePaymentBtn, #rejectPaymentBtn').data('id', paymentId);
                }
            },
            error: function(xhr) {
                console.error("Error loading payment details:", xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load payment details.'
                });
                $('#paymentVerificationModal').modal('hide');
            }
        });
    });

    // Approve payment
    $('#approvePaymentBtn').click(function() {
        const paymentId = $(this).data('id');
        const notes = $('#adminNotes').val();
        
        verifyPayment(paymentId, 'verified', notes);
    });

    // Reject payment
    $('#rejectPaymentBtn').click(function() {
        const paymentId = $(this).data('id');
        const notes = $('#adminNotes').val();
        
        verifyPayment(paymentId, 'rejected', notes);
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
    // Verify payment function
    function verifyPayment(paymentId, status, notes) {
        $.ajax({
            url: `/admin/payments/${paymentId}/verify`,
            type: 'POST',
            data: {
                status: status,
                admin_notes: notes,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#approvePaymentBtn, #rejectPaymentBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');
            },
            success: function(response) {
                if (response.success) {
                    $('#paymentVerificationModal').modal('hide');
                    paymentsTable.ajax.reload();
                    
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
                    $('#approvePaymentBtn, #rejectPaymentBtn').prop('disabled', false).html(status === 'verified' ? '<i class="bi bi-check-circle"></i> Approve' : '<i class="bi bi-x-circle"></i> Reject');
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to process payment.'
                });
                $('#approvePaymentBtn, #rejectPaymentBtn').prop('disabled', false).html(status === 'verified' ? '<i class="bi bi-check-circle"></i> Approve' : '<i class="bi bi-x-circle"></i> Reject');
            }
        });
    }
});
</script>
</body>
</html>