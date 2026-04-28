<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555;}
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px;}
        .error { color: #dc3545; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px;}
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Stress Induction System</h2>
        
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="admin@system.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit">Secure Login</button>
        </form>
    </div>

</body>
</html>