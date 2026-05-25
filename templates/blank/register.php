<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'modern-enterprise';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Outfit';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Create a new portal account.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Us | <?php echo htmlspecialchars($schoolSite['name'] ?? 'Institution'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: #000;
            color: #fff;
            background-color: var(--bg-color, #0f172a);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 120vh;
            padding: 1rem 0;
            background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 117%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 0;
        }
        .enterprise-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1.5rem;
            padding: .5rem 3rem;
            width: 100%;
            max-width: 480px;
            z-index: 1;
            position: relative;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
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
        .form-control option {
            background: #000;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="enterprise-card">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
            </div>
            <h2 style="margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 700;">Join the network</h2>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; margin: 0;">Create your portal account</p>
        </div>
        
        <form onsubmit="handleRegister(event)">
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label style="color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.85rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Full Name</label>
                <input type="text" class="form-control" required placeholder="Alem Kebede">
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label style="color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.85rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Email Address</label>
                <input type="email" class="form-control" required placeholder="name@domain.com">
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label style="color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.85rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Account Type</label>
                <select class="form-control" id="roleSelect">
                    <option value="student">Student</option>
                    <option value="teacher">Faculty Member</option>
                    <option value="parent">Parent</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label style="color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.85rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Password</label>
                <input type="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="enterprise-btn">Create Account</button>
        </form>
        <p style="text-align: center; margin-top: 2rem; color: rgba(255,255,255,0.5); font-size: 0.9rem;">
            Already with us? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign in here</a>
        </p>
    </div>

    <script>
        function handleRegister(e) {
            e.preventDefault();
            const role = document.getElementById('roleSelect').value;
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', 'New Member');
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
