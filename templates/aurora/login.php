<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$themePath  = !empty($schoolSite['theme_path'])  ? $schoolSite['theme_path']  : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography'])  ? $schoolSite['typography']  : 'Plus Jakarta Sans';
$schoolName = !empty($schoolSite['name'])        ? $schoolSite['name']        : 'SIS Academy';
$heroTitle  = !empty($schoolSite['hero_title'])  ? $schoolSite['hero_title']  : 'Welcome Back';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo htmlspecialchars($schoolName); ?></title>
    <meta name="description" content="Sign in to <?php echo htmlspecialchars($schoolName); ?> portal.">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/templates/aurora/assets/css/style.css">
    <style>
        :root {
            --primary:     var(--accent-1, #6366f1);
            --secondary:   var(--accent-2, #06b6d4);
            --tertiary:    var(--accent-3, #d946ef);
            --border-glass: rgba(255, 255, 255, 0.08);
            --font-custom: '<?php echo htmlspecialchars($typography); ?>', 'Plus Jakarta Sans', sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-custom);
            background: var(--bg-color, #070a13);
            color: var(--text-color, #f8fafc);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 1.5rem;
        }
        /* ── BACKGROUND DECORATIVE GLOW SPHERES ── */
        .deco-bubble-1 {
            position: absolute;
            width: 350px; height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
            top: 10%; left: 10%;
            pointer-events: none;
            z-index: -1;
        }
        .deco-bubble-2 {
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.12) 0%, transparent 70%);
            bottom: 10%; right: 10%;
            pointer-events: none;
            z-index: -1;
        }

        /* ── MODERN GLASS CARD ── */
        .login-card {
            width: 100%;
            max-width: 450px;
            background: rgba(16, 22, 38, 0.7);
            border: 1px solid var(--border-glass);
            padding: 3rem 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4), 0 0 30px rgba(99, 102, 241, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .school-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: var(--primary);
            padding: 0.4rem 1.1rem;
            border-radius: 99px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-card h2 {
            font-size: 2.2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
            background: linear-gradient(180deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .login-card .sub {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted, #94a3b8);
            margin-bottom: 2.5rem;
        }

        .fg {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-lbl {
            display: block;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-color, #f8fafc);
            margin-bottom: 0.5rem;
        }

        .b-input {
            display: block;
            width: 100%;
            border: 1px solid var(--theme-border-color, rgba(255, 255, 255, 0.1));
            padding: 0.85rem 1.1rem;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-color, #f8fafc);
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
        }

        .b-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 12px rgba(99, 102, 241, 0.2);
            background: rgba(255, 255, 255, 0.04);
        }

        .b-input option {
            background: var(--bg-card, #101626);
            color: var(--text-color, #f8fafc);
        }

        .b-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.95rem;
            border: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #ffffff;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1rem;
        }

        .b-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.5);
            opacity: 0.95;
        }

        .b-btn:active {
            transform: translateY(1px);
        }

        .b-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .err-box {
            background: rgba(244, 63, 94, 0.15);
            color: #fda4af;
            border: 1px solid rgba(244, 63, 94, 0.3);
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: none;
            text-align: left;
        }

        .auth-foot {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted, #94a3b8);
        }

        .auth-foot a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 800;
            border-bottom: 1px solid var(--primary);
            transition: all 0.3s ease;
        }

        .auth-foot a:hover {
            color: var(--secondary);
            border-color: var(--secondary);
        }
    </style>
</head>
<body>
    <div class="deco-bubble-1"></div>
    <div class="deco-bubble-2"></div>

    <div class="login-card">
        <div class="school-tag">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="margin-right: 0.3rem;"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            <?php echo htmlspecialchars($schoolName); ?>
        </div>

        <h2>Sign In</h2>
        <p class="sub">Enter your institution credentials</p>

        <div class="err-box" id="errBox"></div>

        <form onsubmit="handleLogin(event)" autocomplete="on">
            <div class="fg">
                <label class="form-lbl" for="role">Portal Access</label>
                <select id="role" class="b-input" style="height:auto;">
                    <option value="student">Student Portal</option>
                    <option value="teacher">Faculty Core</option>
                    <option value="parent">Parent Radar</option>
                    <option value="director">Director Console</option>
                </select>
            </div>
            <div class="fg">
                <label class="form-lbl" for="username">Username / Email / ID</label>
                <input type="text" id="username" class="b-input" required placeholder="e.g. hayle@school.edu" autocomplete="username">
            </div>
            <div class="fg">
                <label class="form-lbl" for="password">Password</label>
                <input type="password" id="password" class="b-input" required placeholder="••••••••" autocomplete="current-password">
            </div>
            <button type="submit" class="b-btn" id="loginBtn">Sign In &rarr;</button>
        </form>

        <p class="auth-foot">
            New student? <a href="register.php">Request access</a>
        </p>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const p = new URLSearchParams(window.location.search);
        if (p.get('role')) {
            const s = document.getElementById('role');
            if (s.querySelector(`option[value="${p.get('role')}"]`)) s.value = p.get('role');
        }
        if (!window.location.search.includes('preview_subdomain') && localStorage.getItem('token')) window.location.href = 'dashboard.php';
    });

    async function handleLogin(e) {
        e.preventDefault();
        const role = document.getElementById('role').value;
        const user = document.getElementById('username').value.trim();
        const pass = document.getElementById('password').value;
        const btn  = document.getElementById('loginBtn');
        const err  = document.getElementById('errBox');

        err.style.display = 'none';
        btn.disabled = true;
        btn.textContent = 'AUTHENTICATING...';

        try {
            const res  = await fetch(`/api/auth/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username: user, password: pass, role })
            });
            const data = await res.json();

            if (data.success) {
                localStorage.setItem('token',     data.token);
                localStorage.setItem('user_role', role);
                localStorage.setItem('user_name', data.user_name);
                localStorage.setItem('user_id',   data.user_id);
                if (data.school_id) localStorage.setItem('school_id', data.school_id);
                window.location.href = 'dashboard.php';
            } else {
                err.textContent    = data.message || 'Authentication failed. Check your credentials.';
                err.style.display  = 'block';
            }
        } catch (ex) {
            err.textContent   = 'Connection error. Please try again.';
            err.style.display = 'block';
        } finally {
            btn.disabled    = false;
            btn.textContent = 'Sign In →';
        }
    }
</script>
</body>
</html>

