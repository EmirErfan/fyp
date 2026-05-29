<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* (Using the exact same CSS from your login.blade.php) */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .login-container { width: 100%; max-width: 1000px; height: 650px; background-color: #F8F9FA; border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); display: flex; overflow: hidden; }
        .left-panel { flex: 1; padding: 3rem 2rem; display: flex; flex-direction: column; justify-content: center; align-items: center; position: relative; }
        .left-panel-inner { width: 100%; max-width: 340px; }
        .left-panel h1 { font-size: 2rem; font-weight: 600; color: #1A1A1A; text-align: center; margin-bottom: 1rem; }
        .left-panel p.desc { text-align: center; color: #666; font-size: 0.85rem; margin-bottom: 2.5rem; line-height: 1.5; }
        .form-group { position: relative; margin-bottom: 1.25rem; }
        .form-group input { width: 100%; padding: 1.1rem 1.25rem; background: #FFFFFF; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 500; color: #333; outline: none; box-shadow: 0 4px 10px rgba(0,0,0,0.015); transition: box-shadow 0.2s; }
        .form-group input::placeholder { color: #B0B0B0; font-weight: 400; }
        .form-group input:focus { box-shadow: 0 0 0 2px rgba(143,100,105,0.2); }
        .alert-error { background: #ffebe9; color: #d1242f; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.8rem; margin-bottom: 1.25rem; }
        .submit-btn { width: 100%; padding: 1.1rem; background-color: #926A6F; color: #FFFFFF; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 500; cursor: pointer; box-shadow: 0 10px 25px rgba(146,106,111,0.3); transition: transform 0.2s, box-shadow 0.2s; margin-bottom: 2rem; }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(146,106,111,0.4); }
        .right-panel { flex: 1.1; background-color: #E2D9D6; background-image: url('/images/login-illustration.png'); background-size: cover; background-position: center; border-radius: 0 30px 30px 0; }
        @media (max-width: 850px) { .login-container { flex-direction: column; height: auto; border-radius: 20px; } .right-panel { display: none; } }
    </style>
</head>
<body>

<div class="login-container">
    <div class="left-panel">
        <div class="left-panel-inner">
            <h1>Secure Account</h1>
            <p class="desc">Please enter your new password below. Make sure it is at least 8 characters long.</p>
            
            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <input type="email" name="email" value="{{ request()->email }}" readonly style="background: #f1f1f1; color: #888;">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="New Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
                </div>
                
                <button type="submit" class="submit-btn">Reset Password</button>
            </form>
        </div>
    </div>
    <div class="right-panel"></div>
</div>

</body>
</html>