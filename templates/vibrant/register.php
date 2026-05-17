<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'vibrant';
$primaryColor = !empty($schoolSite['primary_color']) ? $schoolSite['primary_color'] : '#000000';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SIS</title>
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <style>
        :root {
            --border-dark: <?php echo htmlspecialchars($primaryColor); ?>;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
        }
    </style>
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
