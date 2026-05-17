<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'minimalist';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Dashboard portal.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SIS</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background: #fbfbfd;
            color: #1d1d1f;
        }
        .minimal-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .minimal-glass:hover {
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .minimal-btn {
            background: #0071e3;
            color: #fff;
            border-radius: 980px;
            padding: 0.8rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .minimal-btn:hover {
            background: #0077ed;
            transform: scale(1.02);
        }
        .minimal-btn-outline {
            background: transparent;
            color: #1d1d1f;
            border: 1px solid #d2d2d7;
            border-radius: 980px;
            padding: 0.8rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .minimal-btn-outline:hover {
            background: #f5f5f7;
        }
        .stat-card {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .stat-icon {
            font-size: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <nav style="padding: 1rem 5%; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border-bottom: 1px solid #e5e5ea; position: sticky; top: 0; z-index: 10; margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: center;">
        <a href="#" class="nav-brand" style="text-decoration: none; color: #1d1d1f; font-weight: 600; font-size: 1.25rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0071e3" stroke-width="2" style="vertical-align: middle; margin-right: 0.5rem;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            NexEdu
        </a>
        <div class="nav-links" style="display: flex; gap: 1.5rem; align-items: center;">
            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                <span id="userNameDisplay" style="color: #1d1d1f; font-weight: 600; font-size: 0.95rem;">Loading...</span>
                <span id="roleBadge" style="color: #0071e3; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Role</span>
            </div>
            <div style="width: 1px; height: 30px; background: #e5e5ea;"></div>
            <a href="#" onclick="logout()" class="minimal-btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Sign Out</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
        <!-- Dynamic Content Area -->
        <div id="dashboardContent">
            <!-- Rendered by JS -->
        </div>
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
                            <h2 style="font-size: 2rem; margin-bottom: 0.25rem; font-weight: 600; letter-spacing: -0.02em;">Academic Overview</h2>
                            <p style="color: #0071e3; margin: 0; font-weight: 500;">Grade 10 - Section A</p>
                        </div>
                        <button class="minimal-btn">Download Report</button>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">📈</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.85rem; color: #86868b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Current GPA</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #1d1d1f; letter-spacing: -0.02em;">3.8<span style="font-size: 1rem; color: #86868b; font-weight: 500;">/4.0</span></p>
                            </div>
                        </div>
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">📚</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.85rem; color: #86868b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Active Courses</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #1d1d1f; letter-spacing: -0.02em;">6</p>
                            </div>
                        </div>
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">⚠️</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.85rem; color: #86868b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Due Assignments</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #1d1d1f; letter-spacing: -0.02em;">2</p>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 2rem;">
                        <div class="minimal-glass" style="padding: 1.5rem;">
                            <h3 style="margin-top: 0; border-bottom: 1px solid #e5e5ea; padding-bottom: 0.75rem; color: #1d1d1f; font-weight: 600;">Subjects & Teachers</h3>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="padding: 1rem 0; border-bottom: 1px solid #f5f5f7; display: flex; justify-content: space-between;">
                                    <span style="color: #1d1d1f; font-weight: 500;">Mathematics</span> <span style="color: #86868b;">Mr. Abebe</span>
                                </li>
                                <li style="padding: 1rem 0; border-bottom: 1px solid #f5f5f7; display: flex; justify-content: space-between;">
                                    <span style="color: #1d1d1f; font-weight: 500;">Physics</span> <span style="color: #86868b;">Ms. Sara</span>
                                </li>
                                <li style="padding: 1rem 0; display: flex; justify-content: space-between;">
                                    <span style="color: #1d1d1f; font-weight: 500;">English</span> <span style="color: #86868b;">Mr. Dawit</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="minimal-glass" style="padding: 0; overflow: hidden;">
                            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid #e5e5ea; background: rgba(255,255,255,0.5);">
                                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Recent Grades</h3>
                            </div>
                            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid #e5e5ea; color: #86868b; font-size: 0.85rem; text-transform: uppercase; background: rgba(255,255,255,0.2);">
                                    <th style="padding: 1rem 2rem; font-weight: 600;">Course</th>
                                    <th style="padding: 1rem 2rem; font-weight: 600;">Assignment</th>
                                    <th style="padding: 1rem 2rem; font-weight: 600;">Grade</th>
                                </tr>
                                <tr style="border-bottom: 1px solid #f5f5f7;">
                                    <td style="padding: 1.25rem 2rem; font-weight: 500; color: #1d1d1f;">Mathematics</td>
                                    <td style="padding: 1.25rem 2rem; color: #86868b;">Midterm Exam</td>
                                    <td style="padding: 1.25rem 2rem; color: #34c759; font-weight: 600;">85/100</td>
                                </tr>
                                <tr>
                                    <td style="padding: 1.25rem 2rem; font-weight: 500; color: #1d1d1f;">Physics</td>
                                    <td style="padding: 1.25rem 2rem; color: #86868b;">Lab Report</td>
                                    <td style="padding: 1.25rem 2rem; color: #34c759; font-weight: 600;">92/100</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                        <div>
                            <h2 style="font-size: 2rem; margin-bottom: 0.25rem; font-weight: 600; letter-spacing: -0.02em;">Faculty Hub</h2>
                            <p style="color: #86868b; margin: 0;">Manage your classes and assignments</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">📝</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.85rem; color: #86868b; text-transform: uppercase; font-weight: 600;">To Grade</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #1d1d1f; letter-spacing: -0.02em;">24</p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <h2 style="font-size: 2rem; margin-bottom: 2rem; font-weight: 600; letter-spacing: -0.02em;">Guardian Portal</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <div class="minimal-glass">
                            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-weight: 600;">Linked Students</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: #fff; border-radius: 1rem; border: 1px solid #e5e5ea; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                                <div>
                                    <h4 style="margin: 0 0 0.25rem; color: #1d1d1f; font-weight: 600;">Alex Doe</h4>
                                    <p style="margin: 0; font-size: 0.85rem; color: #0071e3; font-weight: 500;">Grade 10</p>
                                </div>
                                <button class="minimal-btn-outline" style="padding: 0.4rem 1rem; font-size: 0.85rem;">View Details</button>
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
