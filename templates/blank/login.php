<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'modern-enterprise';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Outfit';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Login to your portal.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo htmlspecialchars($schoolSite['name'] ?? 'Institution'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 0;
        }
        .enterprise-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1.5rem;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            z-index: 1;
            position: relative;
        }
        .enterprise-btn {
            background: #fff;
            color: #000;
            padding: 1rem 1.5rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: transform 0.3s, box-shadow 0.3s;
            display: block;
            width: 100%;
            text-align: center;
            border: none;
            cursor: pointer;
            margin-top: 1.5rem;
        }
        .enterprise-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(255,255,255,0.2);
        }
        .form-control {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 12px !important;
            padding: 1rem !important;
            color: #fff !important;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: rgba(255,255,255,0.5) !important;
            box-shadow: none !important;
            outline: none;
        }
        .form-control::placeholder {
            color: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="enterprise-card">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <h2 style="margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 700;">Welcome back</h2>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; margin: 0;">Sign in to your account</p>
        </div>
        
        <form onsubmit="handleLogin(event)">
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label style="color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.85rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Email Address</label>
                <input type="text" id="username" class="form-control" required placeholder="name@domain.com">
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: baseline;">
                    <label style="color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.85rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Password</label>
                    <a href="#" style="color: var(--primary); font-size: 0.85rem; text-decoration: none; font-weight: 500;">Forgot?</a>
                </div>
                <input type="password" id="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="enterprise-btn">Sign In</button>
        </form>
        <p style="text-align: center; margin-top: 2rem; color: rgba(255,255,255,0.5); font-size: 0.9rem;">
            Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Request Access</a>
        </p>
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
