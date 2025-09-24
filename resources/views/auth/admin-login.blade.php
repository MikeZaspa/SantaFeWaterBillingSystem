<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Santa Fe Water Billing System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #1a73e8;
            --primary-dark: #0d5bba;
            --text: #202124;
            --text-light: #5f6368;
            --light: #f8f9fa;
            --white: #ffffff;
            --border: #dadce0;
            --error: #d93025;
            --success: #06d6a0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: var(--white);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        .login-logo {
            width: 170px;
            height: 120px;
            margin-bottom: 1.5rem;
        }
        
        .system-title {
            color: var(--text);
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
            position: relative;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }
        
        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            background-color: var(--primary-dark);
        }
        
        .forgot-password {
            margin: 1rem 0;
            text-align: right;
        }
        
        .forgot-password a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        
        .forgot-password a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .forgot-password a i {
            margin-left: 5px;
            font-size: 0.8rem;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 2rem 0;
            color: var(--text-light);
            font-size: 0.8rem;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--border);
        }
        
        .divider::before {
            margin-right: 1rem;
        }
        
        .divider::after {
            margin-left: 1rem;
        }
        
        .signup-link {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .signup-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            margin-left: 0.5rem;
        }
        
        .language-selector {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        .language-selector a {
            color: var(--text);
            text-decoration: none;
            margin: 0 0.3rem;
        }
        
        .language-selector a.active {
            color: var(--primary);
        }
        
        .error-message {
            color: var(--error);
            font-size: 0.8rem;
            margin-top: 0.4rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                border: none;
            }
        }
        
        .alert-success {
            background-color: #e6ffed;
            color: var(--success);
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid #a3d9a5;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('image/santafe.png') }}" class="login-logo" alt="Santa Fe Water">
            <h1 class="system-title">Santa Fe Water Billing System</h1>
        </div> 
        
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <form id="loginForm" method="POST" action="{{ route('admin-login') }}">
            @csrf
            
            <div class="form-group">
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email address">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" id="password" name="password" required placeholder="Password">
                <i class="fas fa-eye-slash input-icon" id="togglePassword"></i>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="forgot-password">
                <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                    Forgot password? <i class="fas fa-question-circle"></i>
                </a>
            </div>
            
            <button type="submit" class="btn-login">
                <span>Log In</span>
            </button>
            
            <div class="divider">or</div>
            
            <div class="signup-link">
                <span>Don't have an account?</span>
                <a href="{{ route('admin-register') }}">Sign up</a>
            </div>
            <div class="extra-portals" style="margin-top: 1rem; font-size: 0.9rem; text-align: center;">
                <a href="" style="color: var(--primary); text-decoration: none; margin-right: 1rem;">
                    Plumber Portal and Accountant Portal
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            
            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
            
            // Form validation
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validate email
                if(!emailInput.value) {
                    isValid = false;
                    emailInput.style.borderColor = 'var(--error)';
                    const errorDiv = emailInput.nextElementSibling;
                    if(errorDiv && errorDiv.classList.contains('error-message')) {
                        errorDiv.textContent = 'Email is required';
                        errorDiv.style.display = 'block';
                    }
                } else if(!/\S+@\S+\.\S+/.test(emailInput.value)) {
                    isValid = false;
                    emailInput.style.borderColor = 'var(--error)';
                    const errorDiv = emailInput.nextElementSibling;
                    if(errorDiv && errorDiv.classList.contains('error-message')) {
                        errorDiv.textContent = 'Please enter a valid email address';
                        errorDiv.style.display = 'block';
                    }
                }
                
                // Validate password
                if(!passwordInput.value) {
                    isValid = false;
                    passwordInput.style.borderColor = 'var(--error)';
                    const errorDiv = passwordInput.nextElementSibling.nextElementSibling;
                    if(errorDiv && errorDiv.classList.contains('error-message')) {
                        errorDiv.textContent = 'Password is required';
                        errorDiv.style.display = 'block';
                    }
                }
                
                if(!isValid) {
                    e.preventDefault();
                }
            });
            
            // Clear errors when typing
            emailInput.addEventListener('input', function() {
                this.style.borderColor = 'var(--border)';
                const errorDiv = this.nextElementSibling;
                if(errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.style.display = 'none';
                }
            });
            
            passwordInput.addEventListener('input', function() {
                this.style.borderColor = 'var(--border)';
                const errorDiv = this.nextElementSibling.nextElementSibling;
                if(errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>