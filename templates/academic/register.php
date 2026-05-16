<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions | Institution Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card" style="max-width: 500px;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                <h2 style="margin: 1rem 0 0.5rem;">New Enrollment</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Register for an institutional account.</p>
            </div>
            
            <form onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label>Legal Full Name</label>
                    <input type="text" class="form-control" required placeholder="First Last">
                </div>
                <div class="form-group">
                    <label>Personal Email</label>
                    <input type="email" class="form-control" required placeholder="name@domain.com">
                </div>
                <div class="form-group">
                    <label>Account Type</label>
                    <select class="form-control" id="roleSelect">
                        <option value="student">Prospective Student</option>
                        <option value="teacher">Faculty Member</option>
                        <option value="parent">Parent / Guardian</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Create Password</label>
                    <input type="password" class="form-control" required placeholder="Min. 8 characters">
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Submit Application</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.85rem;">
                Already enrolled? <a href="login.php" style="color: var(--primary); font-weight: 600;">Sign in here</a>
            </p>
        </div>
    </div>

    <script>
        function handleRegister(e) {
            e.preventDefault();
            const role = document.getElementById('roleSelect').value;
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', 'New Applicant');
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
