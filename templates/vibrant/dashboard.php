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
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@500;700;800;900&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root {
            --border-dark: #000000;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-color, #f3f4f6);
            color: #000;
            line-height: 1.4;
            padding-bottom: 5rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
            border: 4px solid var(--border-dark);
            background: var(--bg-card, #fff);
            margin-top: 1.5rem;
            margin-bottom: 3rem;
            box-shadow: 8px 8px 0 var(--border-dark);
        }
        .brutal-card {
            border: 4px solid var(--border-dark);
            background: #fff;
            padding: 2.2rem;
            box-shadow: 8px 8px 0 var(--border-dark);
            margin-bottom: 2rem;
            transition: all 0.15s cubic-bezier(0, 0, 0, 1);
        }
        .brutal-card:hover {
            transform: translate(-3px, -3px);
            box-shadow: 11px 11px 0 var(--border-dark);
        }
        .brutal-btn {
            display: inline-block;
            padding: 0.6rem 1.4rem;
            border: 3px solid var(--border-dark);
            background-color: var(--primary);
            color: #fff;
            text-decoration: none;
            font-weight: 900;
            text-transform: uppercase;
            box-shadow: 4px 4px 0 var(--border-dark);
            transition: all 0.1s cubic-bezier(0, 0, 0, 1);
            cursor: pointer;
            font-family: inherit;
        }
        .brutal-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--border-dark);
        }
        .brutal-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 var(--border-dark);
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <h2 style="margin:0; background: var(--accent-1, #22d3ee); padding: 0.5rem 1rem; border: 3px solid var(--border-dark); font-weight:900; font-size:1.35rem; box-shadow: 4px 4px 0 var(--border-dark);">DASHBOARD</h2>
            <div class="nav-links" style="display: flex; align-items: center;">
                <div style="text-align: right; margin-right: 1.5rem;">
                    <span id="userNameDisplay" style="font-weight: 900; text-transform: uppercase; display:block; font-size: 0.95rem;">USER</span>
                    <span id="roleBadge" style="font-weight: 800; font-size: 0.75rem; text-transform: uppercase; background: var(--accent-2, #facc15); border: 2px solid var(--border-dark); padding: 0.1rem 0.4rem; display:inline-block; margin-top:0.25rem;">ROLE</span>
                </div>
                <div style="width: 3px; height: 35px; background: var(--border-dark); margin-right: 1.5rem;"></div>
                <a href="#" onclick="logout()" style="font-weight: 900; color: var(--accent-3, #f43f5e); text-decoration: none; text-transform: uppercase; border-bottom: 3px solid var(--accent-3, #f43f5e);">LOGOUT X</a>
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
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; border-bottom: 4px solid var(--border-dark); padding-bottom: 1.5rem;">
                        <div>
                            <h1 style="font-size: 3.5rem; font-weight: 900; text-transform: uppercase; letter-spacing: -0.03em;">MY LEDGER</h1>
                            <p style="font-weight: 800; font-size: 1.15rem; margin-top: 0.5rem; background: var(--accent-2, #facc15); padding: 0.3rem 0.8rem; display: inline-block; border: 3px solid #000; box-shadow: 4px 4px 0 #000;">GRADE 10 - SECTION A</p>
                        </div>
                        <button class="brutal-btn" onclick="alert('Roster compiled!')">DOWNLOAD REPORT</button>
                    </div>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: var(--accent-1, #22d3ee);">
                            <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">3.85</h2>
                            <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">GPA</p>
                        </div>
                        <div class="brutal-card" style="background: #fff;">
                            <h3 style="margin: 0; font-size: 1.8rem; font-weight: 900; text-transform: uppercase;">NEXT EXAM</h3>
                            <p style="font-weight: 800; font-size: 1.2rem; color: var(--accent-3, #f43f5e); margin-top: 0.5rem;">CALCULUS II FINAL</p>
                            <span style="font-weight: 800; font-size: 0.95rem; border: 2px solid #000; padding: 0.2rem 0.5rem; display: inline-block; margin-top: 0.5rem;">OCT 12 @ 9AM</span>
                        </div>
                        <div class="brutal-card" style="background: var(--accent-3, #f43f5e); color: #000;">
                            <h2 style="margin: 0; font-size: 3rem; font-weight: 900; text-transform: uppercase;">WARNING!</h2>
                            <p style="font-weight: 800; font-size: 1.15rem; text-transform: uppercase; margin-top: 0.5rem;">2 TASK SUBMISSIONS DUE</p>
                        </div>
                    </div>
                    
                    <h2 style="font-size: 2.2rem; margin-top: 3.5rem; margin-bottom: 2rem; border-bottom: 4px solid #000; display: inline-block; font-weight: 900; text-transform: uppercase; padding-bottom: 0.5rem;">ROSTER & EVALUATIONS</h2>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: #fff;">
                            <h3 style="font-size: 1.6rem; font-weight: 900; text-transform: uppercase; margin: 0;">MATHEMATICS</h3>
                            <p style="font-weight: 800; color: var(--accent-3, #f43f5e); margin-top: 0.25rem;">Mr. Abebe</p>
                            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 3px dashed #000;">
                                <p style="margin: 0; font-weight: 800; font-size: 1.05rem;">Midterm Exam: <span style="background: var(--accent-2, #facc15); padding: 0.1rem 0.4rem; border: 2px solid #000;">85/100</span></p>
                            </div>
                        </div>
                        <div class="brutal-card" style="background: #fff;">
                            <h3 style="font-size: 1.6rem; font-weight: 900; text-transform: uppercase; margin: 0;">PHYSICS</h3>
                            <p style="font-weight: 800; color: var(--accent-3, #f43f5e); margin-top: 0.25rem;">Ms. Sara</p>
                            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 3px dashed #000;">
                                <p style="margin: 0; font-weight: 800; font-size: 1.05rem;">Lab Report: <span style="background: var(--accent-2, #facc15); padding: 0.1rem 0.4rem; border: 2px solid #000;">92/100</span></p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; border-bottom: 4px solid var(--border-dark); padding-bottom: 1.5rem;">
                        <div>
                            <h1 style="font-size: 3.5rem; font-weight: 900; text-transform: uppercase; letter-spacing: -0.03em;">FACULTY HUB</h1>
                            <p style="font-weight: 800; font-size: 1.15rem; margin-top: 0.5rem; background: var(--accent-2, #facc15); padding: 0.3rem 0.8rem; display: inline-block; border: 3px solid #000; box-shadow: 4px 4px 0 #000;">Syllabus command console</p>
                        </div>
                    </div>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: var(--accent-1, #22d3ee);">
                            <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">4</h2>
                            <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">SECTIONS</p>
                        </div>
                        <div class="brutal-card" style="background: var(--accent-3, #f43f5e);">
                            <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">15</h2>
                            <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">UNRESOLVED SCORINGS</p>
                            <button class="brutal-btn" style="background:#fff; color:#000; margin-top:1.5rem; width:100%;" onclick="alert('Evaluations list resolved!')">RESOLVE NOW</button>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; border-bottom: 4px solid var(--border-dark); padding-bottom: 1.5rem;">
                        <div>
                            <h1 style="font-size: 3.5rem; font-weight: 900; text-transform: uppercase; letter-spacing: -0.03em;">THE RADAR</h1>
                            <p style="font-weight: 800; font-size: 1.15rem; margin-top: 0.5rem; background: var(--accent-2, #facc15); padding: 0.3rem 0.8rem; display: inline-block; border: 3px solid #000; box-shadow: 4px 4px 0 #000;">Family academic dashboard</p>
                        </div>
                    </div>
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: var(--accent-1, #22d3ee);">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin: 0; margin-bottom: 1rem;">ALEX DOE</h2>
                            <p style="font-weight: 800; font-size: 1.1rem; text-transform: uppercase; margin: 0;">GRADE 10 - CLASS A</p>
                            <button class="brutal-btn" style="background:#fff; color:#000; margin-top:1.5rem; width:100%;" onclick="alert('Roster compiled!')">VIEW LEDGER</button>
                        </div>
                        <div class="brutal-card" style="background: var(--accent-3, #f43f5e);">
                            <h2 style="font-size: 3rem; font-weight: 900; margin: 0; line-height: 1;">$0.00</h2>
                            <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">OUTSTANDING TUITION</p>
                            <span style="font-weight: 900; background:#fff; border:3px solid #000; padding:0.2rem 0.5rem; display:inline-block; margin-top:1rem;">FULLY RESOLVED</span>
                        </div>
                    </div>
                `;
            } else if (role === 'director') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; border-bottom: 4px solid var(--border-dark); padding-bottom: 1.5rem;">
                        <div>
                            <h1 style="font-size: 3.5rem; font-weight: 900; text-transform: uppercase; letter-spacing: -0.03em;">CENTRAL CONTROL</h1>
                            <p style="font-weight: 800; font-size: 1.15rem; margin-top: 0.5rem; background: var(--accent-2, #facc15); padding: 0.3rem 0.8rem; display: inline-block; border: 3px solid #000; box-shadow: 4px 4px 0 #000;">Global system command panel</p>
                        </div>
                        <a href="/dashboard.html" class="brutal-btn" style="background: var(--accent-3, #f43f5e); color:#000;">LAUNCH BUILDER</a>
                    </div>
                    
                    <div class="dashboard-grid">
                        <div class="brutal-card" style="background: var(--accent-1, #22d3ee);">
                            <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">480</h2>
                            <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">PUPILS ENROLLED</p>
                        </div>
                        <div class="brutal-card" style="background: var(--accent-2, #facc15);">
                            <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">32</h2>
                            <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">FACULTY ROSTER</p>
                        </div>
                        <div class="brutal-card" style="background: #a855f7; color: #fff;">
                            <h2 style="font-size: 2.2rem; font-weight: 900; margin: 0; text-transform: uppercase;">ENTERPRISE</h2>
                            <p style="font-weight: 900; font-size: 1.2rem; text-transform: uppercase; margin-top: 0.5rem;">BILLING LEVEL</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                        <div class="brutal-card" style="background: #fff;">
                            <h3 style="font-size: 1.8rem; font-weight: 900; text-transform: uppercase; border-bottom: 4px solid #000; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">ACTIVE CONFIGURATION</h3>
                            <div style="font-weight: 800; font-size: 1.05rem; display: flex; flex-direction: column; gap: 1rem;">
                                <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;">
                                    <span>DYNAMIC BUILDER FRAME</span>
                                    <span style="color: var(--accent-3, #f43f5e);">CONNECTED</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;">
                                    <span>AUTO CSV SYNC ROSTER</span>
                                    <span style="color: var(--accent-3, #f43f5e);">ACTIVE</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>TERM / CURRICULUM</span>
                                    <span>FALL SEMESTER 2026</span>
                                </div>
                            </div>
                        </div>

                        <div class="brutal-card" style="background: #fff; display: flex; flex-direction: column; gap: 1rem;">
                            <h3 style="font-size: 1.8rem; font-weight: 900; text-transform: uppercase; border-bottom: 4px solid #000; padding-bottom: 0.5rem; margin-bottom: 0.5rem;">UTILITIES</h3>
                            <button class="brutal-btn" style="background: var(--accent-2, #facc15); color:#000;" onclick="location.href='/dashboard.html'">THEME BUILDER</button>
                            <button class="brutal-btn" style="background: #fff; color:#000;" onclick="alert('Roster compiled and checked!')">CSV RE-SYNC</button>
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
