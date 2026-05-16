<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | EduPortal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="glass-panel" style="width: 100%; max-width: 450px;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2><span class="text-accent">Edu</span>Portal</h2>
                <p style="color: var(--text-muted);">Create your account</p>
            </div>
            <form onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" required placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label>I am a...</label>
                    <select class="form-control" id="roleSelect" style="background-color: #0f172a;">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="parent">Parent</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <p style="text-align: center; margin-top: 1.5rem; color: var(--text-muted); font-size: 0.875rem;">
                Already have an account? <a href="login.php" class="text-accent" style="text-decoration: none;">Sign in</a>
            </p>
        </div>
    </div>

    <script>
        function handleRegister(e) {
            e.preventDefault();
            // Mock registration routing
            const role = document.getElementById('roleSelect').value;
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', 'New User');
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
