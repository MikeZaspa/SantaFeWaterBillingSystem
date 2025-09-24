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

        /* Payment Steps */
        .payment-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .payment-steps:before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 8px;
            transition: all 0.3s;
        }
        
        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: #28a745;
            color: white;
        }
        
        .step-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .step-content {
            display: none;
        }
        
        .step-content.active {
            display: block;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Payment Method Selection */
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
        }
        
        .payment-method:hover {
            border-color: var(--primary-light);
            transform: translateY(-5px);
        }
        
        .payment-method.selected {
            border-color: var(--primary-color);
            background-color: rgba(211, 47, 47, 0.05);
        }
        
        .payment-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
        }
        
        .gcash-color {
            background-color: #00a99d;
        }
        
        .maya-color {
            background-color: #6f42c1;
        }
        
        /* QR Code Styles */
        .qr-code-container {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .qr-code {
            display: none;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .qr-code.active {
            display: block;
        }
        
        .qr-code img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        
        /* Proof Upload */
        .proof-preview {
            max-width: 300px;
            max-height: 200px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        
        /* Payment Details */
        .payment-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
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
            
            .payment-steps {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .payment-steps:before {
                display: none;
            }
            
            .step {
                flex-direction: row;
                margin-bottom: 15px;
                width: 100%;
            }
            
            .step-number {
                margin-right: 15px;
                margin-bottom: 0;
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
                <a class="nav-link" href="consumer-information">
                    <i class="bi bi-speedometer2"></i> Consumer Information
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="consumer-login">
                    <i class="bi bi-people"></i> Billing
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
                    <span>Consumer</span>
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
   
    <!-- Billing History -->
    <div class="table-container">
        <div class="table-title">
            <h3>Billing History</h3>
            <div>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="billingTable">
                <thead>
                    <tr>
                        <th>Billing ID</th>
                        <th>Billing Month</th>
                        <th>Due Date</th>
                        <th>Consumption (m³)</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bills as $bill)
                    <tr>
                        <td class="fw-medium">#{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($bill->reading_date)->format('F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}</td>
                        <td>{{ $bill->consumption }} m³</td>
                        <td class="fw-medium">₱{{ number_format($bill->total_amount, 2) }}</td>
                        <td>
                            @if($bill->status === 'paid')
                                <span class="badge badge-paid">
                                    <i class="bi bi-check-circle-fill me-1"></i> Paid
                                </span>
                            @elseif($bill->status === 'unpaid')
                                <span class="badge badge-unpaid">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Unpaid
                                </span>
                            @else
                                <span class="badge badge-overdue">
                                    <i class="bi bi-clock-fill me-1"></i> Overdue
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex">
                                @if($bill->status !== 'paid')
                                    <button class="btn btn-sm btn-success payment-btn me-1"
                                        data-id="{{ $bill->id }}"
                                        data-amount="{{ $bill->total_amount }}"
                                        data-billno="{{ $loop->iteration }}">
                                        <i class="bi bi-credit-card me-1"></i> Pay
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-outline-primary receipt-btn" data-id="{{ $bill->id }}">
                                    <i class="bi bi-receipt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">Process Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Payment Steps -->
                    <div class="payment-steps">
                        <div class="step active" id="step1">
                            <div class="step-number">1</div>
                            <div class="step-label">Select Method</div>
                        </div>
                        <div class="step" id="step2">
                            <div class="step-number">2</div>
                            <div class="step-label">Scan QR Code</div>
                        </div>
                        <div class="step" id="step3">
                            <div class="step-number">3</div>
                            <div class="step-label">Upload Proof</div>
                        </div>
                    </div>
                    
                    <!-- Step 1: Select Payment Method -->
                    <div class="step-content active" id="step1-content">
                        <h6 class="fw-bold mb-3">Select Payment Method</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="payment-method" data-method="gcash">
                                    <div class="text-center">
                                        <div class="payment-icon gcash-color">
                                            <i class="bi bi-phone-fill"></i>
                                        </div>
                                        <h5 class="gcash-color">GCash</h5>
                                        <p class="text-muted">Pay using your GCash account</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="payment-method" data-method="maya">
                                    <div class="text-center">
                                        <div class="payment-icon maya-color">
                                            <i class="bi bi-credit-card-fill"></i>
                                        </div>
                                        <h5 class="maya-color">Maya</h5>
                                        <p class="text-muted">Pay using your Maya account</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-details mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Billing ID:</strong> <span id="paymentBillingId"></span></p>
                                    <p class="mb-1"><strong>Account Name:</strong> {{ $consumer->first_name }} {{ $consumer->last_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Amount Due:</strong> ₱<span id="paymentAmountDue"></span></p>
                                    <p class="mb-0"><strong>Due Date:</strong> <span id="paymentDueDate"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: QR Code -->
                    <div class="step-content" id="step2-content">
                        <h6 class="fw-bold mb-3">Scan QR Code to Pay</h6>
                        
                        <div class="qr-code-container">
                            <!-- GCash QR Code (initially hidden) -->
                            <div class="qr-code" id="gcash-qr">
                                <img src="{{ asset('image/gcash.jfif') }}" alt="GCash QR Code">
                            </div>
                            
                            <!-- Maya QR Code (initially hidden) -->
                            <div class="qr-code" id="maya-qr">
                                <img src="{{ asset('image/maya.jfif') }}" alt="Maya QR Code">
                            </div>
                            
                            <p class="mt-3 mb-0">Scan the QR code using your <span id="paymentAppName"></span> app to complete the payment</p>
                        </div>
                        
                        <div class="payment-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Billing ID:</strong> <span id="qrBillingId"></span></p>
                                    <p class="mb-1"><strong>Account Name:</strong> {{ $consumer->first_name }} {{ $consumer->last_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Amount:</strong> ₱<span id="qrAmount"></span></p>
                                    <p class="mb-0"><strong>Payment Method:</strong> <span id="qrMethod"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Upload Proof -->
                    <div class="step-content" id="step3-content">
                        <h6 class="fw-bold mb-3">Upload Proof of Payment</h6>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i> Please upload a screenshot or photo of your payment confirmation from <span id="uploadMethodName"></span>. Only PNG and JPEG files are accepted.
                        </div>
                        
                        <div class="mb-3">
                            <label for="proofInput" class="form-label">Upload Proof of Payment</label>
                            <input class="form-control" type="file" id="proofInput" accept=".png,.jpg,.jpeg">
                            <div class="form-text" id="fileValidation"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="referenceNumber" class="form-label">Reference Number</label>
                            <input type="text" class="form-control" id="referenceNumber" 
                                placeholder="Enter your transaction reference number" required>
                        </div>
                        
                        <div id="proofPreviewContainer" class="text-center mt-3" style="display: none;">
                            <p>Preview:</p>
                            <img id="proofPreview" class="proof-preview img-fluid rounded">
                        </div>
                        
                        <div class="payment-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Billing ID:</strong> <span id="uploadBillingId"></span></p>
                                    <p class="mb-1"><strong>Account Name:</strong> {{ $consumer->first_name }} {{ $consumer->last_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Amount:</strong> ₱<span id="uploadAmount"></span></p>
                                    <p class="mb-0"><strong>Payment Method:</strong> <span id="uploadMethod"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="prevStepBtn" style="display: none;">Previous</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="nextStepBtn">Next</button>
                    <button type="button" class="btn btn-success" id="submitPaymentBtn" style="display: none;">Submit Payment</button>
                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#billingTable').DataTable({
            ordering: true,
            searching: true,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search bills...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
            }
        });
        
        // Apply status filter
        $('#statusFilter').change(function() {
            const status = $(this).val();
            const table = $('#billingTable').DataTable();
            
            if (status) {
                table.column(5).search(status).draw();
            } else {
                table.column(5).search('').draw();
            }
        });
        
        // Variables to track payment process
        let currentStep = 1;
        let selectedMethod = '';
        let currentBillId = '';
        let currentBillAmount = '';
        let displayBillNo = '';
        
        // Payment button click handler
        $(document).on('click', '.payment-btn', function(e) {
            e.preventDefault();
            currentBillId = $(this).data('id');
            currentBillAmount = $(this).data('amount');
            displayBillNo = $(this).data('billno'); // Get the sequential number

            // Set values in modal
            $('#paymentBillingId').text(displayBillNo); // Use display number
            $('#qrBillingId').text(displayBillNo);
            $('#uploadBillingId').text(displayBillNo);
            $('#paymentAmountDue').text(currentBillAmount.toFixed(2));

            // Get due date from the table row
            const dueDateText = $(this).closest('tr').find('td:eq(2)').text();
            $('#paymentDueDate').text(dueDateText);

            // Reset the modal to step 1
            resetPaymentModal();

            // Show modal
            $('#paymentModal').modal('show');
        });
        
        // Reset payment modal to initial state
        function resetPaymentModal() {
            currentStep = 1;
            selectedMethod = '';
            
            // Reset steps
            $('.step').removeClass('active completed');
            $('#step1').addClass('active');
            
            // Show step 1 content, hide others
            $('.step-content').removeClass('active');
            $('#step1-content').addClass('active');
            
            // Reset payment method selection
            $('.payment-method').removeClass('selected');
            
            // Reset buttons
            $('#prevStepBtn').hide();
            $('#nextStepBtn').show().text('Next').prop('disabled', true);
            $('#submitPaymentBtn').hide();
            
            // Clear proof upload
            $('#proofInput').val('');
            $('#proofPreview').attr('src', '');
            $('#proofPreviewContainer').hide();
            $('#referenceNumber').val('');
            
            // Hide all QR codes
            $('.qr-code').removeClass('active');
        }
        
        // Payment method selection
        $('.payment-method').click(function() {
            $('.payment-method').removeClass('selected');
            $(this).addClass('selected');
            selectedMethod = $(this).data('method');
            
            // Enable next button
            $('#nextStepBtn').prop('disabled', false);
        });
        
        // Next step button
        $('#nextStepBtn').click(function() {
            if (currentStep === 1) {
                // Validate selection
                if (!selectedMethod) {
                    alert('Please select a payment method');
                    return;
                }
                
                // Move to step 2
                goToStep(2);
                
                // Update QR code based on selected method
                if (selectedMethod === 'gcash') {
                    $('#gcash-qr').addClass('active');
                    $('#maya-qr').removeClass('active');
                    $('#paymentAppName').text('GCash');
                    $('#qrMethod').text('GCash');
                } else if (selectedMethod === 'maya') {
                    $('#maya-qr').addClass('active');
                    $('#gcash-qr').removeClass('active');
                    $('#paymentAppName').text('Maya');
                    $('#qrMethod').text('Maya');
                }
                
                // Update billing details in step 2
                $('#qrBillingId').text(displayBillNo);
                $('#qrAmount').text(currentBillAmount.toFixed(2));
                
            } else if (currentStep === 2) {
                // Move to step 3
                goToStep(3);
                
                // Update step 3 with selected method
                const methodName = selectedMethod.charAt(0).toUpperCase() + selectedMethod.slice(1);
                $('#uploadMethodName').text(methodName);
                $('#uploadMethod').text(methodName);
                
                // Update billing details in step 3
                $('#uploadBillingId').text(displayBillNo);
                $('#uploadAmount').text(currentBillAmount.toFixed(2)); 
            }
        });
        
        // Previous step button
        $('#prevStepBtn').click(function() {
            goToStep(currentStep - 1);
        });
        
        // Navigate to specific step
        function goToStep(step) {
            // Update current step
            currentStep = step;
            
            // Update steps UI
            $('.step').removeClass('active completed');
            $(`#step${step}`).addClass('active');
            
            // Mark previous steps as completed
            for (let i = 1; i < step; i++) {
                $(`#step${i}`).addClass('completed');
            }
            
            // Show appropriate content
            $('.step-content').removeClass('active');
            $(`#step${step}-content`).addClass('active');
            
            // Update buttons
            if (step === 1) {
                $('#prevStepBtn').hide();
                $('#nextStepBtn').show().text('Next');
                $('#submitPaymentBtn').hide();
            } else if (step === 2) {
                $('#prevStepBtn').show();
                $('#nextStepBtn').show().text('Next');
                $('#submitPaymentBtn').hide();
            } else if (step === 3) {
                $('#prevStepBtn').show();
                $('#nextStepBtn').hide();
                $('#submitPaymentBtn').show();
            }
        }
        
        // Proof upload functionality with file type validation
        $('#proofInput').on('change', function(e) {
            const file = e.target.files[0];
            const fileValidation = $('#fileValidation');
            
            // Reset validation message
            fileValidation.text('');
            
            if (file) {
                // Check file type
                const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    fileValidation.text('Only PNG and JPEG files are allowed.');
                    $(this).val('');
                    $('#proofPreviewContainer').hide();
                    return;
                }
                
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    fileValidation.text('File size must be less than 5MB.');
                    $(this).val('');
                    $('#proofPreviewContainer').hide();
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#proofPreview').attr('src', event.target.result);
                    $('#proofPreviewContainer').show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#proofPreviewContainer').hide();
            }
        });
        
        // Submit payment button
        $('#submitPaymentBtn').click(function() {
            const billingId = currentBillId;
            const method = selectedMethod;
            const referenceNumber = $('#referenceNumber').val().trim();
            const proofFile = $('#proofInput')[0].files[0];
            
            if (!referenceNumber) {
                alert('Please enter your reference number');
                return;
            }
            
            if (!proofFile) {
                alert('Please upload proof of payment');
                return;
            }
            
            // Create form data
            const formData = new FormData();
            formData.append('bill_id', billingId);
            formData.append('payment_method', method);
            formData.append('reference_number', referenceNumber);
            formData.append('proof_image', proofFile);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            
            // Show loading
            const submitBtn = $('#submitPaymentBtn');
            submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Processing...').prop('disabled', true);
            
            // Submit payment
            $.ajax({
                url: "{{ route('consumer.payment.submit') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Payment submitted successfully
                        $('#paymentModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Submitted',
                            text: 'Your payment has been submitted for verification. You will be notified once it is approved.'
                        }).then(() => {
                            // Reload the page to update billing status
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'An error occurred while processing your payment.'
                        });
                        submitBtn.html('Submit Payment').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Payment submission error:', error, xhr.responseText);
                    
                    let errorMessage = 'An error occurred while submitting your payment.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.statusText) {
                        errorMessage = xhr.statusText;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                    submitBtn.html('Submit Payment').prop('disabled', false);
                }
            });
        });
        
        // Sidebar toggle for mobile
        $('#sidebarToggle').click(function() {
            $('.sidebar').toggleClass('active');
            $('.main-content').toggleClass('active');
        });
    });
    </script>
</body>
</html>