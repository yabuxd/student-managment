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
        <div class="glass-panel" style="width: 100%; max-width: 400px;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2><span class="text-accent">Edu</span>Portal</h2>
                <p style="color: var(--text-muted);">Sign in to your account</p>
            </div>
            <form onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>Email / Username</label>
                    <input type="text" id="username" class="form-control" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            <p style="text-align: center; margin-top: 1.5rem; color: var(--text-muted); font-size: 0.875rem;">
                Don't have an account? <a href="register.php" class="text-accent" style="text-decoration: none;">Register here</a>
            </p>
        </div>
    </div>

    <script>
        function handleLogin(e) {
            e.preventDefault();
            // Mock authentication for the template
            // In a real app, this hits the backend and gets a token & role
            const user = document.getElementById('username').value.toLowerCase();
            let role = 'student'; // default mock
            if(user.includes('teacher')) role = 'teacher';
            if(user.includes('parent')) role = 'parent';
            
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', user);
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
