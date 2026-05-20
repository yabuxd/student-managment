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
            background-color: var(--bg-color, #0b0f19);
            color: var(--text-color, #fff);
            margin: 0;
            min-height: 100vh;
        }
        .enterprise-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 5%;
            background: rgba(11, 15, 25, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .enterprise-btn {
            background: var(--primary);
            color: #fff;
            padding: 0.5rem 1.25rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .enterprise-btn:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }
        .enterprise-btn-outline {
            background: transparent;
            color: #fff;
            padding: 0.5rem 1.25rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.3s;
            cursor: pointer;
        }
        .enterprise-btn-outline:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.3);
        }
        .bento-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1.5rem;
            padding: 2rem;
            transition: border-color 0.3s, transform 0.3s;
        }
        .bento-card:hover {
            border-color: rgba(255,255,255,0.12);
        }
    </style>
</head>
<body>
    <nav class="enterprise-nav">
        <a href="#" style="color: #fff; text-decoration: none; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polygon points="12 2 2 7 12 12 22 7 12 2"/></svg>
            </div>
            Dashboard
        </a>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end;">
                <span id="userNameDisplay" style="font-weight: 600; font-size: 0.9rem;">User Name</span>
                <span id="roleBadge" style="font-size: 0.7rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1rem; font-weight: 700; margin-top: 0.25rem;">Role</span>
            </div>
            <div style="height: 32px; width: 1px; background: rgba(255,255,255,0.15);"></div>
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
                    <div style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <h2 style="margin: 0 0 0.5rem; font-size: 2.5rem; font-weight: 800; letter-spacing: -0.03em;">Academic Space</h2>
                            <p style="margin: 0; color: rgba(255,255,255,0.45); font-size: 1.1rem;">Section 10-A • Student Ledger</p>
                        </div>
                        <button class="enterprise-btn" onclick="alert('Roster Compiled!')">Download Transcript</button>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="bento-card" style="display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.45);">Cumulative GPA</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">3.85<span style="font-size: 1.25rem; color: rgba(255,255,255,0.3); font-weight: 500;">/4.0</span></div>
                        </div>
                        <div class="bento-card" style="background: linear-gradient(135deg, rgba(255,255,255,0.03), transparent); border-color: var(--primary);">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);">Next Exam</h3>
                            <div style="font-size: 1.75rem; font-weight: 600; color: #fff; margin-bottom: 1rem;">Calculus II Final</div>
                            <div style="display: inline-block; padding: 0.25rem 0.75rem; background: rgba(255,255,255,0.08); border-radius: 100px; font-size: 0.85rem;">Oct 12 • 09:00 AM</div>
                        </div>
                        <div class="bento-card" style="display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.45);">Active Courses</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">6</div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600; letter-spacing:-0.02em;">Evaluation Log</h3>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <div>
                                    <div style="font-weight: 600;">Mathematics</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.45);">Midterm Exam</div>
                                </div>
                                <div style="font-weight: 700; color: var(--primary);">85%</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0;">
                                <div>
                                    <div style="font-weight: 600;">Physics</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.45);">Lab Report</div>
                                </div>
                                <div style="font-weight: 700; color: var(--primary);">92%</div>
                            </div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600; letter-spacing:-0.02em;">Quick Links</h3>
                            <button style="width: 100%; padding: 1rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: #fff; border-radius: 12px; margin-bottom: 0.75rem; cursor: pointer; text-align: left; font-weight: 600; transition: background 0.2s;" onclick="alert('Compiled!')">Academic Calendar</button>
                            <button style="width: 100%; padding: 1rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: #fff; border-radius: 12px; cursor: pointer; text-align: left; font-weight: 600; transition: background 0.2s;" onclick="alert('Calling advisor...')">Contact Advisory</button>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <h2 style="margin: 0 0 0.5rem; font-size: 2.5rem; font-weight: 800; letter-spacing: -0.03em;">Faculty Operating Space</h2>
                            <p style="margin: 0; color: rgba(255,255,255,0.45); font-size: 1.1rem;">Manage syllabus, grading checklists, and rosters</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.45);">Assigned Sections</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">4</div>
                        </div>
                        <div class="bento-card" style="border-color: var(--primary);">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);">Grading Checklist</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 1rem;">15</div>
                            <button class="enterprise-btn" onclick="alert('Grading Ledger loaded!')">Review Checklist</button>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <div style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <h2 style="margin: 0 0 0.5rem; font-size: 2.5rem; font-weight: 800; letter-spacing: -0.03em;">Parent Guardian Space</h2>
                            <p style="margin: 0; color: rgba(255,255,255,0.45); font-size: 1.1rem;">Monitor academic progress and schedules</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600; letter-spacing:-0.02em;">Student Roster</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem; background: rgba(255,255,255,0.03); border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
                                <div>
                                    <div style="font-weight: 600; font-size: 1.1rem;">Alex Doe</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.45);">Sophomore • Section 10-A</div>
                                </div>
                                <button class="enterprise-btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem;" onclick="alert('Roster detail loaded!')">View Details</button>
                            </div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600; letter-spacing:-0.02em;">Outstanding Tuition</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary);">$0.00</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.45); margin-top: 0.25rem;">Fully Resolved</div>
                                </div>
                                <button class="enterprise-btn" onclick="alert('SaaS Stripe/Gateway loading...')">Ledger History</button>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'director') {
                contentArea.innerHTML = `
                    <div style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <h2 style="margin: 0 0 0.5rem; font-size: 2.5rem; font-weight: 800; letter-spacing: -0.03em;">Command Console</h2>
                            <p style="margin: 0; color: rgba(255,255,255,0.45); font-size: 1.1rem;">School Metrics and Visual Builder Hub</p>
                        </div>
                        <a href="/dashboard.html" class="enterprise-btn">Launch Builder Workspace</a>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.45);">Total Pupils Enrolled</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">480</div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.45);">Faculty Roster</h3>
                            <div style="font-size: 3.5rem; font-weight: 800; color: #fff; line-height: 1;">32</div>
                        </div>
                        <div class="bento-card" style="border-color: var(--primary);">
                            <h3 style="margin: 0 0 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);">Curriculum Stage</h3>
                            <div style="font-size: 1.8rem; font-weight: 800; color: #fff; margin-top: 0.5rem; text-transform: uppercase;">Fall Term</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600; letter-spacing:-0.02em;">Tenant Settings</h3>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <span>Dynamic Page Builder Hook</span>
                                <span style="font-weight: 700; color: var(--primary);">CONNECTED</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                <span>School Subdomain ID</span>
                                <span style="font-weight: 700; color: rgba(255,255,255,0.6);">Active Directory</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0;">
                                <span>CSV Auto Import Sync</span>
                                <span style="font-weight: 700; color: var(--primary);">ENABLED</span>
                            </div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin: 0 0 1.5rem; font-size: 1.25rem; font-weight: 600; letter-spacing:-0.02em;">Platform Utility</h3>
                            <button style="width: 100%; padding: 1rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: #fff; border-radius: 12px; margin-bottom: 0.75rem; cursor: pointer; text-align: left; font-weight: 600; transition: background 0.2s;" onclick="location.href='/dashboard.html'">Adjust Site Theme</button>
                            <button style="width: 100%; padding: 1rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: #fff; border-radius: 12px; cursor: pointer; text-align: left; font-weight: 600; transition: background 0.2s;" onclick="alert('Platform Config Resolved!')">System Health Check</button>
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
