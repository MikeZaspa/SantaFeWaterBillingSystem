@extends('layouts.admin') {{-- Adjust based on your layout --}}

@section('content')
<div class="table-container animate-fadein">
    <div class="table-title">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h3 class="mb-0">Online Payment Verification</h3>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover" id="onlinePaymentsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Consumer</th>
                    <th>Billing ID</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference No.</th>
                    <th>Date Submitted</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->billing->consumer->first_name }} {{ $payment->billing->consumer->last_name }}</td>
                    <td>#{{ $payment->billing_id }}</td>
                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                    <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        @if($payment->proof_image)
                        <button class="btn btn-sm btn-info view-proof" data-image="{{ asset('storage/' . $payment->proof_image) }}">
                            <i class="bi bi-eye"></i> View
                        </button>
                        @else
                        No Proof
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $payment->status == 'pending' ? 'warning' : ($payment->status == 'verified' ? 'success' : 'danger') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary verify-payment" data-id="{{ $payment->id }}">
                            <i class="bi bi-check-circle"></i> Verify
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Verification Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">Verify Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="verifyForm">
                <div class="modal-body">
                    <input type="hidden" id="payment_id" name="payment_id">
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="status" required>
                            <option value="verified">Verify Payment</option>
                            <option value="rejected">Reject Payment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="payment_notes" name="notes" rows="3" placeholder="Add notes about this verification"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Verification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Proof Image Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proofModalLabel">Payment Proof</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="proofImage" src="" class="img-fluid" alt="Payment Proof">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#onlinePaymentsTable').DataTable({
        ordering: true,
        searching: true,
        responsive: true
    });
    
    // View proof image
    $('.view-proof').click(function() {
        const imageUrl = $(this).data('image');
        $('#proofImage').attr('src', imageUrl);
        $('#proofModal').modal('show');
    });
    
    // Verify payment
    $('.verify-payment').click(function() {
        const paymentId = $(this).data('id');
        $('#payment_id').val(paymentId);
        $('#verifyModal').modal('show');
    });
    
    // Submit verification form
    $('#verifyForm').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            payment_id: $('#payment_id').val(),
            status: $('#payment_status').val(),
            notes: $('#payment_notes').val(),
            _token: '{{ csrf_token() }}'
        };
        
        $.ajax({
            url: '{{ route("admin.online-payments.verify", "") }}/' + formData.payment_id,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#verifyModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'Payment status updated successfully'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            }
        });
    });
});
</script>
@endsection