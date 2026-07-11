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
    <title>{{ __('messages.login') }} - Investimento de Capital</title>
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
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .auth-card .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-card .logo img {
            width: 150px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
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
                <h4 class="mt-3">{{ __('messages.investment_plans') }}</h4>
            </div>
            
            <div class="alert alert-danger" id="errorAlert"></div>
            <div class="alert alert-success" id="successAlert"></div>
            
            <form id="loginForm">
                @csrf
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="your@email.com">
                </div>
                <div class="form-group mb-4">
                    <label for="password">{{ __('messages.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <span id="loginText">{{ __('messages.login') }}</span>
                    <span id="loginSpinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Carregando...
                    </span>
                </button>
            </form>
            
            <div class="text-center">
                <p>{{ __('messages.dont_have_account') }} <a href="{{ url('/auth/register') }}">{{ __('messages.register_now') }}</a></p>
                <p><a href="{{ url('/auth/forgot-password') }}">{{ __('messages.forgot_password') }}</a></p>
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
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                // Show loading
                $('#loginText').hide();
                $('#loginSpinner').show();
                $('.alert').hide();
                
                const formData = {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    _token: $('input[name="_token"]').val()
                };
                
                $.ajax({
                        url: '/api/login',  // Use /api/login
                        method: 'POST',
                        data: formData,
                    success: function(response) {
                        if (response.token) {
                            localStorage.setItem('token', response.token);
                            localStorage.setItem('user', JSON.stringify(response.user));
                            window.location.href = '/dashboard';
                        }
                    },
                    error: function(xhr) {
                        $('#loginText').show();
                        $('#loginSpinner').hide();
                        
                        let errorMessage = '{{ __("messages.login_failed") }}';
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