<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'academic';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Playfair Display';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Enroll in our institution.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions | Institution Portal</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
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
            padding: 2rem 0;
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
            max-width: 500px;
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
        }
        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: none !important;
            outline: none;
            background-color: #fff !important;
        }
    </style>
</head>
<body>
    <div class="auth-container" style="width: 100%; display: flex; justify-content: center; padding: 2rem;">
        <div class="academic-card">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                <h2 style="margin: 1.5rem 0 0.5rem; font-size: 2rem; color: #2c3e50;">New Enrollment</h2>
                <p style="color: #546e7a; font-size: 1rem; margin: 0; font-style: italic;">Register for an institutional account.</p>
            </div>
            
            <form onsubmit="handleRegister(event)" style="text-align: left;">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="color: #2c3e50; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Legal Full Name</label>
                    <input type="text" class="form-control" required placeholder="First Last">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="color: #2c3e50; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Personal Email</label>
                    <input type="email" class="form-control" required placeholder="name@domain.com">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="color: #2c3e50; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Account Type</label>
                    <select class="form-control" id="roleSelect">
                        <option value="student">Prospective Student</option>
                        <option value="teacher">Faculty Member</option>
                        <option value="parent">Parent / Guardian</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="color: #2c3e50; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Create Password</label>
                    <input type="password" class="form-control" required placeholder="Min. 8 characters">
                </div>
                <button type="submit" class="academic-btn">Submit Application</button>
            </form>
            <p style="text-align: center; margin-top: 2.5rem; color: #546e7a; font-size: 0.95rem; font-style: italic;">
                Already enrolled? <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: none; border-bottom: 1px solid var(--primary);">Sign in here</a>
            </p>
        </div>
    </div>

    <script>
        function handleRegister(e) {
            e.preventDefault();
            const role = document.getElementById('roleSelect').value;
            localStorage.setItem('user_role', role);
            localStorage.setItem('user_name', 'New Applicant');
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
