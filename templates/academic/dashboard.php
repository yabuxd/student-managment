<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Dashboard | Institution</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: var(--bg-main);">
    <nav style="box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <a href="#" class="nav-brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            Campus Dashboard
        </a>
        <div class="nav-links" style="display: flex; align-items: center; gap: 1rem;">
            <div style="text-align: right;">
                <div id="userNameDisplay" style="font-weight: 600; color: var(--primary); line-height: 1.2;">User Name</div>
                <div id="roleBadge" style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Role</div>
            </div>
            <div style="height: 32px; width: 1px; background: var(--border-color); margin: 0 1rem;"></div>
            <a href="#" onclick="logout()" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.85rem;">Sign Out</a>
        </div>
    </nav>

    <div class="container" style="padding-top: 3rem;">
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
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                        <div>
                            <h2 style="margin:0 0 0.5rem;">Academic Overview</h2>
                            <p style="margin:0; color: var(--primary); font-weight: 600; display: inline-block; padding: 0.2rem 0.5rem; background: rgba(0,0,0,0.05); border-radius: 4px;">Grade 10 - Section A</p>
                        </div>
                        <button class="btn btn-primary" style="width: auto;">Download Transcript</button>
                    </div>
                    <div class="dashboard-grid">
                        <div class="card stat-item">
                            <h3>Cumulative GPA</h3>
                            <p>3.85 <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 400;">/ 4.0</span></p>
                        </div>
                        <div class="card stat-item">
                            <h3>Active Courses</h3>
                            <p>6 <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 400;">Subjects</span></p>
                        </div>
                        <div class="card stat-item">
                            <h3>Next Examination</h3>
                            <p style="font-size: 1.2rem;">Oct 12 - Calculus II</p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                        <div class="card">
                            <h3 style="margin-top: 0; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">Subjects & Teachers</h3>
                            <ul style="list-style: none; padding: 0; margin: 1rem 0 0 0;">
                                <li style="padding: 0.75rem 0; border-bottom: 1px solid #f1f3f5; display: flex; justify-content: space-between;">
                                    <strong>Mathematics</strong> <span style="color: var(--text-muted);">Mr. Abebe</span>
                                </li>
                                <li style="padding: 0.75rem 0; border-bottom: 1px solid #f1f3f5; display: flex; justify-content: space-between;">
                                    <strong>Physics</strong> <span style="color: var(--text-muted);">Ms. Sara</span>
                                </li>
                                <li style="padding: 0.75rem 0; display: flex; justify-content: space-between;">
                                    <strong>English</strong> <span style="color: var(--text-muted);">Mr. Dawit</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card">
                            <h3 style="margin-top: 0; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">Recent Grades</h3>
                            <table style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 1rem;">
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <th style="padding: 0.75rem 0;">Course</th>
                                    <th>Assignment</th>
                                    <th>Grade</th>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f3f5;">
                                    <td style="padding: 0.75rem 0; font-weight: 600;">Mathematics</td>
                                    <td>Midterm Exam</td>
                                    <td style="color: green; font-weight: bold;">85/100</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0.75rem 0; font-weight: 600;">Physics</td>
                                    <td>Lab Report</td>
                                    <td style="color: green; font-weight: bold;">92/100</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <h2 style="margin-bottom: 2rem;">Faculty Portal</h2>
                    <div class="dashboard-grid">
                        <div class="card stat-item">
                            <h3>Courses Taught</h3>
                            <p>4 Active Sections</p>
                        </div>
                        <div class="card stat-item">
                            <h3>Pending Grades</h3>
                            <p>15 Submissions</p>
                            <a href="#" style="font-size: 0.85rem; color: var(--primary);">Review now →</a>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <h2 style="margin-bottom: 2rem;">Parent Guardian Portal</h2>
                    <div class="dashboard-grid">
                        <div class="card">
                            <h3 style="margin-top: 0;">Enrolled Dependents</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                                <div>
                                    <h4 style="margin:0;">Alex Doe</h4>
                                    <p style="margin:0; font-size: 0.85rem; color: var(--text-muted);">Sophomore</p>
                                </div>
                                <button class="btn btn-outline">View Records</button>
                            </div>
                        </div>
                        <div class="card">
                            <h3 style="margin-top: 0;">Institutional Notices</h3>
                            <ul style="padding-left: 1rem; color: var(--text-muted); font-size: 0.95rem;">
                                <li style="margin-bottom: 0.5rem;">Tuition deadline approaching.</li>
                                <li>Parent-Teacher meetings next Thursday.</li>
                            </ul>
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
