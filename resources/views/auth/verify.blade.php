<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | Santa Fe Water Billing System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #5624d0;
            --primary-dark: #401ea0;
            --text: #2b2d42;
            --text-light: #8d99ae;
            --light: #edf2f4;
            --white: #ffffff;
            --error: #ef233c;
            --success: #06d6a0;
            --border: #e0e0e0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            background: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .verification-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 40px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
        .verification-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .verification-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text);
        }
        .verification-header p {
            font-size: 14px;
            color: var(--text-light);
        }
        .code-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .code-inputs input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background-color: var(--light);
        }
        .code-inputs input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(86, 36, 208, 0.1);
        }
        .btn-verify {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            border: none;
            color: #fff;
            font-weight: 500;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        .btn-verify:hover {
            background: var(--primary-dark);
        }
        .resend-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .resend-link a {
            color: var(--primary);
            text-decoration: none;
            cursor: pointer;
        }
        .countdown {
            color: var(--text-light);
            font-size: 0.9rem;
            text-align: center;
            margin-top: 5px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background-color: rgba(239, 35, 60, 0.1);
            color: var(--error);
            border: 1px solid rgba(239, 35, 60, 0.2);
        }
        .alert-success {
            background-color: rgba(6, 214, 160, 0.1);
            color: var(--success);
            border: 1px solid rgba(6, 214, 160, 0.2);
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="verification-header">
            <h1>Verify Your Email</h1>
            <p>We've sent a verification code to {{ $email }}</p>
        </div>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <form id="verificationForm" method="POST" action="{{ route('verify-code') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="code-inputs">
                <input type="text" name="digit1" maxlength="1" required autofocus>
                <input type="text" name="digit2" maxlength="1" required>
                <input type="text" name="digit3" maxlength="1" required>
                <input type="text" name="digit4" maxlength="1" required>
                <input type="text" name="digit5" maxlength="1" required>
                <input type="text" name="digit6" maxlength="1" required>
            </div>
            
            <button type="submit" class="btn-verify">Verify Email</button>
        </form>
        
        <div class="resend-link">
            <p>Didn't receive the code? <a id="resendCode" href="#" class="hidden">Resend code</a></p>
            <div class="countdown" id="countdown">Resend available in <span id="timeLeft">60</span> seconds</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.code-inputs input');
            const form = document.getElementById('verificationForm');
            const resendLink = document.getElementById('resendCode');
            const countdown = document.getElementById('countdown');
            const timeLeftSpan = document.getElementById('timeLeft');
            
            // Auto-tab between inputs
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });
                
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && index > 0 && !e.target.value) {
                        inputs[index - 1].focus();
                    }
                });
            });
            
            // Countdown timer for resend
            let timeLeft = 60;
            const timer = setInterval(() => {
                timeLeft--;
                timeLeftSpan.textContent = timeLeft;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    countdown.classList.add('hidden');
                    resendLink.classList.remove('hidden');
                }
            }, 1000);
            
            // Resend code functionality
            resendLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show loading state
                const originalText = resendLink.textContent;
                resendLink.textContent = 'Sending...';
                
                // Create a form to submit the resend request
                const formData = new FormData();
                formData.append('email', '{{ $email }}');
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch('{{ route("resend-code") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('A new verification code has been sent to your email.');
                        
                        // Reset countdown
                        timeLeft = 60;
                        timeLeftSpan.textContent = timeLeft;
                        countdown.classList.remove('hidden');
                        resendLink.classList.add('hidden');
                        
                        const newTimer = setInterval(() => {
                            timeLeft--;
                            timeLeftSpan.textContent = timeLeft;
                            
                            if (timeLeft <= 0) {
                                clearInterval(newTimer);
                                countdown.classList.add('hidden');
                                resendLink.classList.remove('hidden');
                                resendLink.textContent = originalText;
                            }
                        }, 1000);
                    } else {
                        alert('Failed to resend verification code. Please try again.');
                        resendLink.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    resendLink.textContent = originalText;
                });
            });
        });
    </script>
</body>
</html>