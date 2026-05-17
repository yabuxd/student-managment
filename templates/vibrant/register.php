<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'vibrant';
$primaryColor = !empty($schoolSite['primary_color']) ? $schoolSite['primary_color'] : '#000000';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Register for an account.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SIS</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root {
            --border-dark: <?php echo htmlspecialchars($primaryColor); ?>;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem 0;
        }
        .brutal-btn {
            border: 4px solid var(--border-dark);
            box-shadow: 6px 6px 0 var(--border-dark);
            transition: all 0.2s ease;
            font-weight: 800;
            text-transform: uppercase;
            width: 100%;
            cursor: pointer;
            padding: 1rem;
            background: var(--border-dark);
            color: #fff;
        }
        .brutal-btn:hover {
            transform: translate(2px, 2px);
            box-shadow: 4px 4px 0 var(--border-dark);
        }
        .brutal-btn:active {
            transform: translate(6px, 6px);
            box-shadow: 0 0 0 var(--border-dark);
        }
        .brutal-card {
            border: 4px solid var(--border-dark);
            box-shadow: 8px 8px 0 var(--border-dark);
            background: #fff;
            padding: 2.5rem;
            color: #000;
        }
        .form-control {
            border: 3px solid var(--border-dark) !important;
            border-radius: 0 !important;
            padding: 0.75rem !important;
            font-weight: 600;
            background: #f8f9fa !important;
            color: #000 !important;
            box-shadow: inset 2px 2px 0px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="brutal-card" style="width: 100%; max-width: 450px; text-align: center;">
            <h2 style="margin-bottom: 0.5rem; font-size: 2.5rem; background: var(--accent-coral); color: #000; display: inline-block; padding: 0 1rem; border: 3px solid var(--border-dark); font-weight: 900; text-transform: uppercase;">REGISTER</h2>
            <p style="font-weight: 600; margin-bottom: 2rem;">Join the crew</p>
            
            <form onsubmit="handleRegister(event)">
                <div class="form-group" style="text-align: left; margin-bottom: 1rem;">
                    <label style="font-weight: 800; text-transform: uppercase;">Full Name</label>
                    <input type="text" class="form-control" required>
                </div>
                <div class="form-group" style="text-align: left; margin-bottom: 1rem;">
                    <label style="font-weight: 800; text-transform: uppercase;">Email</label>
                    <input type="email" class="form-control" required>
                </div>
                <div class="form-group" style="text-align: left; margin-bottom: 1rem;">
                    <label style="font-weight: 800; text-transform: uppercase;">I AM A...</label>
                    <select class="form-control" id="roleSelect">
                        <option value="student">STUDENT</option>
                        <option value="teacher">TEACHER</option>
                        <option value="parent">PARENT</option>
                    </select>
                </div>
                <div class="form-group" style="text-align: left; margin-bottom: 2rem;">
                    <label style="font-weight: 800; text-transform: uppercase;">Password</label>
                    <input type="password" class="form-control" required>
                </div>
                <button type="submit" class="brutal-btn" style="background: var(--accent-yellow); color: #000;">Create Account</button>
            </form>
            <p style="margin-top: 2rem; font-weight: 600; font-size: 0.875rem;">
                ALREADY IN? <a href="login.php" style="color: var(--accent-cyan); text-decoration: none; border-bottom: 2px solid var(--accent-cyan);">LOGIN</a>
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
