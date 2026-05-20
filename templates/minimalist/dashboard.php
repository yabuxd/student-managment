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
            font-family: '<?php echo htmlspecialchars($typography); ?>', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bg-color, #fbfbfd);
            color: var(--text-color, #1d1d1f);
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }
        .minimal-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }
        .minimal-btn {
            background: var(--primary);
            color: #fff;
            border-radius: 980px;
            padding: 0.6rem 1.4rem;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            border: 1px solid transparent;
            cursor: pointer;
            font-family: inherit;
        }
        .minimal-btn:hover {
            opacity: 0.95;
            transform: scale(1.01);
        }
        .minimal-btn-outline {
            background: transparent;
            color: var(--text-color, #1d1d1f);
            border: 1px solid var(--border-color, #d2d2d7);
            border-radius: 980px;
            padding: 0.6rem 1.4rem;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
        }
        .minimal-btn-outline:hover {
            background: rgba(0,0,0,0.02);
        }
        .stat-card {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .stat-icon {
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <nav style="padding: 1rem 5%; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-color, #e5e5ea); position: sticky; top: 0; z-index: 100; margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: center;">
        <a href="#" class="nav-brand" style="text-decoration: none; color: inherit; font-weight: 600; font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            Dashboard
        </a>
        <div class="nav-links" style="display: flex; gap: 1.5rem; align-items: center;">
            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                <span id="userNameDisplay" style="font-weight: 600; font-size: 0.9rem;">Loading...</span>
                <span id="roleBadge" style="color: var(--primary); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Role</span>
            </div>
            <div style="width: 1px; height: 30px; background: var(--border-color, #e5e5ea);"></div>
            <a href="#" onclick="logout()" class="minimal-btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Sign Out</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1100px; margin: 0 auto; padding: 0 1.5rem;">
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
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
                        <div>
                            <h2 style="font-size: 2.2rem; margin: 0 0 0.25rem; font-weight: 600; letter-spacing: -0.03em;">Academic Roster</h2>
                            <p style="color: var(--primary); margin: 0; font-weight: 500; font-size: 0.95rem;">Grade 10 - Section A</p>
                        </div>
                        <button class="minimal-btn" onclick="alert('Roster compiled!')">Download Ledger</button>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">🎓</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: #86868b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Cumulative GPA</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; letter-spacing: -0.03em;">3.85<span style="font-size: 1rem; color: #86868b; font-weight: 400;">/4.0</span></p>
                            </div>
                        </div>
                        <div class="minimal-glass stat-card" style="border-color: var(--primary);">
                            <div class="stat-icon" style="color: var(--primary);">⏱️</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Calculus II Final</h3>
                                <p style="margin:0; font-size: 1.25rem; font-weight: 700; letter-spacing: -0.01em;">Oct 12 • 09:00 AM</p>
                            </div>
                        </div>
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">📚</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: #86868b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Active Courses</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; letter-spacing: -0.03em;">6</p>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div class="minimal-glass" style="padding: 2rem;">
                            <h3 style="margin-top: 0; border-bottom: 1px solid var(--border-color, #e5e5ea); padding-bottom: 0.75rem; font-weight: 600; font-size: 1.25rem;">Syllabus Directory</h3>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color, #f5f5f7); display: flex; justify-content: space-between;">
                                    <span style="font-weight: 500;">Mathematics</span> <span style="color: #86868b;">Mr. Abebe</span>
                                </li>
                                <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color, #f5f5f7); display: flex; justify-content: space-between;">
                                    <span style="font-weight: 500;">Physics</span> <span style="color: #86868b;">Ms. Sara</span>
                                </li>
                                <li style="padding: 1rem 0; display: flex; justify-content: space-between;">
                                    <span style="font-weight: 500;">English Literature</span> <span style="color: #86868b;">Mr. Dawit</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="minimal-glass" style="padding: 0; overflow: hidden;">
                            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--border-color, #e5e5ea);">
                                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Recent Evaluations</h3>
                            </div>
                            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid var(--border-color, #e5e5ea); color: #86868b; font-size: 0.8rem; text-transform: uppercase;">
                                    <th style="padding: 1rem 2rem; font-weight: 600;">Course</th>
                                    <th style="padding: 1rem 2rem; font-weight: 600;">Assessment</th>
                                    <th style="padding: 1rem 2rem; font-weight: 600;">Grade</th>
                                </tr>
                                <tr style="border-bottom: 1px solid var(--border-color, #f5f5f7);">
                                    <td style="padding: 1.25rem 2rem; font-weight: 500;">Mathematics</td>
                                    <td style="padding: 1.25rem 2rem; color: #86868b;">Midterm Exam</td>
                                    <td style="padding: 1.25rem 2rem; color: var(--primary); font-weight: 600;">85/100</td>
                                </tr>
                                <tr>
                                    <td style="padding: 1.25rem 2rem; font-weight: 500;">Physics</td>
                                    <td style="padding: 1.25rem 2rem; color: #86868b;">Lab Report</td>
                                    <td style="padding: 1.25rem 2rem; color: var(--primary); font-weight: 600;">92/100</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
                        <div>
                            <h2 style="font-size: 2.2rem; margin: 0 0 0.25rem; font-weight: 600; letter-spacing: -0.03em;">Faculty Operating Space</h2>
                            <p style="color: #86868b; margin: 0; font-size: 0.95rem;">Curriculum & grading command console</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">📋</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: #86868b; text-transform: uppercase; font-weight: 600;">Pending Grading</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; letter-spacing:-0.03em;">15 <span style="font-size:0.9rem; font-weight:400; color:#86868b;">Submissions</span></p>
                            </div>
                        </div>
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">🏫</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: #86868b; text-transform: uppercase; font-weight: 600;">Active Sections</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; letter-spacing:-0.03em;">4</p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
                        <div>
                            <h2 style="font-size: 2.2rem; margin: 0 0 0.25rem; font-weight: 600; letter-spacing: -0.03em;">Parent Guardian Space</h2>
                            <p style="color: #86868b; margin: 0; font-size: 0.95rem;">Monitor academic milestones and finance</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div class="minimal-glass" style="padding: 2rem;">
                            <h3 style="margin-top:0; border-bottom: 1px solid var(--border-color, #e5e5ea); padding-bottom: 0.75rem; font-weight: 600; font-size: 1.25rem;">Dependents</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0,0,0,0.01); border-radius: 1rem; border: 1px solid var(--border-color, #e5e5ea);">
                                <div>
                                    <h4 style="margin:0 0 0.25rem; font-weight: 600; color:#1d1d1f;">Alex Doe</h4>
                                    <p style="margin:0; font-size: 0.85rem; color: #86868b;">Grade 10 • Section A</p>
                                </div>
                                <button class="minimal-btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem;" onclick="alert(' Roster detail loaded!')">View Records</button>
                            </div>
                        </div>
                        <div class="minimal-glass" style="padding: 2rem;">
                            <h3 style="margin-top:0; border-bottom: 1px solid var(--border-color, #e5e5ea); padding-bottom: 0.75rem; font-weight: 600; font-size: 1.25rem;">Outstanding Balance</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                <div>
                                    <span style="font-size: 2.2rem; font-weight: 700; letter-spacing:-0.04em; color: var(--primary);">$0.00</span>
                                    <span style="display:block; font-size: 0.8rem; color:#86868b; margin-top:0.25rem;">Fully Resolved</span>
                                </div>
                                <button class="minimal-btn" onclick="alert('Loading payment history...')">Statements</button>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'director') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
                        <div>
                            <h2 style="font-size: 2.2rem; margin: 0 0 0.25rem; font-weight: 600; letter-spacing: -0.03em;">Command Console</h2>
                            <p style="color: #86868b; margin: 0; font-size: 0.95rem;">Global administration & layout configuration</p>
                        </div>
                        <a href="/dashboard.html" class="minimal-btn">Launch Builder Workspace</a>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">👥</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: #86868b; text-transform: uppercase; font-weight: 600;">Total Students</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; letter-spacing:-0.03em;">480</p>
                            </div>
                        </div>
                        <div class="minimal-glass stat-card">
                            <div class="stat-icon">👨‍🏫</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: #86868b; text-transform: uppercase; font-weight: 600;">Total Faculty</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; letter-spacing:-0.03em;">32</p>
                            </div>
                        </div>
                        <div class="minimal-glass stat-card" style="border-color: var(--primary);">
                            <div class="stat-icon" style="color: var(--primary);">🛠️</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.8rem; color: var(--primary); text-transform: uppercase; font-weight: 600;">Billing Tier</h3>
                                <p style="margin:0; font-size: 1.4rem; font-weight: 700; text-transform:uppercase;">Enterprise</p>
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                        <div class="minimal-glass" style="padding: 2rem;">
                            <h3 style="margin-top:0; border-bottom: 1px solid var(--border-color, #e5e5ea); padding-bottom: 0.75rem; font-weight: 600; font-size: 1.25rem;">Active Parameters</h3>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--border-color, #f5f5f7);">
                                <span>Academic Session</span>
                                <span style="font-weight: 600;">Fall Semester 2026</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid var(--border-color, #f5f5f7);">
                                <span>Theme Configuration ID</span>
                                <span style="font-weight: 600; color: var(--primary);">DYNAMIC ACTIVE</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 1rem 0;">
                                <span>CSV Auto Import Sync</span>
                                <span style="font-weight: 600; color: #34c759;">ONLINE</span>
                            </div>
                        </div>
                        
                        <div class="minimal-glass" style="padding: 2rem;">
                            <h3 style="margin-top:0; border-bottom: 1px solid var(--border-color, #e5e5ea); padding-bottom: 0.75rem; font-weight: 600; font-size: 1.25rem;">System Utility</h3>
                            <button class="minimal-btn" style="width: 100%; margin-bottom: 0.75rem;" onclick="location.href='/dashboard.html'">Adjust Layout</button>
                            <button class="minimal-btn-outline" style="width: 100%;" onclick="alert('Config checked!')">Platform Check</button>
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
