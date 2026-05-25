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
            background-color: var(--bg-color, #0f172a);
            color: var(--text-color, #fff);
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
            top: 0; left: 0; width: 100%; height: 105%;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 0;
        }
        .enterprise-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-radius: 1.5rem;
            padding: 3rem;
            width: 100%;
            max-width: 440px;
            z-index: 1;
            position: relative;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        .enterprise-btn {
            background: var(--primary);
            color: #fff;
            padding: 1rem 1.5rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            text-align: center;
            border: none;
            cursor: pointer;
            margin-top: 1.5rem;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        }
        .enterprise-btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .form-control {
            background: rgba(255,255,255,0.04) !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            border-radius: 12px !important;
            padding: 1rem !important;
            color: #fff !important;
            font-size: 0.95rem;
            transition: all 0.3s;
            width: 100%;
            box-sizing: border-box;
            display: block;
        }
        .form-control:focus {
            border-color: var(--primary) !important;
            background: rgba(255,255,255,0.08) !important;
            box-shadow: none !important;
            outline: none;
        }
        .form-control::placeholder {
            color: rgba(255,255,255,0.25);
        }
        label {
            color: rgba(255,255,255,0.75);
            font-weight: 500;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        select.form-control option {
            background: #0f172a;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="enterprise-card">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <h2 style="margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em;">Gateway Access</h2>
            <p style="color: rgba(255,255,255,0.45); font-size: 0.95rem; margin: 0;">Sign in to your learning dashboard</p>
        </div>
        
        <form onsubmit="handleLogin(event)">
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="role">User Gateway</label>
                <select id="role" class="form-control" style="height: auto;">
                    <option value="student">Student Portal</option>
                    <option value="teacher">Faculty Core</option>
                    <option value="parent">Parent Radar</option>
                    <option value="director">Director Console</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="username">Email Address</label>
                <input type="text" id="username" class="form-control" required placeholder="name@domain.com">
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="enterprise-btn">Sign In</button>
        </form>
        <p style="text-align: center; margin-top: 2rem; color: rgba(255,255,255,0.4); font-size: 0.9rem;">
            Need help? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Request Access</a>
        </p>
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

        async function handleLogin(e) {
            e.preventDefault();
            const selectedRole = document.getElementById('role').value;
            const user = document.getElementById('username').value;
            const pass = document.getElementById('password').value;
            const btn = e.target.querySelector('button');
            const originalBtnText = btn.textContent;
            
            btn.disabled = true;
            btn.textContent = 'Authenticating...';
            
            try {
                API_BASE = 'sis.localhost/api';
                const response = await fetch(`${API_BASE}/auth/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: user, password: pass, role: selectedRole })
                });
                
                const result = await response.text();
                console.log('url', `${API_BASE}/auth/login`);
                console.log(result);
                
                if (result.success) {
                    localStorage.setItem('token', result.token);
                    localStorage.setItem('user_role', selectedRole);
                    localStorage.setItem('user_name', result.user_name);
                    localStorage.setItem('user_id', result.user_id);
                    if (result.school_id) {
                        localStorage.setItem('school_id', result.school_id);
                    }
                    window.location.href = 'dashboard.php';
                } else {
                    alert(result.message || 'Authentication failed. Please check credentials.');
                }
            } catch (err) {
                console.error(err);
                alert('Connection error. Please try again.');
            } finally {
                btn.disabled = false;
                btn.textContent = originalBtnText;
            }
        }
    </script>
</body>
</html>
