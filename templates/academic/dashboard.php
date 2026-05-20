<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'academic';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Playfair Display';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : "Campus Dashboard.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Dashboard | Institution</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            background-color: #f4f6f8;
            color: #2c3e50;
            margin: 0;
            background-image: radial-gradient(#e0e0e0 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .academic-nav {
            border-bottom: 1px solid #e0e0e0;
            background: #fff;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .academic-btn {
            background-color: var(--primary);
            color: #fff;
            padding: 0.6rem 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.8rem;
            text-decoration: none;
            transition: background-color 0.3s;
            border: 1px solid var(--primary);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }
        .academic-btn:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .academic-btn-outline {
            background-color: transparent;
            color: var(--primary);
            padding: 0.6rem 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.3s;
            border: 1px solid var(--primary);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }
        .academic-btn-outline:hover {
            background-color: var(--primary);
            color: #fff;
        }
        .academic-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 2rem;
            border-top: 4px solid var(--primary);
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            margin-bottom: 1.5rem;
        }
        h1, h2, h3, h4 {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <nav class="academic-nav">
        <a href="#" style="text-decoration: none; color: inherit; display: flex; align-items: center; font-size: 1.25rem; font-weight: 700;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right: 0.5rem;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            Campus Dashboard
        </a>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="text-align: right;">
                <div id="userNameDisplay" style="font-weight: 700; color: #2c3e50; font-family: 'Inter', sans-serif; font-size: 0.95rem;">User Name</div>
                <div id="roleBadge" style="font-size: 0.75rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; font-family: 'Inter', sans-serif;">Role</div>
            </div>
            <div style="height: 32px; width: 1px; background: #e0e0e0;"></div>
            <a href="#" onclick="logout()" class="academic-btn-outline">Sign Out</a>
        </div>
    </nav>

    <div class="container" style="padding: 3rem 5%; max-width: 1200px; margin: 0 auto;">
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
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--primary); padding-bottom: 1rem;">
                        <div>
                            <h2 style="margin:0 0 0.5rem; font-size: 2.25rem; font-weight: 700; color: #2c3e50;">Academic Overview</h2>
                            <p style="margin:0; color: var(--primary); font-family: 'Inter', sans-serif; font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">Grade 10 - Section A</p>
                        </div>
                        <button class="academic-btn" onclick="alert('Transcript Downloaded!')">Download Transcript</button>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Cumulative GPA</h3>
                            <p style="margin:0; font-size: 2.5rem; font-weight: 700; color: var(--primary);">3.85 <span style="font-size: 1rem; color: #90a4ae; font-weight: 400; font-family: 'Inter', sans-serif;">/ 4.0</span></p>
                        </div>
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Active Courses</h3>
                            <p style="margin:0; font-size: 2.5rem; font-weight: 700; color: var(--primary);">6 <span style="font-size: 1rem; color: #90a4ae; font-weight: 400; font-family: 'Inter', sans-serif;">Subjects</span></p>
                        </div>
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Next Examination</h3>
                            <p style="margin:0; font-size: 1.5rem; font-weight: 600; color: var(--primary); padding-top: 0.5rem;">Oct 12 - Calculus II</p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div class="academic-card" style="padding: 0;">
                            <h3 style="margin: 0; border-bottom: 1px solid #e0e0e0; padding: 1.5rem 2rem; font-size: 1.25rem; font-weight: 600; background: #fafafa;">Subjects & Faculty</h3>
                            <ul style="list-style: none; padding: 0 2rem; margin: 0;">
                                <li style="padding: 1rem 0; border-bottom: 1px solid #f1f3f5; display: flex; justify-content: space-between; font-family: 'Inter', sans-serif;">
                                    <strong style="color: #2c3e50;">Mathematics</strong> <span style="color: #546e7a;">Prof. Abebe</span>
                                </li>
                                <li style="padding: 1rem 0; border-bottom: 1px solid #f1f3f5; display: flex; justify-content: space-between; font-family: 'Inter', sans-serif;">
                                    <strong style="color: #2c3e50;">Physics</strong> <span style="color: #546e7a;">Dr. Sara</span>
                                </li>
                                <li style="padding: 1rem 0; display: flex; justify-content: space-between; font-family: 'Inter', sans-serif; margin-bottom: 1rem;">
                                    <strong style="color: #2c3e50;">English Literature</strong> <span style="color: #546e7a;">Prof. Dawit</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="academic-card" style="padding: 0;">
                            <h3 style="margin: 0; border-bottom: 1px solid #e0e0e0; padding: 1.5rem 2rem; font-size: 1.25rem; font-weight: 600; background: #fafafa;">Recent Evaluations</h3>
                            <table style="width: 100%; text-align: left; border-collapse: collapse; font-family: 'Inter', sans-serif;">
                                <tr style="border-bottom: 1px solid #e0e0e0; background: #fcfcfc;">
                                    <th style="padding: 1rem 2rem; color: #546e7a; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Course</th>
                                    <th style="padding: 1rem 2rem; color: #546e7a; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Assessment</th>
                                    <th style="padding: 1rem 2rem; color: #546e7a; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Grade</th>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f3f5;">
                                    <td style="padding: 1rem 2rem; font-weight: 600; color: #2c3e50;">Mathematics</td>
                                    <td style="padding: 1rem 2rem; color: #546e7a;">Midterm Exam</td>
                                    <td style="padding: 1rem 2rem; color: #2e7d32; font-weight: 700;">85/100</td>
                                </tr>
                                <tr>
                                    <td style="padding: 1rem 2rem; font-weight: 600; color: #2c3e50;">Physics</td>
                                    <td style="padding: 1rem 2rem; color: #546e7a;">Lab Report</td>
                                    <td style="padding: 1rem 2rem; color: #2e7d32; font-weight: 700;">92/100</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--primary); padding-bottom: 1rem;">
                        <div>
                            <h2 style="margin:0; font-size: 2.25rem; font-weight: 700; color: #2c3e50;">Faculty Operating Space</h2>
                            <p style="margin: 0; color: var(--primary); font-family: 'Inter', sans-serif; font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">Welcome back, Instructor</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Active Classes</h3>
                            <p style="margin:0; font-size: 2.5rem; font-weight: 700; color: var(--primary);">4 <span style="font-size: 1rem; color: #90a4ae; font-weight: 400; font-family: 'Inter', sans-serif;">Sections</span></p>
                        </div>
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Pending Evaluations</h3>
                            <p style="margin:0; font-size: 2.5rem; font-weight: 700; color: var(--primary);">15 <span style="font-size: 1rem; color: #90a4ae; font-weight: 400; font-family: 'Inter', sans-serif;">Submissions</span></p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
                        <div class="academic-card" style="padding: 0;">
                            <h3 style="margin: 0; border-bottom: 1px solid #e0e0e0; padding: 1.5rem 2rem; font-size: 1.25rem; font-weight: 600; background: #fafafa;">Pending Submissions Queue</h3>
                            <table style="width: 100%; text-align: left; border-collapse: collapse; font-family: 'Inter', sans-serif;">
                                <tr style="border-bottom: 1px solid #e0e0e0; background: #fcfcfc;">
                                    <th style="padding: 1rem 2rem; color: #546e7a; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Student</th>
                                    <th style="padding: 1rem 2rem; color: #546e7a; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Course</th>
                                    <th style="padding: 1rem 2rem; color: #546e7a; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Task</th>
                                    <th style="padding: 1rem 2rem; color: #546e7a; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Action</th>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f3f5;">
                                    <td style="padding: 1rem 2rem; font-weight: 600; color: #2c3e50;">Alex Doe</td>
                                    <td style="padding: 1rem 2rem; color: #546e7a;">Mathematics</td>
                                    <td style="padding: 1rem 2rem; color: #546e7a;">Calculus HW 2</td>
                                    <td style="padding: 1rem 2rem;"><button class="academic-btn" style="padding: 0.3rem 0.8rem; font-size: 0.75rem;" onclick="alert('Grading Modal Loaded!')">Grade Now</button></td>
                                </tr>
                                <tr>
                                    <td style="padding: 1rem 2rem; font-weight: 600; color: #2c3e50;">Betty Abebe</td>
                                    <td style="padding: 1rem 2rem; color: #546e7a;">Physics</td>
                                    <td style="padding: 1rem 2rem; color: #546e7a;">Thermo Lab</td>
                                    <td style="padding: 1rem 2rem;"><button class="academic-btn" style="padding: 0.3rem 0.8rem; font-size: 0.75rem;" onclick="alert('Grading Modal Loaded!')">Grade Now</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--primary); padding-bottom: 1rem;">
                        <div>
                            <h2 style="margin:0; font-size: 2.25rem; font-weight: 700; color: #2c3e50;">Parent Guardian Portal</h2>
                            <p style="margin: 0; color: var(--primary); font-family: 'Inter', sans-serif; font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">Family Educational Progress</p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div class="academic-card" style="padding: 0;">
                            <h3 style="margin: 0; border-bottom: 1px solid #e0e0e0; padding: 1.5rem 2rem; font-size: 1.25rem; font-weight: 600; background: #fafafa;">Enrolled Dependents</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f3f5;">
                                <div>
                                    <h4 style="margin:0 0 0.25rem 0; font-size: 1.15rem; color: #2c3e50;">Alex Doe</h4>
                                    <p style="margin:0; font-size: 0.85rem; color: #546e7a; font-family: 'Inter', sans-serif;">Sophomore • Grade 10-A</p>
                                </div>
                                <button class="academic-btn-outline" onclick="alert('Loading Academic Roster...')">View Records</button>
                            </div>
                        </div>
                        <div class="academic-card" style="padding: 0;">
                            <h3 style="margin: 0; border-bottom: 1px solid #e0e0e0; padding: 1.5rem 2rem; font-size: 1.25rem; font-weight: 600; background: #fafafa;">Billing & Finance</h3>
                            <div style="padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="margin:0; color:#546e7a; font-size: 0.85rem; font-family:'Inter'; text-transform:uppercase;">Outstanding Tuition</p>
                                    <p style="margin:0; font-size: 1.8rem; font-weight: 700; color: #b71c1c;">$0.00 <span style="font-size: 0.95rem; color:#2e7d32; font-weight: 600;">(Fully Paid)</span></p>
                                </div>
                                <button class="academic-btn" style="padding: 0.5rem 1rem;" onclick="alert('Redirecting to secure gateway...')">Receipts</button>
                            </div>
                        </div>
                    </div>
                `;
            } else if (role === 'director') {
                contentArea.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--primary); padding-bottom: 1rem;">
                        <div>
                            <h2 style="margin:0; font-size: 2.25rem; font-weight: 700; color: #2c3e50;">Director Operations Dashboard</h2>
                            <p style="margin: 0; color: var(--primary); font-family: 'Inter', sans-serif; font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">Institutional Command Panel</p>
                        </div>
                        <a href="/dashboard.html" class="academic-btn" style="text-align: center;">Launch visual editor</a>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Total Students</h3>
                            <p style="margin:0; font-size: 2.5rem; font-weight: 700; color: var(--primary);">480</p>
                        </div>
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Faculty Members</h3>
                            <p style="margin:0; font-size: 2.5rem; font-weight: 700; color: var(--primary);">32</p>
                        </div>
                        <div class="academic-card" style="padding: 1.5rem;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: #546e7a; font-family: 'Inter', sans-serif;">Active Plan</h3>
                            <p style="margin:0; font-size: 1.8rem; font-weight: 700; color: var(--primary); text-transform:uppercase;">Enterprise</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div class="academic-card" style="padding: 2rem;">
                            <h3 style="margin-top:0; margin-bottom:1.5rem; border-bottom:1px solid #e0e0e0; padding-bottom: 0.75rem;">Platform Branding & Config</h3>
                            <div style="display: flex; flex-direction: column; gap: 1rem; font-family: 'Inter', sans-serif;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>Theme Configuration</span>
                                    <button class="academic-btn-outline" style="padding: 0.4rem 1rem; font-size: 0.75rem;" onclick="location.href='/dashboard.html'">Adjust Layout</button>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>Active Term / Curriculum</span>
                                    <span style="font-weight: 700; color:#546e7a;">Fall Semester 2026</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>Registration Code Status</span>
                                    <span style="font-weight: 700; color: #2e7d32;">Active (CSV Enabled)</span>
                                </div>
                            </div>
                        </div>

                        <div class="academic-card" style="padding: 2rem;">
                            <h3 style="margin-top:0; margin-bottom:1.5rem; border-bottom:1px solid #e0e0e0; padding-bottom: 0.75rem;">System Logs</h3>
                            <ul style="padding-left:1.25rem; margin:0; font-family: 'Inter', sans-serif; font-size: 0.9rem; color:#546e7a; line-height: 1.8;">
                                <li>CSV Import: Successfully imported 12 students.</li>
                                <li>Payment: Billing cycle resolved successfully.</li>
                                <li>System: Backup saved to recovery database.</li>
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
