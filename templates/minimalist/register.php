<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SIS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="auth-container">
        <div class="glass-panel" style="width: 100%; max-width: 480px; padding: 3rem;">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" style="margin-bottom: 1rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Create Account</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Join the digital campus ecosystem</p>
            </div>
            <form onsubmit="handleRegister(event)">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" required placeholder="John">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" required placeholder="Doe">
                    </div>
                </div>
                <div class="form-group">
                    <label>Institution Email</label>
                    <input type="email" class="form-control" required placeholder="john@school.edu">
                </div>
                <div class="form-group">
                    <label>Account Type</label>
                    <select class="form-control" id="roleSelect" style="background-color: rgba(0,0,0,0.4); appearance: none;">
                        <option value="student">Student</option>
                        <option value="teacher">Faculty / Teacher</option>
                        <option value="parent">Parent / Guardian</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Complete Registration</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
                Already registered? <a href="login.php" class="text-accent" style="text-decoration: none; font-weight: 500;">Sign in instead</a>
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
