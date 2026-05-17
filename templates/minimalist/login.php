<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'minimalist';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Login to your portal.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIS</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background: #fbfbfd;
            color: #1d1d1f;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .minimal-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.04);
            padding: 3rem;
            width: 100%;
            max-width: 420px;
        }
        .minimal-btn {
            background: #0071e3;
            color: #fff;
            border-radius: 980px;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            border: 1px solid transparent;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }
        .minimal-btn:hover {
            background: #0077ed;
            transform: scale(1.02);
        }
        .form-control {
            border-radius: 0.75rem !important;
            border: 1px solid #d2d2d7 !important;
            padding: 0.8rem 1rem !important;
            background: rgba(255,255,255,0.8) !important;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus {
            border-color: #0071e3 !important;
            box-shadow: 0 0 0 4px rgba(0,113,227,0.1) !important;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="auth-container">
        <div class="minimal-glass">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#0071e3" stroke-width="2" style="margin-bottom: 1rem;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; font-weight: 600; letter-spacing: -0.02em;">Welcome Back</h2>
                <p style="color: #86868b; font-size: 0.95rem;">Enter your credentials to access the portal</p>
            </div>
            <form onsubmit="handleLogin(event)">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="color: #1d1d1f; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Email Address</label>
                    <input type="text" id="username" class="form-control" required placeholder="name@school.edu">
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline;">
                        <label style="color: #1d1d1f; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Password</label>
                        <a href="#" style="color: #0071e3; font-size: 0.85rem; text-decoration: none;">Forgot?</a>
                    </div>
                    <input type="password" id="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="minimal-btn">Sign In to Portal</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: #86868b; font-size: 0.9rem;">
                Don't have an account? <a href="register.php" style="color: #0071e3; text-decoration: none; font-weight: 500;">Request Access</a>
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
