<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$themePath  = !empty($schoolSite['theme_path'])  ? $schoolSite['theme_path']  : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography'])  ? $schoolSite['typography']  : 'Inter';
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
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <style>
        :root {
            --primary:     var(--accent-1, #facc15);
            --secondary:   var(--accent-2, #22d3ee);
            --tertiary:    var(--accent-3, #f43f5e);
            --border-dark: var(--border-color, #000000);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background: var(--bg-color, #f3f4f6);
            color: var(--text-color, #000);
            min-height: 100vh;
            display: flex;
        }
        /* ── LEFT HERO PANEL ── */
        .auth-left {
            flex: 1.1;
            background: var(--primary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            border-right: 4px solid var(--border-dark);
            position: relative;
            overflow: hidden;
        }
        .auth-left .deco-circle {
            position: absolute;
            border: 4px solid var(--border-dark);
            border-radius: 50%;
            bottom: -100px; right: -100px;
            width: 350px; height: 350px;
            background: var(--secondary);
        }
        .auth-left .deco-square {
            position: absolute;
            border: 4px solid var(--border-dark);
            top: 3.5rem; right: 3rem;
            width: 140px; height: 140px;
            background: var(--tertiary);
        }
        .auth-left .deco-dot {
            position: absolute;
            width: 24px; height: 24px;
            background: var(--border-dark);
            border-radius: 50%;
            top: 1.5rem; right: 10.5rem;
        }
        .school-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--border-dark);
            color: var(--primary);
            padding: 0.5rem 1.25rem;
            font-weight: 900;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            position: relative;
            z-index: 2;
        }
        .hero-text {
            position: relative;
            z-index: 2;
        }
        .hero-text h1 {
            font-size: clamp(2.8rem, 5vw, 5rem);
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1;
            color: var(--border-dark);
            letter-spacing: -0.02em;
        }
        .hero-text p {
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            color: var(--border-dark);
            opacity: 0.65;
            margin-top: 1.25rem;
        }
        .hero-footer {
            font-weight: 800;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--border-dark);
            opacity: 0.5;
            position: relative;
            z-index: 2;
        }
        /* ── RIGHT FORM PANEL ── */
        .auth-right {
            flex: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: var(--bg-card, #fff);
        }
        .login-card { width: 100%; max-width: 400px; }
        .login-card h2 {
            font-size: 2.4rem;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--text-color, #000);
            border-bottom: 4px solid var(--border-dark);
            padding-bottom: 1rem;
            margin-bottom: 0.6rem;
        }
        .login-card .sub {
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted, #666);
            margin-bottom: 2.2rem;
        }
        .form-lbl {
            display: block;
            font-weight: 900;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-color, #000);
            margin-bottom: 0.4rem;
        }
        .b-input {
            display: block;
            width: 100%;
            border: 3px solid var(--border-dark);
            padding: 0.85rem 1rem;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 700;
            background: var(--bg-color, #f9fafb);
            color: var(--text-color, #000);
            outline: none;
            transition: box-shadow 0.1s;
        }
        .b-input:focus {
            box-shadow: 4px 4px 0 var(--border-dark);
            background: var(--bg-card, #fff);
        }
        .b-input option { background: var(--bg-card, #fff); color: var(--text-color, #000); }
        .fg { margin-bottom: 1.4rem; }
        .b-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            border: 3px solid var(--border-dark);
            background: var(--border-dark);
            color: var(--primary);
            font-family: inherit;
            font-size: 1rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            cursor: pointer;
            box-shadow: 5px 5px 0 var(--primary);
            transition: all 0.1s cubic-bezier(0,0,0,1);
            margin-top: 0.5rem;
        }
        .b-btn:hover { transform: translate(-2px, -2px); box-shadow: 7px 7px 0 var(--primary); }
        .b-btn:active { transform: translate(3px, 3px); box-shadow: 2px 2px 0 var(--primary); }
        .b-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none !important; }
        .err-box {
            background: var(--tertiary, #f43f5e);
            color: #fff;
            border: 3px solid var(--border-dark);
            padding: 0.75rem 1rem;
            font-weight: 800;
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
            display: none;
        }
        .auth-foot {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted, #555);
        }
        .auth-foot a {
            color: var(--text-color, #000);
            border-bottom: 2px solid var(--primary);
            text-decoration: none;
            font-weight: 900;
        }
        @media (max-width: 800px) {
            body { flex-direction: column; }
            .auth-left { flex: none; min-height: 220px; border-right: none; border-bottom: 4px solid var(--border-dark); padding: 2rem; }
            .auth-left .deco-circle, .auth-left .deco-square, .auth-left .deco-dot { display: none; }
            .hero-text h1 { font-size: 2.4rem; }
            .auth-right { flex: none; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="deco-circle"></div>
        <div class="deco-square"></div>
        <div class="deco-dot"></div>

        <div class="school-tag">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            <?php echo htmlspecialchars($schoolName); ?>
        </div>

        <div class="hero-text">
            <h1><?php echo htmlspecialchars($heroTitle ?: 'Student Portal'); ?></h1>
            <p>Access your academic dashboard &amp; portal</p>
        </div>

        <div class="hero-footer">Powered by SIS &bull; <?php echo date('Y'); ?></div>
    </div>

    <div class="auth-right">
        <div class="login-card">
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
                    <input type="text" id="username" class="b-input" required placeholder="e.g. john@school.edu" autocomplete="username">
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
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const p = new URLSearchParams(window.location.search);
        if (p.get('role')) {
            const s = document.getElementById('role');
            if (s.querySelector(`option[value="${p.get('role')}"]`)) s.value = p.get('role');
        }
        if (localStorage.getItem('token')) window.location.href = 'dashboard.php';
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
            const res  = await fetch(`${window.location.origin}/api/auth/login`, {
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
