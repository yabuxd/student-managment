<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'vibrant';
$primaryColor = !empty($schoolSite['primary_color']) ? $schoolSite['primary_color'] : '#000000';
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
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@500;700;800;900&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root {
            --border-dark: #000000;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-color, #f3f4f6);
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .brutal-btn {
            border: 4px solid var(--border-dark);
            box-shadow: 6px 6px 0 var(--border-dark);
            transition: all 0.15s cubic-bezier(0, 0, 0, 1);
            font-weight: 900;
            text-transform: uppercase;
            width: 100%;
            cursor: pointer;
            padding: 1rem;
            background: var(--primary);
            color: #fff;
            font-size: 1rem;
        }
        .brutal-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0 var(--border-dark);
        }
        .brutal-btn:active {
            transform: translate(4px, 4px);
            box-shadow: 2px 2px 0 var(--border-dark);
        }
        .brutal-card {
            border: 4px solid var(--border-dark);
            box-shadow: 8px 8px 0 var(--border-dark);
            background: #fff;
            padding: 2.5rem;
            color: #000;
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            border: 3px solid var(--border-dark) !important;
            border-radius: 0 !important;
            padding: 0.85rem !important;
            font-weight: 700;
            background: #fff !important;
            color: #000 !important;
            box-shadow: inset 2px 2px 0px rgba(0,0,0,0.05) !important;
            width: 100%;
            box-sizing: border-box;
            display: block;
            font-size: 0.95rem;
            outline: none;
        }
        .form-control:focus {
            background: var(--bg-color, #f9fafb) !important;
            box-shadow: 4px 4px 0 var(--border-dark) !important;
        }
        label {
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.85rem;
            letter-spacing: 0.02em;
        }
    </style>
</head>
<body>
    <div class="auth-container" style="padding: 1.5rem; width: 100%; display: flex; justify-content: center;">
        <div class="brutal-card" style="text-align: center;">
            <h2 style="margin-bottom: 0.5rem; font-size: 2.3rem; background: var(--accent-1, #22d3ee); display: inline-block; padding: 0.3rem 1.2rem; border: 4px solid var(--border-dark); font-weight: 900; text-transform: uppercase; box-shadow: 4px 4px 0 var(--border-dark);">LOGIN</h2>
            <p style="font-weight: 800; text-transform: uppercase; margin-top: 1.25rem; margin-bottom: 2rem; font-size: 0.9rem;">Access Branded Portals</p>
            
            <form onsubmit="handleLogin(event)">
                <div class="form-group" style="text-align: left; margin-bottom: 1.5rem;">
                    <label for="role">Institutional Gateway</label>
                    <select id="role" class="form-control" style="height: auto;">
                        <option value="student">Student Portal</option>
                        <option value="teacher">Faculty Core</option>
                        <option value="parent">Parent Radar</option>
                        <option value="director">Director Console</option>
                    </select>
                </div>
                <div class="form-group" style="text-align: left; margin-bottom: 1.5rem;">
                    <label for="username">Username / ID</label>
                    <input type="text" id="username" class="form-control" required placeholder="e.g. brutal_id">
                </div>
                <div class="form-group" style="text-align: left; margin-bottom: 2rem;">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" required placeholder="********">
                </div>
                <button type="submit" class="brutal-btn">Sign In &rarr;</button>
            </form>
            <p style="margin-top: 2.2rem; font-weight: 800; font-size: 0.85rem; text-transform: uppercase;">
                New admission? <a href="register.php" style="color: var(--accent-3, #f43f5e); text-decoration: none; border-bottom: 2px solid var(--accent-3);">Enroll here</a>
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

        async function handleLogin(e) {
            e.preventDefault();
            const selectedRole = document.getElementById('role').value;
            const user = document.getElementById('username').value;
            const pass = document.getElementById('password').value;
            const btn = e.target.querySelector('button');
            const originalBtnText = btn.textContent;
            
            btn.disabled = true;
            btn.textContent = 'AUTHENTICATING...';
            
            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: user, password: pass, role: selectedRole })
                });
                
                const result = await response.json();
                
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
