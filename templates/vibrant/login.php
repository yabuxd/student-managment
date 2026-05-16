<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EduPortal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="brutal-card" style="width: 100%; max-width: 400px; text-align: center;">
            <h2 style="margin-bottom: 0.5rem; font-size: 2.5rem; background: var(--accent-3); display: inline-block; padding: 0 1rem;">LOGIN</h2>
            <p style="font-weight: 600; margin-bottom: 2rem;">Enter the portal</p>
            
            <form onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>Username / Email</label>
                    <input type="text" id="username" class="form-control" required placeholder="superstudent@edu">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="password" class="form-control" required placeholder="********">
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            <p style="margin-top: 2rem; font-weight: 600; font-size: 0.875rem;">
                NEW HERE? <a href="register.php" style="color: var(--accent-1); text-decoration: none; border-bottom: 2px solid var(--accent-1);">REGISTER</a>
            </p>
        </div>
    </div>

    <script>
        function handleLogin(e) {
            e.preventDefault();
            const user = document.getElementById('username').value.toLowerCase();
            let role = 'student';
            if(user.includes('teacher')) role = 'teacher';
            if(user.includes('parent')) role = 'parent';
            
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', user);
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
