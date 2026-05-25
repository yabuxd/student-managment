<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$themePath  = !empty($schoolSite['theme_path'])  ? $schoolSite['theme_path']  : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography'])  ? $schoolSite['typography']  : 'Playfair Display';
$schoolName = !empty($schoolSite['name'])        ? $schoolSite['name']        : 'SIS Academy';
$heroTitle  = !empty($schoolSite['hero_title'])  ? $schoolSite['hero_title']  : 'Secure Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo htmlspecialchars($schoolName); ?></title>
    <meta name="description" content="Authenticate to access <?php echo htmlspecialchars($schoolName); ?> portal.">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <style>
        :root {
            --primary:   var(--accent-1, #0f4c81);
            --secondary: var(--accent-2, #2176ae);
            --tertiary:  var(--accent-3, #c0392b);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-color, #f7f6f0);
            color: var(--text-color, #2c3e50);
            min-height: 100vh;
            display: flex;
        }

        /* Left decorative panel */
        .auth-left {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 4rem;
            overflow: hidden;
            background: var(--bg-card, #fff);
            border-right: 1px solid var(--border-color, rgba(0,0,0,0.08));
        }
        .auth-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg, var(--primary) 0%, var(--secondary) 60%, var(--tertiary) 100%);
            opacity: 0.92;
        }
        .auth-left-content { position: relative; z-index: 1; color: #fff; }
        .seal {
            width: 72px; height: 72px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2.5rem;
        }
        .auth-left h1 {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            font-size: clamp(2.2rem, 4vw, 3.5rem);
            font-weight: 700;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 1rem;
        }
        .auth-left p {
            font-size: 1rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.7;
            max-width: 400px;
            margin-bottom: 3rem;
        }
        .auth-dividers {
            display: flex;
            gap: 0.5rem;
        }
        .auth-dividers span {
            height: 3px;
            border-radius: 2px;
            background: rgba(255,255,255,0.4);
        }
        .auth-dividers span:first-child { width: 48px; background: #fff; }
        .auth-dividers span:nth-child(2) { width: 24px; }
        .auth-dividers span:nth-child(3) { width: 16px; }
        .left-footer {
            position: absolute;
            bottom: 2.5rem; left: 4rem;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.45);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            z-index: 1;
        }

        /* Right form panel */
        .auth-right {
            flex: 0.85;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            background: var(--bg-card, #fff);
            overflow-y: auto;
        }
        .login-card { width: 100%; max-width: 380px; }
        .card-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .card-icon {
            width: 52px; height: 52px;
            background: var(--primary);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }
        .card-header h2 {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            font-size: 1.85rem;
            font-weight: 700;
            color: var(--text-color, #2c3e50);
            margin-bottom: 0.4rem;
        }
        .card-header p {
            font-size: 0.9rem;
            color: var(--text-muted, #546e7a);
            font-style: italic;
        }

        .form-lbl {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted, #546e7a);
            margin-bottom: 0.45rem;
        }
        .a-input {
            display: block;
            width: 100%;
            border: 1.5px solid var(--border-color, #d0d7de);
            border-radius: 6px;
            padding: 0.85rem 1rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            background: var(--bg-color, #fafaf8);
            color: var(--text-color, #2c3e50);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .a-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,76,129,0.08);
            background: var(--bg-card, #fff);
        }
        .a-input option { background: var(--bg-card, #fff); color: var(--text-color, #2c3e50); }
        .fg { margin-bottom: 1.4rem; }

        .a-btn {
            display: block;
            width: 100%;
            padding: 0.95rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        .a-btn:hover { background: var(--secondary); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.15); }
        .a-btn:active { transform: translateY(0); }
        .a-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

        .err-box {
            background: rgba(192,57,43,0.08);
            border: 1.5px solid var(--tertiary, #c0392b);
            border-radius: 6px;
            color: var(--tertiary, #c0392b);
            padding: 0.75rem 1rem;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: none;
        }
        .auth-foot {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.875rem;
            color: var(--text-muted, #546e7a);
            font-style: italic;
        }
        .auth-foot a {
            color: var(--primary);
            font-weight: 600;
            font-style: normal;
            text-decoration: none;
            border-bottom: 1px solid var(--primary);
        }
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
            color: var(--text-muted, #90a4ae);
            font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color, #e0e0e0);
        }

        @media (max-width: 820px) {
            body { flex-direction: column; }
            .auth-left { flex: none; min-height: 240px; padding: 2.5rem; border-right: none; border-bottom: 1px solid var(--border-color, rgba(0,0,0,0.08)); }
            .auth-left h1 { font-size: 2rem; }
            .auth-left p { display: none; }
            .auth-dividers { display: none; }
            .left-footer { display: none; }
            .auth-right { flex: none; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="auth-left-content">
            <div class="seal">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <h1><?php echo htmlspecialchars($heroTitle ?: 'Scholar Portal'); ?></h1>
            <p><?php echo htmlspecialchars($schoolName); ?> — Your gateway to academic records, progress tracking, and institutional communications.</p>
            <div class="auth-dividers">
                <span></span><span></span><span></span>
            </div>
        </div>
        <div class="left-footer"><?php echo htmlspecialchars($schoolName); ?> &bull; Powered by SIS</div>
    </div>

    <div class="auth-right">
        <div class="login-card">
            <div class="card-header">
                <div class="card-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h2>Secure Login</h2>
                <p>Please authenticate to access the portal</p>
            </div>

            <div class="err-box" id="errBox"></div>

            <form onsubmit="handleLogin(event)" autocomplete="on">
                <div class="fg">
                    <label class="form-lbl" for="role">Select Your Gateway</label>
                    <select id="role" class="a-input" style="height:auto;">
                        <option value="student">Student Portal</option>
                        <option value="teacher">Faculty / Teacher Portal</option>
                        <option value="parent">Parent / Guardian Portal</option>
                        <option value="director">Director Console</option>
                    </select>
                </div>
                <div class="fg">
                    <label class="form-lbl" for="username">Institution Email or ID</label>
                    <input type="text" id="username" class="a-input" required placeholder="e.g. user@institution.edu" autocomplete="username">
                </div>
                <div class="fg">
                    <label class="form-lbl" for="password">Password</label>
                    <input type="password" id="password" class="a-input" required placeholder="Enter your password" autocomplete="current-password">
                </div>
                <button type="submit" class="a-btn" id="loginBtn">Authenticate &rarr;</button>
            </form>

            <p class="auth-foot" style="margin-top: 2rem;">
                New admission? <a href="register.php">Enroll here</a>
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
        btn.textContent = 'Authenticating...';

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
                err.textContent   = data.message || 'Authentication failed. Please check your credentials.';
                err.style.display = 'block';
            }
        } catch (ex) {
            err.textContent   = 'Connection error. Please try again.';
            err.style.display = 'block';
        } finally {
            btn.disabled    = false;
            btn.textContent = 'Authenticate →';
        }
    }
</script>
</body>
</html>
