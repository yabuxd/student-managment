<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'academic';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Playfair Display';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Login to your institution portal.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login | Institution Portal</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            background-color: #fcfcfc;
            color: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-image: radial-gradient(#e0e0e0 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .academic-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 3.5rem 3rem;
            text-align: center;
            border-top: 5px solid var(--primary);
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 450px;
        }
        .academic-btn {
            background-color: var(--primary);
            color: #fff;
            padding: 0.85rem 2rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background-color 0.3s;
            border: 1px solid var(--primary);
            width: 100%;
            cursor: pointer;
            margin-top: 1.5rem;
        }
        .academic-btn:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .form-control {
            border: 1px solid #d0d7de !important;
            border-radius: 2px !important;
            padding: 0.85rem !important;
            font-family: 'Inter', sans-serif;
            background-color: #fafafa !important;
            transition: border-color 0.2s;
            width: 100%;
            box-sizing: border-box;
            display: block;
        }
        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: none !important;
            outline: none;
            background-color: #fff !important;
        }
        label {
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
    <div class="auth-container" style="width: 100%; display: flex; justify-content: center; padding: 2rem;">
        <div class="academic-card">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <h2 style="margin: 1.5rem 0 0.5rem; font-size: 2rem; color: #2c3e50;">Secure Login</h2>
                <p style="color: #546e7a; font-size: 1rem; margin: 0; font-style: italic;">Please authenticate to access the portal.</p>
            </div>
            
            <form onsubmit="handleLogin(event)" style="text-align: left;">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="role">Select Your Gateway</label>
                    <select id="role" class="form-control" style="height: auto;">
                        <option value="student">Student Portal</option>
                        <option value="teacher">Faculty / Teacher Portal</option>
                        <option value="parent">Parent / Guardian Portal</option>
                        <option value="director">Director Console</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="username">Institution Email or ID</label>
                    <input type="text" id="username" class="form-control" required placeholder="e.g. user@institution.edu">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" required placeholder="Enter password">
                </div>
                <button type="submit" class="academic-btn">Authenticate</button>
            </form>
            <p style="text-align: center; margin-top: 2.5rem; color: #546e7a; font-size: 0.95rem; font-style: italic;">
                New admission? <a href="register.php" style="color: var(--primary); font-weight: 600; text-decoration: none; border-bottom: 1px solid var(--primary);">Enroll here</a>
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
