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
                    <h1 style="font-size: 3rem; margin-bottom: 2rem;">MY STUFF.</h1>
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
