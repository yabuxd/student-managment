<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SIS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <nav style="padding: 1rem 5%; background: rgba(6, 9, 19, 0.6); backdrop-filter: blur(20px); border-bottom: 1px solid var(--glass-border); position: sticky; top: 0; z-index: 10; margin-bottom: 3rem;">
        <a href="#" class="nav-brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            <span style="font-size: 1.25rem;">NexEdu</span>
        </a>
        <div class="nav-links">
            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                <span id="userNameDisplay" style="color: #fff; font-weight: 600; font-size: 0.95rem;">Loading...</span>
                <span id="roleBadge" style="color: var(--accent); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-family: 'Space Grotesk', sans-serif;">Role</span>
            </div>
            <div style="width: 1px; height: 30px; background: var(--glass-border);"></div>
            <a href="#" onclick="logout()" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Sign Out</a>
        </div>
    </nav>

    <div class="container">
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
                            <h2 style="font-size: 2rem; margin-bottom: 0.25rem;">Academic Overview</h2>
                            <p style="color: var(--accent); margin: 0; font-weight: 600;">Grade 10 - Section A</p>
                        </div>
                        <button class="btn btn-primary">Download Report</button>
                    </div>
                    <div class="dashboard-grid">
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">📈</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Current GPA</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #fff;">3.8<span style="font-size: 1rem; color: var(--text-muted);">/4.0</span></p>
                            </div>
                        </div>
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">📚</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Active Courses</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #fff;">6</p>
                            </div>
                        </div>
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">⚠️</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Due Assignments</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #fff;">2</p>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 2rem;">
                        <div class="glass-panel" style="padding: 1.5rem;">
                            <h3 style="margin-top: 0; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem; color: #fff;">Subjects & Teachers</h3>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between;">
                                    <span style="color: #fff;">Mathematics</span> <span style="color: var(--text-muted);">Mr. Abebe</span>
                                </li>
                                <li style="padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between;">
                                    <span style="color: #fff;">Physics</span> <span style="color: var(--text-muted);">Ms. Sara</span>
                                </li>
                                <li style="padding: 0.75rem 0; display: flex; justify-content: space-between;">
                                    <span style="color: #fff;">English</span> <span style="color: var(--text-muted);">Mr. Dawit</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="glass-panel" style="padding: 0;">
                            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--glass-border);">
                                <h3 style="margin: 0; font-size: 1.25rem;">Recent Grades</h3>
                            </div>
                            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid var(--glass-border); color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">
                                    <th style="padding: 1rem 2rem; font-weight: 500;">Course</th>
                                    <th style="padding: 1rem 2rem; font-weight: 500;">Assignment</th>
                                    <th style="padding: 1rem 2rem; font-weight: 500;">Grade</th>
                                </tr>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 1rem 2rem; font-weight: 500; color: #fff;">Mathematics</td>
                                    <td style="padding: 1rem 2rem; color: var(--text-muted);">Midterm Exam</td>
                                    <td style="padding: 1rem 2rem; color: #10b981; font-weight: 600;">85/100</td>
                                </tr>
                                <tr>
                                    <td style="padding: 1rem 2rem; font-weight: 500; color: #fff;">Physics</td>
                                    <td style="padding: 1rem 2rem; color: var(--text-muted);">Lab Report</td>
                                    <td style="padding: 1rem 2rem; color: #10b981; font-weight: 600;">92/100</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                        <div>
                            <h2 style="font-size: 2rem; margin-bottom: 0.25rem;">Faculty Hub</h2>
                            <p style="color: var(--text-muted); margin: 0;">Manage your classes and assignments</p>
                        </div>
                    </div>
                    <div class="dashboard-grid">
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">📝</div>
                            <div>
                                <h3 style="margin:0; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase;">To Grade</h3>
                                <p style="margin:0; font-size: 2rem; font-weight: 700; color: #fff;">24</p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <h2 style="font-size: 2rem; margin-bottom: 2rem;">Guardian Portal</h2>
                    <div class="dashboard-grid">
                        <div class="glass-panel">
                            <h3 style="margin-top: 0;">Linked Students</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 0.75rem; border: 1px solid var(--glass-border);">
                                <div>
                                    <h4 style="margin: 0 0 0.25rem; color: #fff;">Alex Doe</h4>
                                    <p style="margin: 0; font-size: 0.85rem; color: var(--accent);">Grade 10</p>
                                </div>
                                <button class="btn btn-outline" style="padding: 0.4rem 1rem;">View Details</button>
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
