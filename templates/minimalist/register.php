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
            background: var(--bg-color, #fbfbfd);
            color: var(--text-color, #1d1d1f);
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
            border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.03);
            padding: 1rem 2rem;
            width: 100%;
            max-width: 480px;
        }
        .minimal-btn {
            background: var(--bg-color);
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
            color: #333;
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
        .err-box {
            background: rgba(244, 63, 94, 0.1);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.3);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
            display: none;
        }
        .ok-box {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="minimal-glass">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-bottom: 1rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; font-weight: 600; letter-spacing: -0.02em;">Create Account</h2>
                <p style="color: #86868b; font-size: 0.95rem;">Join the digital campus ecosystem</p>
            </div>
            
            <div class="err-box" id="errBox"></div>
            <div class="ok-box"  id="okBox"></div>

            <form onsubmit="handleRegister(event)" autocomplete="on">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" class="form-control" required placeholder="Alem" autocomplete="given-name">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" class="form-control" required placeholder="Kebede" autocomplete="family-name">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="email">Institution Email</label>
                    <input type="email" id="email" class="form-control" required placeholder="name@school.edu" autocomplete="email">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="role">Account Type</label>
                    <select id="role" class="form-control" style="height: auto;">
                        <option value="student">Student</option>
                        <option value="teacher">Faculty / Teacher</option>
                        <option value="parent">Parent / Guardian</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" required placeholder="••••••••" autocomplete="new-password">
                </div>
                <button type="submit" class="minimal-btn" id="regBtn">Complete Registration</button>
            </form>
            <p style="text-align: center; margin-top: 2rem; color: #86868b; font-size: 0.9rem;">
                Already registered? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">Sign in instead</a>
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
            btn.disabled      = true;
            btn.textContent   = 'Creating Account...';

            try {
                const res  = await fetch(`/api/auth/register`, {
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
                btn.textContent = 'Complete Registration';
            }
        }
    </script>
</body>
</html>
