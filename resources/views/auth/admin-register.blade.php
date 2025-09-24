<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Santa Fe Water Billing System</title>
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
        .register-page {
            display: flex;
            background: var(--white);
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            max-width: 960px;
        }
        .register-illustration {
            flex: 1;
            background: #fff;
            display: flex;
            flex-direction: column; 
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center; 
        }
        .register-illustration img {
            max-width: 100%;
            height: auto;
        }
        .register-illustration h1 {
            font-size: 40px;
            color: black;
            font-weight: bold;
        }
        h2 {
            font-size: 30px;
        }
        .register-container {
            flex: 1;
            padding: 40px;
        }
        .register-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text);
        }
        .register-header p {
            font-size: 14px;
            color: var(--text-light);
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            background-color: var(--light);
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(86, 36, 208, 0.1);
        }
        .form-group input.error,
        .form-group select.error {
            border-color: var(--error);
        }
        .error-message {
            color: var(--error);
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        .btn-register {
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
        }
        .btn-register:hover {
            background: var(--primary-dark);
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .login-link a {
            color: var(--primary);
            text-decoration: none;
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
        @media (max-width: 768px) {
            .register-page {
                flex-direction: column;
            }
            .register-illustration {
                display: none;
            }
        }
        
        /* Verification Page Styles */
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
    </style>
</head>
<body>
    <div class="register-page">
        <div class="register-illustration">
            <img src="{{ asset('image/santafe.png') }}" alt="Register Illustration">
            <h1>Santa Fe Water</h1>
            <h2>Billing System</h2>
        </div>
        <div class="register-container">
            <div class="register-header">
                <h1>Sign up with email</h1>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form id="registerForm" method="POST" action="{{ route('admin-register') }}">
                @csrf

                <div class="form-group">
                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required class="{{ $errors->has('first_name') ? 'error' : '' }}">
                    @error('first_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name (Optional)" class="{{ $errors->has('middle_name') ? 'error' : '' }}">
                    @error('middle_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required class="{{ $errors->has('last_name') ? 'error' : '' }}">
                    @error('last_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="date" name="birthdate" value="{{ old('birthdate') }}" required class="{{ $errors->has('birthdate') ? 'error' : '' }}">
                    @error('birthdate')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <select name="gender" required class="{{ $errors->has('gender') ? 'error' : '' }}">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <select name="role" required class="{{ $errors->has('role') ? 'error' : '' }}">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                        <option value="plumber" {{ old('role') == 'plumber' ? 'selected' : '' }}>Plumber</option>
                    </select>
                    @error('role')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
              <div class="form-group">
                    <input type="email" name="email" value="{{ old('email') }}" 
                        placeholder="you@example.com" required 
                        class="{{ $errors->has('email') ? 'error' : '' }}"
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="tel" name="contact_number" value="{{ old('contact_number') }}" 
                        placeholder="09123456789" required 
                        class="{{ $errors->has('contact_number') ? 'error' : '' }}"
                        pattern="09[0-9]{9}" 
                        maxlength="11"
                        title="Please enter a valid 11-digit number starting with 09">
                    @error('contact_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required class="{{ $errors->has('password') ? 'error' : '' }}">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn-register">Create Account</button>
                <div class="login-link">
                    Already have an account? <a href="{{ route('admin-login') }}">Log in</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactInput = document.querySelector('input[name="contact_number"]');
            
            if (contactInput) {
                // Prevent non-numeric input
                contactInput.addEventListener('input', function(e) {
                    // Remove any non-digit characters
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    // Ensure it starts with 09
                    if (this.value.length >= 2 && this.value.substring(0, 2) !== '09') {
                        this.value = '09' + this.value.substring(2);
                    }
                    
                    // Limit to 11 characters
                    if (this.value.length > 11) {
                        this.value = this.value.substring(0, 11);
                    }
                });
                
                // Prevent paste of non-numeric content
                contactInput.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');
                    const currentValue = this.value;
                    const selectionStart = this.selectionStart;
                    
                    // Insert the pasted numbers at the cursor position
                    this.value = currentValue.substring(0, selectionStart) + 
                                 pasteData + 
                                 currentValue.substring(this.selectionEnd);
                                 
                    // Ensure it starts with 09
                    if (this.value.length >= 2 && this.value.substring(0, 2) !== '09') {
                        this.value = '09' + this.value.substring(2);
                    }
                    
                    // Limit to 11 characters
                    if (this.value.length > 11) {
                        this.value = this.value.substring(0, 11);
                    }
                });
            }
            
            // Email validation
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const emailInput = document.querySelector('input[name="email"]');
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    
                    if (!emailRegex.test(emailInput.value)) {
                        e.preventDefault();
                        alert('Please enter a valid email address (e.g., user@example.com)');
                        emailInput.focus();
                    }
                    
                    // Additional validation for contact number
                    const contactInput = document.querySelector('input[name="contact_number"]');
                    if (contactInput && !/^09[0-9]{9}$/.test(contactInput.value)) {
                        e.preventDefault();
                        alert('Please enter a valid 11-digit contact number starting with 09');
                        contactInput.focus();
                    }
                });
            }
        });
    </script>
</body>
</html>