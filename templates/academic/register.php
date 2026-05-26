<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$themePath  = !empty($schoolSite['theme_path'])  ? $schoolSite['theme_path']  : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography'])  ? $schoolSite['typography']  : 'Playfair Display';
$schoolName = !empty($schoolSite['name'])        ? $schoolSite['name']        : 'SIS Academy';
$heroTitle  = !empty($schoolSite['hero_title'])  ? $schoolSite['hero_title']  : 'Admissions';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | <?php echo htmlspecialchars($schoolName); ?></title>
    <meta name="description" content="Register to access <?php echo htmlspecialchars($schoolName); ?> portal.">
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
        .login-card { width: 100%; max-width: 420px; }
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
        
        .ok-box {
            background: rgba(46,204,113,0.08);
            border: 1.5px solid #2ecc71;
            border-radius: 6px;
            color: #27ae60;
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
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
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
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                </div>
                <h2>Register</h2>
                <p>Submit your application to enroll</p>
            </div>

            <div class="err-box" id="errBox"></div>
            <div class="ok-box" id="okBox"></div>

            <form onsubmit="handleRegister(event)" autocomplete="on">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.4rem;">
                    <div>
                        <label class="form-lbl" for="firstName">First Name</label>
                        <input type="text" id="firstName" class="a-input" required placeholder="hayle" autocomplete="given-name">
                    </div>
                    <div>
                        <label class="form-lbl" for="lastName">Last Name</label>
                        <input type="text" id="lastName" class="a-input" required placeholder="girma" autocomplete="family-name">
                    </div>
                </div>
                <div class="fg">
                    <label class="form-lbl" for="email">Institution Email</label>
                    <input type="email" id="email" class="a-input" required placeholder="e.g. user@institution.edu" autocomplete="email">
                </div>
                <div class="fg">
                    <label class="form-lbl" for="role">Account Type</label>
                    <select id="role" class="a-input" style="height:auto;">
                        <option value="student">Student Portal</option>
                        <option value="teacher">Faculty / Teacher Portal</option>
                        <option value="parent">Parent / Guardian Portal</option>
                    </select>
                </div>
                <div class="fg">
                    <label class="form-lbl" for="password">Password</label>
                    <input type="password" id="password" class="a-input" required placeholder="Create a strong password" autocomplete="new-password">
                </div>
                <button type="submit" class="a-btn" id="regBtn">Submit Application &rarr;</button>
            </form>

            <p class="auth-foot" style="margin-top: 2rem;">
                Already have an account? <a href="login.php">Secure Login</a>
            </p>
        </div>
    </div>

<script>
    async function handleRegister(e) {
        e.preventDefault();
        const first = document.getElementById('firstName').value.trim();
        const last  = document.getElementById('lastName').value.trim();
        const full_name = first + ' ' + last;
        const email     = document.getElementById('email').value.trim();
        const role      = document.getElementById('role').value;
        const password  = document.getElementById('password').value;
        const btn       = document.getElementById('regBtn');
        const err       = document.getElementById('errBox');
        const ok        = document.getElementById('okBox');

        err.style.display = 'none';
        ok.style.display  = 'none';
        btn.disabled = true;
        btn.textContent = 'Submitting...';

        try {
            const res  = await fetch(`${window.location.origin}/api/auth/register`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ full_name, email, password, role })
            });
            const data = await res.json();

            if (data.success) {
                ok.textContent   = data.message || 'Registration successful! Redirecting to login...';
                ok.style.display = 'block';
                e.target.reset();
                setTimeout(() => window.location.href = `login.php?role=${role}`, 2500);
            } else {
                err.textContent   = data.message || 'Registration failed. Please check your inputs.';
                err.style.display = 'block';
            }
        } catch (ex) {
            err.textContent   = 'Connection error. Please try again.';
            err.style.display = 'block';
        } finally {
            btn.disabled    = false;
            btn.textContent = 'Submit Application →';
        }
    }
</script>
</body>
</html>

