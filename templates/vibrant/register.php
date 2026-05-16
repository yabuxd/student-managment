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
        <div class="brutal-card" style="width: 100%; max-width: 450px; text-align: center;">
            <h2 style="margin-bottom: 0.5rem; font-size: 2.5rem; background: var(--accent-1); color: #fff; display: inline-block; padding: 0 1rem;">REGISTER</h2>
            <p style="font-weight: 600; margin-bottom: 2rem;">Join the crew</p>
            
            <form onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>I AM A...</label>
                    <select class="form-control" id="roleSelect">
                        <option value="student">STUDENT</option>
                        <option value="teacher">TEACHER</option>
                        <option value="parent">PARENT</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-secondary" style="width: 100%;">Create Account</button>
            </form>
            <p style="margin-top: 2rem; font-weight: 600; font-size: 0.875rem;">
                ALREADY IN? <a href="login.php" style="color: var(--accent-2); text-decoration: none; border-bottom: 2px solid var(--accent-2);">LOGIN</a>
            </p>
        </div>
    </div>

    <script>
        function handleRegister(e) {
            e.preventDefault();
            const role = document.getElementById('roleSelect').value;
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', 'New User');
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
