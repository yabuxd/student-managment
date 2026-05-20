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
            background: var(--bg-color, #fbfbfd);
            color: var(--text-color, #1d1d1f);
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
            border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.03);
            padding: 3rem;
            width: 100%;
            max-width: 420px;
        }
        .minimal-btn {
            background: var(--primary);
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
            opacity: 0.9;
            transform: scale(1.01);
        }
        .form-control {
            border-radius: 0.75rem !important;
            border: 1px solid #d2d2d7 !important;
            padding: 0.8rem 1rem !important;
            background: rgba(255,255,255,0.8) !important;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 100%;
            box-sizing: border-box;
            display: block;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px rgba(0,113,227,0.05) !important;
            outline: none;
        }
        label {
            color: #1d1d1f;
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="minimal-glass">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-bottom: 1rem;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; font-weight: 600; letter-spacing: -0.02em;">Welcome Back</h2>
                <p style="color: #86868b; font-size: 0.95rem;">Enter your credentials to access the portal</p>
            </div>
            
            <form onsubmit="handleLogin(event)">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="role">Portal Directory</label>
                    <select id="role" class="form-control" style="height: auto;">
                        <option value="student">Student Portal</option>
                        <option value="teacher">Faculty Directory</option>
                        <option value="parent">Parent Directory</option>
                        <option value="director">Director Console</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="username">Institution ID</label>
                    <input type="text" id="username" class="form-control" required placeholder="name@school.edu">
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline;">
                        <label for="password">Password</label>
                        <a href="#" style="color: var(--primary); font-size: 0.85rem; text-decoration: none;">Forgot?</a>
                    </div>
                    <input type="password" id="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="minimal-btn">Sign In to Portal</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: #86868b; font-size: 0.9rem;">
                Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">Request Access</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const roleParam = urlParams.get('role');
            if (roleParam) {
                const roleSelect = document.getElementById('role');
                if (roleSelect.querySelector(`option[value="${roleParam}"]`)) {
                    roleSelect.value = roleParam;
                }
            }
        });

        function handleLogin(e) {
            e.preventDefault();
            const selectedRole = document.getElementById('role').value;
            const user = document.getElementById('username').value;
            
            localStorage.setItem('user_role', selectedRole);
            localStorage.setItem('user_name', user);
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
