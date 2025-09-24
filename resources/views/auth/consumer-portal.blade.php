<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santa Fe Water Billing System - Consumer Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #d32f2f;
            --primary-light: #ff6659;
            --primary-dark: #9a0007;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            background-image: url('https://images.unsplash.com/photo-1569336415962-a2bddaa96e4d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 4px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.25);
        }
        
        .btn-login {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-login:hover {
            background-color: var(--primary-dark);
            color: white;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-right: none;
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .login-footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .password-toggle {
            cursor: pointer;
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-left: none;
            display: flex;
            align-items: center;
            padding: 0 15px;
        }
        
        .password-toggle:hover {
            background-color: #e9ecef;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <img src="image/santafe.png" class="login-logo">
                <h2>Santa Fe Water Billing System</h2>
                <p class="mb-0">Consumer Login Portal</p>
            </div>
            
            <div class="login-body">
                <form id="loginForm" action="/consumer/login" method="POST">
                    @csrf <!-- Add this for Laravel CSRF protection -->
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Account Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                id="username" name="username" 
                                value="{{ old('username') }}" 
                                placeholder="Enter your meter number" required>
                        </div>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" 
                                placeholder="Enter your password" required>
                            <span class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-login w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </button>
                </form>
            </div>
            
            <div class="login-footer">
                <p class="mb-0">&copy; <span id="currentYear"></span> Santa Fe Water Billing System. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Set current year in footer
            $('#currentYear').text(new Date().getFullYear());
            
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('bi-eye bi-eye-slash');
            });
            

            $('#loginForm').submit(function(e) {
                
            });
        });
    </script>
</body>
</html>