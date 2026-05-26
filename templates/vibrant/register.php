<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$themePath  = !empty($schoolSite['theme_path'])  ? $schoolSite['theme_path']  : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography'])  ? $schoolSite['typography']  : 'Inter';
$schoolName = !empty($schoolSite['name'])        ? $schoolSite['name']        : 'SIS Academy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | <?php echo htmlspecialchars($schoolName); ?></title>
    <meta name="description" content="Request access to <?php echo htmlspecialchars($schoolName); ?> portal.">
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
        .auth-left {
            flex: 1.1;
            background: var(--secondary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            border-right: 4px solid var(--border-dark);
            position: relative;
            overflow: hidden;
        }
        .auth-left .deco-a {
            position: absolute;
            width: 280px; height: 280px;
            background: var(--primary);
            border: 4px solid var(--border-dark);
            bottom: -70px; left: -70px;
            transform: rotate(20deg);
        }
        .auth-left .deco-b {
            position: absolute;
            width: 160px; height: 160px;
            background: var(--tertiary);
            border: 4px solid var(--border-dark);
            border-radius: 50%;
            top: 2.5rem; right: 2rem;
        }
        .school-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--border-dark);
            color: var(--secondary);
            padding: 0.5rem 1.25rem;
            font-weight: 900;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            position: relative;
            z-index: 2;
        }
        .hero-text { position: relative; z-index: 2; }
        .hero-text h1 {
            font-size: clamp(2.5rem, 4.5vw, 4.5rem);
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1;
            color: var(--border-dark);
            letter-spacing: -0.02em;
        }
        .hero-text p {
            font-weight: 800;
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--border-dark);
            opacity: 0.65;
            margin-top: 1.25rem;
        }
        .info-chips {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            position: relative;
            z-index: 2;
        }
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--border-dark);
            color: var(--secondary);
            padding: 0.4rem 0.9rem;
            font-weight: 900;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: fit-content;
        }
        .auth-right {
            flex: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: var(--bg-card, #fff);
            overflow-y: auto;
        }
        .reg-card { width: 100%; max-width: 400px; }
        .reg-card h2 {
            font-size: 2.2rem;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--text-color, #000);
            border-bottom: 4px solid var(--border-dark);
            padding-bottom: 1rem;
            margin-bottom: 0.6rem;
        }
        .reg-card .sub {
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted, #666);
            margin-bottom: 2rem;
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
        .b-input:focus { box-shadow: 4px 4px 0 var(--border-dark); background: var(--bg-card, #fff); }
        .b-input option { background: var(--bg-card, #fff); color: var(--text-color, #000); }
        .fg { margin-bottom: 1.3rem; }
        .b-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            border: 3px solid var(--border-dark);
            background: var(--border-dark);
            color: var(--secondary);
            font-family: inherit;
            font-size: 1rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            box-shadow: 5px 5px 0 var(--secondary);
            transition: all 0.1s cubic-bezier(0,0,0,1);
            margin-top: 0.5rem;
        }
        .b-btn:hover { transform: translate(-2px,-2px); box-shadow: 7px 7px 0 var(--secondary); }
        .b-btn:active { transform: translate(3px,3px); box-shadow: 2px 2px 0 var(--secondary); }
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
        .ok-box {
            background: var(--primary, #facc15);
            color: var(--border-dark, #000);
            border: 3px solid var(--border-dark);
            padding: 0.75rem 1rem;
            font-weight: 900;
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
            border-bottom: 2px solid var(--secondary);
            text-decoration: none;
            font-weight: 900;
        }
        @media (max-width: 800px) {
            body { flex-direction: column; }
            .auth-left { flex: none; min-height: 200px; border-right: none; border-bottom: 4px solid var(--border-dark); padding: 2rem; }
            .auth-left .deco-a, .auth-left .deco-b { display: none; }
            .info-chips { flex-direction: row; flex-wrap: wrap; }
            .hero-text h1 { font-size: 2.2rem; }
            .auth-right { flex: none; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="deco-a"></div>
        <div class="deco-b"></div>

        <div class="school-tag">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            <?php echo htmlspecialchars($schoolName); ?>
        </div>

        <div class="hero-text">
            <h1>Join the Academy</h1>
            <p>Create your institutional account</p>
        </div>

        <div class="info-chips">
            <div class="chip">&#10003; Student &amp; Faculty Access</div>
            <div class="chip">&#10003; Parent Portal Available</div>
            <div class="chip">&#10003; Secure &amp; Encrypted</div>
        </div>
    </div>

    <div class="auth-right">
        <div class="reg-card">
            <h2>Register</h2>
            <p class="sub">Fill in your details to request access</p>

            <div class="err-box" id="errBox"></div>
            <div class="ok-box"  id="okBox"></div>

            <form onsubmit="handleRegister(event)" autocomplete="on">
                <div class="fg">
                    <label class="form-lbl" for="fullName">Full Name</label>
                    <input type="text" id="fullName" class="b-input" required placeholder="e.g. hayle girma" autocomplete="name">
                </div>
                <div class="fg">
                    <label class="form-lbl" for="email">Email Address</label>
                    <input type="email" id="email" class="b-input" required placeholder="hayle@school.edu" autocomplete="email">
                </div>
                <div class="fg">
                    <label class="form-lbl" for="role">I Am A...</label>
                    <select id="role" class="b-input" style="height:auto;">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher / Faculty</option>
                        <option value="parent">Parent / Guardian</option>
                    </select>
                </div>
                <div class="fg">
                    <label class="form-lbl" for="password">Password</label>
                    <input type="password" id="password" class="b-input" required placeholder="Min. 6 characters" autocomplete="new-password">
                </div>
                <button type="submit" class="b-btn" id="regBtn">Create Account &rarr;</button>
            </form>

            <p class="auth-foot">
                Already registered? <a href="login.php">Sign In</a>
            </p>
        </div>
    </div>

<script>
    async function handleRegister(e) {
        e.preventDefault();
        const full_name = document.getElementById('fullName').value.trim();
        const email     = document.getElementById('email').value.trim();
        const role      = document.getElementById('role').value;
        const password  = document.getElementById('password').value;
        const btn       = document.getElementById('regBtn');
        const err       = document.getElementById('errBox');
        const ok        = document.getElementById('okBox');

        err.style.display = 'none';
        ok.style.display  = 'none';
        btn.disabled      = true;
        btn.textContent   = 'CREATING ACCOUNT...';

        try {
            const res  = await fetch(`${window.location.origin}/api/auth/register`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ full_name, email, password, role })
            });
            const data = await res.json();

            if (data.success) {
                ok.textContent   = data.message || 'Account created! You can now sign in.';
                ok.style.display = 'block';
                e.target.reset();
                setTimeout(() => window.location.href = `login.php?role=${role}`, 2500);
            } else {
                err.textContent   = data.message || 'Registration failed. Please try again.';
                err.style.display = 'block';
            }
        } catch (ex) {
            err.textContent   = 'Connection error. Please try again.';
            err.style.display = 'block';
        } finally {
            btn.disabled    = false;
            btn.textContent = 'Create Account →';
        }
    }
</script>
</body>
</html>

