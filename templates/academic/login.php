<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login | Institution Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div style="text-align: center; margin-bottom: 2rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <h2 style="margin: 1rem 0 0.5rem;">Secure Login</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Please authenticate to access the portal.</p>
            </div>
            
            <form onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>Institution Email or ID</label>
                    <input type="text" id="username" class="form-control" required placeholder="e.g. s12345@inst.edu">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="password" class="form-control" required placeholder="Enter password">
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Authenticate</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.85rem;">
                New admission? <a href="register.php" style="color: var(--primary); font-weight: 600;">Enroll here</a>
            </p>
        </div>
    </div>

    <script>
        function handleLogin(e) {
            e.preventDefault();
            const user = document.getElementById('username').value.toLowerCase();
            let role = 'student';
            if(user.includes('faculty') || user.includes('teacher')) role = 'teacher';
            if(user.includes('parent')) role = 'parent';
            
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', user);
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
