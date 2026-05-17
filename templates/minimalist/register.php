<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'minimalist';
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
            padding: 2rem 0;
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
            max-width: 480px;
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
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#0071e3" stroke-width="2" style="margin-bottom: 1rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; font-weight: 600; letter-spacing: -0.02em;">Create Account</h2>
                <p style="color: #86868b; font-size: 0.95rem;">Join the digital campus ecosystem</p>
            </div>
            <form onsubmit="handleRegister(event)">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label style="color: #1d1d1f; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">First Name</label>
                        <input type="text" class="form-control" required placeholder="John">
                    </div>
                    <div class="form-group">
                        <label style="color: #1d1d1f; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Last Name</label>
                        <input type="text" class="form-control" required placeholder="Doe">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="color: #1d1d1f; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Institution Email</label>
                    <input type="email" class="form-control" required placeholder="john@school.edu">
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="color: #1d1d1f; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Account Type</label>
                    <select class="form-control" id="roleSelect">
                        <option value="student">Student</option>
                        <option value="teacher">Faculty / Teacher</option>
                        <option value="parent">Parent / Guardian</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label style="color: #1d1d1f; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Password</label>
                    <input type="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="minimal-btn">Complete Registration</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: #86868b; font-size: 0.9rem;">
                Already registered? <a href="login.php" style="color: #0071e3; text-decoration: none; font-weight: 500;">Sign in instead</a>
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
