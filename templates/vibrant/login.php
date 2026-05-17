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
    <title>Login | SIS</title>
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
