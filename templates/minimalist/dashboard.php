<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EduPortal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <nav>
            <h2><span class="text-accent">Edu</span>Portal</h2>
            <div class="nav-links" style="display: flex; align-items: center;">
                <span id="userNameDisplay" style="color: var(--text-primary);">User</span>
                <span id="roleBadge" style="margin-left: 0.5rem; padding: 0.2rem 0.5rem; border-radius: 1rem; background: rgba(56, 189, 248, 0.2); color: var(--accent); font-size: 0.75rem;">Role</span>
                <a href="#" onclick="logout()" style="margin-left: 1.5rem; color: #ef4444;">Logout</a>
            </div>
        </nav>

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
            document.getElementById('roleBadge').textContent = role.toUpperCase();

            renderDashboard(role);
        });

        function renderDashboard(role) {
            const contentArea = document.getElementById('dashboardContent');
            
            if (role === 'student') {
                contentArea.innerHTML = `
                    <h2 style="margin-bottom: 2rem;">My Academic Hub</h2>
                    <div class="dashboard-grid">
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">A</div>
                            <div>
                                <h3 style="margin: 0;">Current GPA</h3>
                                <p style="margin: 0; color: var(--text-muted);">3.8 / 4.0</p>
                            </div>
                        </div>
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">📅</div>
                            <div>
                                <h3 style="margin: 0;">Next Class</h3>
                                <p style="margin: 0; color: var(--text-muted);">Mathematics (10:00 AM)</p>
                            </div>
                        </div>
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">📝</div>
                            <div>
                                <h3 style="margin: 0;">Assignments</h3>
                                <p style="margin: 0; color: var(--text-muted);">2 Pending</p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <h2 style="margin-bottom: 2rem;">Teacher Workspace</h2>
                    <div class="dashboard-grid">
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">📚</div>
                            <div>
                                <h3 style="margin: 0;">My Classes</h3>
                                <p style="margin: 0; color: var(--text-muted);">4 Sections</p>
                            </div>
                        </div>
                        <div class="glass-panel stat-card">
                            <div class="stat-icon">✅</div>
                            <div>
                                <h3 style="margin: 0;">To Grade</h3>
                                <p style="margin: 0; color: var(--text-muted);">15 Submissions</p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <h2 style="margin-bottom: 2rem;">Parent Overview</h2>
                    <div class="dashboard-grid">
                        <div class="glass-panel">
                            <h3>Children Linked</h3>
                            <p style="color: var(--text-muted);">Alex Doe (Grade 10)</p>
                            <button class="btn btn-outline" style="margin-top: 1rem; width: 100%;">View Report Card</button>
                        </div>
                        <div class="glass-panel">
                            <h3>Recent Notifications</h3>
                            <ul style="color: var(--text-muted); padding-left: 1rem;">
                                <li>Math Exam tomorrow</li>
                                <li>Parent-Teacher conference scheduled</li>
                            </ul>
                        </div>
                    </div>
                `;
            } else {
                contentArea.innerHTML = `<div class="glass-panel"><h3>Welcome</h3><p>Please configure your account.</p></div>`;
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
