<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'modern-enterprise';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Outfit';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Campus Dashboard.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | <?php echo htmlspecialchars($schoolSite['name'] ?? 'Institution'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            min-height: 100vh;
        }
        .enterprise-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 5%;
            background: #000;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .enterprise-btn-outline {
            background: transparent;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
            cursor: pointer;
        }
        .enterprise-btn-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
        }
        .bento-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1.5rem;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <nav class="enterprise-nav">
        <a href="#" style="color: #fff; text-decoration: none; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 6px;"></div>
            Dashboard
        </a>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end;">
                <span id="userNameDisplay" style="font-weight: 600; font-size: 0.9rem;">User Name</span>
                <span id="roleBadge" style="font-size: 0.7rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1em; background: rgba(var(--primary-rgb), 0.1); padding: 0.2rem 0.5rem; border-radius: 100px; margin-top: 0.25rem;">Role</span>
            </div>
            <div style="height: 32px; width: 1px; background: rgba(255,255,255,0.1);"></div>
            <a href="#" onclick="logout()" class="enterprise-btn-outline">Sign Out</a>
        </div>
    </nav>

    <div class="container" style="padding: 3rem 5%; max-width: 1400px; margin: 0 auto;">
        <div id="dashboardContent"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const role = localStorage.getItem('user_role') || 'student';
            const name = localStorage.getItem('user_name') || 'Guest User';

            document.getElementById('userNameDisplay').textContent = name;
            document.getElementById('roleBadge').textContent = role;

            renderDashboard(role);
        });

        function renderDashboard(role) {
            const contentArea = document.getElementById('dashboardContent');
            
            if (role === 'student') {
                contentArea.innerHTML = `
                    <div style="margin-bottom: 3rem;">
                        <h2 style="margin: 0 0 0.5rem; font-size: 2.5rem; font-weight: 700; letter-spacing: -0.02em;">Overview</h2>
                        <p style="margin: 0; color: rgba(255,255,255,0.5); font-size: 1.1rem;">Track your academic progress seamlessly.</p>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="bento-card" style="display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.5);">Cumulative GPA</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">3.85<span style="font-size: 1.25rem; color: rgba(255,255,255,0.3); font-weight: 500;">/4.0</span></div>
                        </div>
                        <div class="bento-card" style="background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.2), transparent); border-color: rgba(var(--primary-rgb), 0.3);">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);">Next Milestone</h3>
                            <div style="font-size: 1.75rem; font-weight: 600; color: #fff; margin-bottom: 1rem;">Calculus II Final</div>
                            <div style="display: inline-block; padding: 0.25rem 0.75rem; background: rgba(255,255,255,0.1); border-radius: 100px; font-size: 0.85rem;">Oct 12 • 09:00 AM</div>
                        </div>
                        <div class="bento-card" style="display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.5);">Active Courses</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">6</div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600;">Recent Evaluations</h3>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <div>
                                    <div style="font-weight: 600;">Mathematics</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.5);">Midterm Exam</div>
                                </div>
                                <div style="font-weight: 700; color: #4ade80;">85%</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0;">
                                <div>
                                    <div style="font-weight: 600;">Physics</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.5);">Lab Report</div>
                                </div>
                                <div style="font-weight: 700; color: #4ade80;">92%</div>
                            </div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600;">Quick Actions</h3>
                            <button style="width: 100%; padding: 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 12px; margin-bottom: 0.75rem; cursor: pointer; text-align: left; font-weight: 500; transition: background 0.2s;">Download Transcript</button>
                            <button style="width: 100%; padding: 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 12px; cursor: pointer; text-align: left; font-weight: 500; transition: background 0.2s;">Contact Advisor</button>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div style="margin-bottom: 3rem;">
                        <h2 style="margin: 0 0 0.5rem; font-size: 2.5rem; font-weight: 700; letter-spacing: -0.02em;">Faculty Space</h2>
                        <p style="margin: 0; color: rgba(255,255,255,0.5); font-size: 1.1rem;">Manage your classes and evaluations.</p>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.5);">Courses Taught</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">4</div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);">Pending Grading</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 1rem;">15</div>
                            <button style="padding: 0.5rem 1rem; background: var(--primary); border: none; color: #000; border-radius: 100px; font-weight: 600; cursor: pointer;">Review Now</button>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <div style="margin-bottom: 3rem;">
                        <h2 style="margin: 0 0 0.5rem; font-size: 2.5rem; font-weight: 700; letter-spacing: -0.02em;">Guardian Portal</h2>
                        <p style="margin: 0; color: rgba(255,255,255,0.5); font-size: 1.1rem;">Stay connected with your child's journey.</p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600;">Dependents</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                                <div>
                                    <div style="font-weight: 600; font-size: 1.1rem;">Alex Doe</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.5);">Grade 10</div>
                                </div>
                                <button style="padding: 0.5rem 1rem; background: #fff; border: none; color: #000; border-radius: 100px; font-weight: 600; cursor: pointer;">View Profile</button>
                            </div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600;">Announcements</h3>
                            <div style="padding-left: 1rem; border-left: 2px solid var(--primary); margin-bottom: 1rem;">
                                <div style="font-weight: 500; margin-bottom: 0.25rem;">Tuition Deadline</div>
                                <div style="font-size: 0.85rem; color: rgba(255,255,255,0.5);">Please submit Fall semester payments by Nov 1.</div>
                            </div>
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
