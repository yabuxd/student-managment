<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EduPortal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="auth-container">
        <div class="glass-panel" style="width: 100%; max-width: 420px; padding: 3rem;">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" style="margin-bottom: 1rem;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Welcome Back</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Enter your credentials to access the portal</p>
            </div>
            <form onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" id="username" class="form-control" required placeholder="name@school.edu">
                </div>
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between;">
                        <label>Password</label>
                        <a href="#" style="color: var(--accent); font-size: 0.85rem; text-decoration: none;">Forgot?</a>
                    </div>
                    <input type="password" id="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign In to Portal</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
                Don't have an account? <a href="register.php" class="text-accent" style="text-decoration: none; font-weight: 500;">Request Access</a>
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
