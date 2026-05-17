<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'vibrant';
$primaryColor = !empty($schoolSite['primary_color']) ? $schoolSite['primary_color'] : '#000000';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SIS</title>
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <style>
        :root {
            --border-dark: <?php echo htmlspecialchars($primaryColor); ?>;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <h2 style="margin:0; background: var(--accent-3); padding: 0.5rem 1rem; border: var(--border-width) solid var(--border-color);">DASHBOARD</h2>
            <div class="nav-links" style="display: flex; align-items: center;">
                <span id="userNameDisplay" style="font-weight: 700; text-transform: uppercase;">USER</span>
                <span id="roleBadge" class="badge" style="margin-left: 1rem;">ROLE</span>
                <a href="#" onclick="logout()" style="margin-left: 2rem; color: var(--accent-1);">LOGOUT X</a>
            </div>
        </nav>

        <div id="dashboardContent"></div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const role = localStorage.getItem('user_role') || 'student';
            const name = localStorage.getItem('user_name') || 'Guest';

            document.getElementById('userNameDisplay').textContent = name;
            document.getElementById('roleBadge').textContent = role;

            renderDashboard(role);
        });

        function renderDashboard(role) {
            const contentArea = document.getElementById('dashboardContent');
            
            if (role === 'student') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                        <div>
                            <h1 style="font-size: 3rem; margin-bottom: 0;">MY STUFF.</h1>
                            <p style="font-weight: 700; font-size: 1.25rem; margin-top: 0.5rem; background: var(--accent-3); padding: 0.2rem 0.5rem; display: inline-block; border: 2px solid #000;">GRADE 10 - SECTION A</p>
                        </div>
                    </div>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: var(--accent-2);">
                            <h2 style="font-size: 4rem; margin: 0;">3.8</h2>
                            <p style="font-weight: 700; font-size: 1.5rem; text-transform: uppercase; margin: 0;">GPA</p>
                        </div>
                        <div class="brutal-card">
                            <h2 style="margin: 0; font-size: 2rem;">NEXT UP</h2>
                            <p style="font-weight: 600; font-size: 1.25rem;">MATH LAB - 10:00 AM</p>
                        </div>
                        <div class="brutal-card" style="background: var(--accent-1); color: #fff;">
                            <h2 style="margin: 0; font-size: 2rem;">WARNING</h2>
                            <p style="font-weight: 600; font-size: 1.25rem;">2 ASSIGNMENTS DUE!</p>
                        </div>
                    </div>
                    
                    <h2 style="font-size: 2.5rem; margin-top: 3rem; margin-bottom: 1.5rem; border-bottom: 4px solid #000; display: inline-block;">MY SUBJECTS & GRADES</h2>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: #fff;">
                            <h3 style="font-size: 1.5rem; margin: 0;">MATHEMATICS</h3>
                            <p style="font-weight: 600; color: var(--accent-3);">Mr. Abebe</p>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 2px dashed #000;">
                                <p style="margin: 0; font-weight: 700;">Midterm Exam: <span style="color: #10b981;">85/100</span></p>
                                <p style="margin: 0; font-weight: 700;">Assignment 1: <span style="color: var(--accent-1);">Pending</span></p>
                            </div>
                        </div>
                        <div class="brutal-card" style="background: #fff;">
                            <h3 style="font-size: 1.5rem; margin: 0;">PHYSICS</h3>
                            <p style="font-weight: 600; color: var(--accent-3);">Ms. Sara</p>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 2px dashed #000;">
                                <p style="margin: 0; font-weight: 700;">Lab Report: <span style="color: #10b981;">92/100</span></p>
                                <p style="margin: 0; font-weight: 700;">Quiz 1: <span style="color: #10b981;">18/20</span></p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <h1 style="font-size: 3rem; margin-bottom: 2rem;">COMMAND CENTER.</h1>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: var(--accent-3);">
                            <h2 style="font-size: 3rem; margin: 0;">4</h2>
                            <p style="font-weight: 700; font-size: 1.5rem; text-transform: uppercase; margin: 0;">CLASSES</p>
                        </div>
                        <div class="brutal-card">
                            <h2 style="margin: 0; font-size: 2rem;">NEEDS GRADING</h2>
                            <p style="font-weight: 600; font-size: 1.25rem;">15 PAPERS WAITING...</p>
                            <button class="btn btn-primary" style="margin-top: 1rem; padding: 0.5rem 1rem;">GRADE NOW</button>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <h1 style="font-size: 3rem; margin-bottom: 2rem;">THE KIDS.</h1>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: var(--accent-2);">
                            <h2>ALEX DOE</h2>
                            <p style="font-weight: 600; font-size: 1.25rem;">GRADE 10 - ACTIVE</p>
                            <button class="btn" style="margin-top: 1rem; width: 100%;">REPORT CARD</button>
                        </div>
                        <div class="brutal-card">
                            <h2>ALERTS</h2>
                            <p style="font-weight: 600; color: var(--accent-1);">LATE TO 1ST PERIOD (TODAY)</p>
                        </div>
                    </div>
                `;
            }
        }

        function logout() {
            localStorage.removeItem('user_role');
            localStorage.removeItem('user_name');
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>
