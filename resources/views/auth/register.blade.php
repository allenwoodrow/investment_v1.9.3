@php
    Session::put('locale', 'en');
    Session::put('locale_preferred', false);
    App::setLocale('en');
    app()->setLocale('en');
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.register_now') }} - Investimento de Capital</title>
    <link href="https://azim.hostlin.com/Counsolve/assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://azim.hostlin.com/Counsolve/assets/css/style.css" rel="stylesheet">
    <link href="https://azim.hostlin.com/Counsolve/assets/css/font-awesome-all.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Urbanist', sans-serif;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }
        .auth-card .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e0e0e0;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .alert {
            border-radius: 8px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="https://investimentodecapital.online/public/img/logo-2.png" 
                         alt="Investimento de Capital" 
                         style="width: 120px; height: auto;">
                </a>
                <h4 class="mt-3">{{ __('messages.create_account') }}</h4>
            </div>
            
            <div class="alert alert-danger" id="errorAlert"></div>
            <div class="alert alert-success" id="successAlert"></div>
            
            <form id="registerForm">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fullname">{{ __('messages.fullname') }} *</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="username">{{ __('messages.username') }} *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone">{{ __('messages.phone') }} *</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password">{{ __('messages.password') }} *</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <small class="text-muted">{{ __('messages.password_min') }}</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country">{{ __('messages.country') }} *</label>
                        <input type="text" class="form-control" id="country" name="country" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dob">{{ __('messages.dob') }} *</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="zip">{{ __('messages.zip') }} *</label>
                        <input type="text" class="form-control" id="zip" name="zip" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <span id="registerText">{{ __('messages.register_now') }}</span>
                    <span id="registerSpinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> {{ __('messages.processing') }}
                    </span>
                </button>
            </form>
            
            <div class="text-center">
                <p>{{ __('messages.already_have_account') }} <a href="{{ url('/auth/login') }}">{{ __('messages.login') }}</a></p>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-muted">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.back_home') }}
                </a>
            </div>
        </div>
    </div>

    <script src="https://azim.hostlin.com/Counsolve/assets/js/jquery.js"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                
                $('#registerText').hide();
                $('#registerSpinner').show();
                $('.alert').hide();
                
                const formData = {
                    fullname: $('#fullname').val(),
                    username: $('#username').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    password: $('#password').val(),
                    country: $('#country').val(),
                    dob: $('#dob').val(),
                    zip: $('#zip').val(),
                    _token: $('input[name="_token"]').val()
                };
                
                $.ajax({
                    url: '/api/register',  // Use /api/register
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#successAlert').text(response.message || '{{ __("messages.registration_success") }}').show();
                        setTimeout(function() {
                            window.location.href = '/auth/login';
                        }, 2000);
                    },
                    error: function(xhr) {
                        $('#registerText').show();
                        $('#registerSpinner').hide();
                        
                        let errorMessage = '{{ __("messages.registration_failed") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        $('#errorAlert').text(errorMessage).show();
                    }
                });
            });
        });
    </script>
</body>
</html>