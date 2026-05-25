<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'academic';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Playfair Display';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Dashboard | Institution Portal</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root {
            --primary: var(--accent-1, #0f4c81);
            --primary-light: var(--accent-2, #1a6ab3);
            --secondary: var(--accent-3, #d4af37);
            --border-color: var(--border-color, #dee2e6);
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            background-color: var(--bg-color, #f4f6f8);
            color: var(--text-color, #2c3e50);
            margin: 0;
            background-image: radial-gradient(rgba(0,0,0,0.05) 1px, transparent 1px);
            background-size: 20px 20px;
            padding-bottom: 5rem;
        }
        .academic-nav {
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-card, #fff);
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
        .academic-btn-danger {
            background-color: #c62828;
            border-color: #c62828;
            color: #fff;
        }
        .academic-btn-danger:hover {
            background-color: #b71c1c;
            border-color: #b71c1c;
        }
        .academic-btn-success {
            background-color: #2e7d32;
            border-color: #2e7d32;
            color: #fff;
        }
        .academic-btn-success:hover {
            background-color: #1b5e20;
            border-color: #1b5e20;
        }
        .academic-card {
            background: var(--bg-card, #fff);
            border: 1px solid var(--border-color, #dee2e6);
            padding: 2rem;
            border-top: 4px solid var(--primary);
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            margin-bottom: 1.5rem;
            position: relative;
        }
        .academic-badge {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid var(--border-color, #dee2e6);
            padding: 0.25rem 0.6rem;
            background: var(--bg-color, #fafafa);
            color: var(--text-color, #2c3e50);
            display: inline-block;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .academic-table-container {
            border: 1px solid var(--border-color, #dee2e6);
            background: var(--bg-card, #fff);
            overflow-x: auto;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .academic-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-family: 'Inter', sans-serif;
        }
        .academic-table th {
            background: var(--bg-color, #fafafa);
            color: var(--text-muted, #546e7a);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color, #dee2e6);
        }
        .academic-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color, #f1f3f5);
            color: var(--text-color, #2c3e50);
            font-size: 0.95rem;
        }
        .academic-table tr:last-child td {
            border-bottom: none;
        }
        /* Tab System styling */
        .academic-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .tab-btn {
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            color: #78909c;
            transition: all 0.2s;
        }
        .tab-btn:hover {
            color: var(--primary);
        }
        .tab-btn.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }
        .tab-panel {
            display: none;
        }
        .tab-panel.active {
            display: block;
        }
        /* Elegant Form fields */
        .academic-input {
            border: 1px solid #d0d7de;
            border-radius: 2px;
            padding: 0.8rem;
            font-family: 'Inter', sans-serif;
            background: #fafafa;
            width: 100%;
            box-sizing: border-box;
            outline: none;
            margin-bottom: 1rem;
            transition: border-color 0.2s;
        }
        .academic-input:focus {
            border-color: var(--primary);
            background: #fff;
        }
        /* Modal Neo-Brutalist overlay */
        .academic-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.35);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .academic-modal-content {
            background: #fff;
            border: 1px solid #e0e0e0;
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
            padding: 2.5rem;
            width: 90%;
            max-width: 650px;
            position: relative;
        }
        .close-modal {
            position: absolute;
            top: 1rem; right: 1rem;
            font-size: 1.5rem; font-weight: 300;
            cursor: pointer;
            color: #90a4ae;
            transition: color 0.2s;
        }
        .close-modal:hover {
            color: #c62828;
        }
        h1, h2, h3, h4 {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            font-weight: 700;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <nav class="academic-nav">
        <a href="#" style="text-decoration: none; color: inherit; display: flex; align-items: center; font-size: 1.25rem; font-weight: 700;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right: 0.5rem;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            Campus Core Portal
        </a>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="text-align: right;">
                <div id="userNameDisplay" style="font-weight: 700; color: #2c3e50; font-family: 'Inter', sans-serif; font-size: 0.95rem;">Guest User</div>
                <div id="roleBadge" class="academic-badge" style="border:none; padding:0; background:none; font-size: 0.75rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Role</div>
            </div>
            <div style="height: 32px; width: 1px; background: #e0e0e0;"></div>
            <a href="#" onclick="logout()" class="academic-btn-outline" style="padding: 0.5rem 1.2rem;">Sign Out</a>
        </div>
    </nav>

    <div class="container" style="padding: 2.5rem 5%; max-width: 1200px; margin: 0 auto;">
        <div id="dashboardContent"></div>
    </div>

    <!-- Details/Grades Modal -->
    <div id="academicModal" class="academic-modal">
        <div class="academic-modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');
            const role = localStorage.getItem('user_role');
            const name = localStorage.getItem('user_name');

            if (!token || !role) {
                window.location.href = 'login.php';
                return;
            }

            document.getElementById('userNameDisplay').textContent = name;
            document.getElementById('roleBadge').textContent = role;

            loadRolePortal(role);
        });

        // Common API fetch wrapper adding Bearer token automatically
        async function apiRequest(endpoint, method = 'GET', body = null) {
            const token = localStorage.getItem('token');
            const options = {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            };
            if (body) {
                options.body = JSON.stringify(body);
            }
            try {
                const res = await fetch(`/api/${endpoint}`, options);
                if (res.status === 401) {
                    logout();
                    return null;
                }
                return await res.json();
            } catch (err) {
                console.error("API Request Error:", err);
                return { success: false, message: "Network connection failure." };
            }
        }

        function showModal(html) {
            document.getElementById('modalBody').innerHTML = html;
            document.getElementById('academicModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('academicModal').style.display = 'none';
        }

        function logout() {
            localStorage.clear();
            window.location.href = 'login.php';
        }

        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        // ==========================================
        // MASTER PORTAL LOADING BY ROLE
        // ==========================================

        async function loadRolePortal(role) {
            const contentArea = document.getElementById('dashboardContent');
            
            if (role === 'student') {
                contentArea.innerHTML = `
                    <div class="academic-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-student-courses')">My Enrolled Courses</button>
                        <button class="tab-btn" onclick="switchTab('tab-student-report')">Year-End Report Card</button>
                        <button class="tab-btn" onclick="switchTab('tab-student-chat')">Contact Teachers</button>
                    </div>

                    <div id="tab-student-courses" class="tab-panel active">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--primary); padding-bottom: 1rem;">
                            <div>
                                <h1 style="font-size: 2.5rem; font-weight: 700; margin:0; color:#2c3e50;">Academic Transcript</h1>
                                <p id="studentSectionName" class="academic-badge" style="margin-top:0.5rem;"></p>
                            </div>
                            <div class="academic-card" style="padding: 1rem 1.5rem; margin:0; border-top-color: var(--primary);">
                                <span style="font-weight:600; font-family:'Inter'; font-size:0.8rem; color:#546e7a;">CUMULATIVE AVERAGE</span>
                                <h2 id="studentOverallGpa" style="font-size: 2.2rem; font-weight:700; margin:0; color:var(--primary);">0.00</h2>
                            </div>
                        </div>

                        <div id="studentCoursesList" class="dashboard-grid">
                            <p style="font-style:italic;">Loading enrolled courses...</p>
                        </div>
                    </div>

                    <div id="tab-student-report" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2.2rem; font-weight: 700; margin-top:0;">Official Year-End Roster Report</h2>
                            <div id="studentFinalEvaluationBlock">
                                <p style="font-style:italic;">Checking evaluation status...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-student-chat" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0;">Communication Center</h2>
                            <div id="studentChatPanel">
                                <p style="font-style:italic;">Loading messenger contacts...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadStudentCoursesData();
                await loadStudentFinalEvaluationData();
                await loadMessenger('student');

            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div class="academic-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-teacher-courses')">My Scheduled Classes</button>
                        <button class="tab-btn" onclick="switchTab('tab-teacher-homeroom')">Homeroom Desk</button>
                        <button class="tab-btn" onclick="switchTab('tab-teacher-chat')">Parent Messages</button>
                    </div>

                    <div id="tab-teacher-courses" class="tab-panel active">
                        <h1 style="font-size: 2.5rem; font-weight: 700; margin:0 0 2rem; color:#2c3e50;">Faculty Hub Workspace</h1>
                        <div class="dashboard-grid" id="teacherAssignmentsGrid">
                            <p style="font-style:italic;">Loading teaching assignments...</p>
                        </div>
                    </div>

                    <div id="tab-teacher-homeroom" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2.2rem; font-weight: 700; margin-top:0;">Homeroom Section Management</h2>
                            <div id="teacherHomeroomConfig">
                                <p style="font-style:italic;">Verifying homeroom assignment...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-teacher-chat" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0;">Parent Messaging Console</h2>
                            <div id="teacherChatPanel">
                                <p style="font-style:italic;">Loading chat records...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadTeacherPortalData();
                await loadMessenger('teacher');

            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <div class="academic-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-parent-students')">My Dependents</button>
                        <button class="tab-btn" onclick="switchTab('tab-parent-chat')">Teacher Chat Console</button>
                    </div>

                    <div id="tab-parent-students" class="tab-panel active">
                        <h1 style="font-size: 2.5rem; font-weight: 700; margin:0 0 2rem; color:#2c3e50;">Parent Guardian Portal</h1>
                        <div class="dashboard-grid" id="parentStudentsGrid">
                            <p style="font-style:italic;">Loading linked dependent files...</p>
                        </div>
                    </div>

                    <div id="tab-parent-chat" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0;">Tutor Communication Core</h2>
                            <div id="parentChatPanel">
                                <p style="font-style:italic;">Loading chat records...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadParentPortalData();
                await loadMessenger('parent');

            } else if (role === 'director') {
                contentArea.innerHTML = `
                    <div class="academic-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-director-overview')">Platform Overview</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-assignments')">Faculty Rosters</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-sectioning')">Roster Sectioning</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-parents')">Parent Linkage</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-subjects')">Subjects</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-users')">Users & Onboarding</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-config')">System Config</button>
                    </div>

                    <div id="tab-director-overview" class="tab-panel active">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--primary); padding-bottom: 1rem;">
                            <div>
                                <h1 style="font-size: 2.5rem; font-weight: 700; margin:0; color:#2c3e50;">Platform Command Dashboard</h1>
                                <span class="academic-badge" style="margin-top:0.5rem;">Institutional Core Running</span>
                            </div>
                        </div>
                        <div class="dashboard-grid" id="directorStatsGrid">
                            <p style="font-style:italic;">Loading global statistics...</p>
                        </div>
                    </div>

                    <div id="tab-director-assignments" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0; border-bottom: 1px solid #e0e0e0; padding-bottom: 0.5rem;">Subject & Section Allocation</h2>
                            <div style="margin-top: 1.5rem;" id="directorAssignmentsConfig">
                                <p style="font-style:italic;">Loading scheduled allocations...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-sectioning" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0; border-bottom: 1px solid #e0e0e0; padding-bottom: 0.5rem;">Student sectioning allocation</h2>
                            <div style="margin-top: 1.5rem;" id="directorSectioningConfig">
                                <p style="font-style:italic;">Loading student list...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-parents" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0; border-bottom: 1px solid #e0e0e0; padding-bottom: 0.5rem;">Parent-Student Linkage</h2>
                            <div style="margin-top: 1.5rem;" id="directorParentsConfig">
                                <p style="font-style:italic;">Loading parent linkages...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-subjects" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0; border-bottom: 1px solid #e0e0e0; padding-bottom: 0.5rem;">Subject Curriculum</h2>
                            <div style="margin-top: 1.5rem;" id="directorSubjectsConfig">
                                <p style="font-style:italic;">Loading curriculum subjects...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-users" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0; border-bottom: 1px solid #e0e0e0; padding-bottom: 0.5rem;">User Administration & Onboarding</h2>
                            <div style="margin-top: 1.5rem;" id="directorUsersConfig">
                                <p style="font-style:italic;">Loading onboarding tools...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-config" class="tab-panel">
                        <div class="academic-card">
                            <h2 style="font-size: 2rem; font-weight: 700; margin-top:0; border-bottom: 1px solid #e0e0e0; padding-bottom: 0.5rem;">Curriculum Configuration Command</h2>
                            <div style="margin-top: 2rem;" id="directorConfigBlock">
                                <p style="font-style:italic;">Loading platform controls...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadDirectorOverviewData();
                await loadDirectorAssignmentsData();
                await loadDirectorSectioningData();
                await loadDirectorParentsData();
                await loadDirectorSubjectsData();
                await loadDirectorUsersData();
                await loadDirectorConfigData();
            }
        }

        // ==========================================
        // STUDENT DASHBOARD CONTROLS
        // ==========================================

        async function loadStudentCoursesData() {
            const res = await apiRequest('student/courses');
            if (!res || !res.success) return;

            document.getElementById('studentSectionName').textContent = res.section_name || 'Roster: Unassigned';
            document.getElementById('studentOverallGpa').textContent = `${res.overall_average}%`;

            const container = document.getElementById('studentCoursesList');
            if (res.courses.length === 0) {
                container.innerHTML = `<div class="academic-card" style="grid-column: 1/-1;"><p style="font-style:italic;">No course subjects are scheduled for your section currently.</p></div>`;
                return;
            }

            container.innerHTML = res.courses.map(course => `
                <div class="academic-card" style="background: #fff; cursor:pointer;" onclick="viewStudentGrades(${course.subject_id}, '${course.subject_name}', '${course.teacher_name}')">
                    <span class="academic-badge" style="background: #fafafa; margin-bottom: 1rem; border-color:var(--primary); color:var(--primary);">Course Subject</span>
                    <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0; color:#2c3e50;">${course.subject_name}</h3>
                    <p style="font-family:'Inter'; font-size: 0.9rem; color: #546e7a; margin-top: 0.25rem; margin-bottom:1.5rem;">Tutor: ${course.teacher_name || 'TBD'}</p>
                    <div style="text-align:right;">
                        <span class="academic-btn-outline" style="padding: 0.4rem 1rem; font-size: 0.75rem;">View Scorecard &rarr;</span>
                    </div>
                </div>
            `).join('');
        }

        async function viewStudentGrades(subjectId, subjectName, teacherName) {
            const res = await apiRequest(`student/course-grades?subject_id=${subjectId}`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 2rem; font-weight:700; margin-top:0; color:#2c3e50;">${subjectName} score ledger</h2>
                <p style="font-family:'Inter'; font-size:0.95rem; color:#546e7a; margin-top: -0.5rem; margin-bottom: 1.5rem;">Tutor: ${teacherName}</p>
                
                <div class="academic-table-container">
                    <table class="academic-table">
                        <thead>
                            <tr>
                                <th>Assessment Title</th>
                                <th>Category</th>
                                <th>Weight</th>
                                <th>Record Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.grades.map(g => `
                                <tr>
                                    <td>${g.title}</td>
                                    <td>${g.type_name}</td>
                                    <td>${(g.weight * 100)}%</td>
                                    <td>
                                        <span class="academic-badge" style="color: ${g.score !== null ? '#2e7d32' : '#c62828'}; border-color: ${g.score !== null ? '#e8f5e9' : '#ffebee'}">
                                            ${g.score !== null ? `${g.score} / ${g.max_score}` : 'Ungraded'}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <div class="academic-card" style="margin-bottom:0; display:flex; justify-content:space-between; align-items:center; border-top-color: var(--primary); padding:1rem 2rem;">
                    <span style="font-family:'Inter'; font-weight:600; font-size:0.95rem; color:#546e7a; text-transform:uppercase;">Normalized Course Average:</span>
                    <span style="font-size:1.8rem; font-weight:700; color:var(--primary);">${res.weighted_average}%</span>
                </div>
            `;
            showModal(modalHtml);
        }

        async function loadStudentFinalEvaluationData() {
            const res = await apiRequest('student/final-evaluation');
            if (!res || !res.success) return;

            const block = document.getElementById('studentFinalEvaluationBlock');

            if (!res.is_active) {
                block.innerHTML = `
                    <div style="border:1px solid #ffcdd2; background: #ffebee; color:#b71c1c; padding:1.5rem; font-family:'Inter'; font-weight:600;">
                        Year-end final evaluations are locked currently by the platform administrator. Scores compiling in progress.
                    </div>
                `;
                return;
            }

            if (res.status === 'pending') {
                block.innerHTML = `
                    <div style="border:1px solid #ffe082; background: #fff8e1; color:#b77f00; padding:1.5rem; font-family:'Inter'; font-weight:600;">
                        Year-end final evaluations are open. Your homeroom teacher is currently compiling class ranks and GPAs. Please hold.
                    </div>
                `;
                return;
            }

            const eval = res.evaluation;
            block.innerHTML = `
                <div class="dashboard-grid">
                    <div class="academic-card" style="margin:0; border-top-color: #2e7d32;">
                        <span style="font-family:'Inter'; font-size:0.85rem; color:#546e7a;">YEAR CUMULATIVE AVERAGE</span>
                        <h2 style="font-size:2.8rem; font-weight:700; margin:0; color:#2e7d32;">${eval.average_score}%</h2>
                    </div>
                    <div class="academic-card" style="margin:0; border-top-color: var(--primary);">
                        <span style="font-family:'Inter'; font-size:0.85rem; color:#546e7a;">OFFICIAL CLASS RANK</span>
                        <h2 style="font-size:2.8rem; font-weight:700; margin:0; color:var(--primary);">#${eval.class_rank}</h2>
                    </div>
                    <div class="academic-card" style="margin:0; border-top-color: ${eval.status === 'pass' ? '#2e7d32' : '#c62828'};">
                        <span style="font-family:'Inter'; font-size:0.85rem; color:#546e7a;">PROMOTION DECISION</span>
                        <h2 style="font-size:2.8rem; font-weight:700; text-transform:uppercase; margin:0; color: ${eval.status === 'pass' ? '#2e7d32' : '#c62828'};">${eval.status}</h2>
                    </div>
                </div>
                <div class="academic-card" style="margin-top:1.5rem; border-top-style:dotted;">
                    <p style="font-family:'Inter'; font-size:0.95rem; color:#546e7a; margin:0;">Signed and verified by homeroom instructor: <strong>${eval.evaluator_name || 'System Administrator'}</strong> on ${new Date(eval.evaluated_at).toLocaleDateString()}</p>
                </div>
            `;
        }

        // ==========================================
        // TEACHER PORTAL CONTROLS
        // ==========================================

        async function loadTeacherPortalData() {
            const res = await apiRequest('teacher/classes');
            if (!res || !res.success) return;

            const grid = document.getElementById('teacherAssignmentsGrid');
            if (res.classes.length === 0) {
                grid.innerHTML = `<div class="academic-card" style="grid-column: 1/-1;"><p style="font-style:italic;">No teaching assignments are mapped to your account by administration.</p></div>`;
            } else {
                grid.innerHTML = res.classes.map(c => `
                    <div class="academic-card" style="background: #fff;">
                        <span class="academic-badge" style="margin-bottom: 1rem; border-color:var(--primary); color:var(--primary);">Grade ${c.grade_level} ${c.stream}</span>
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0; color:#2c3e50;">${c.subject_name}</h3>
                        <p style="font-family:'Inter'; font-size: 0.9rem; color:#546e7a; margin-top: 0.25rem; margin-bottom:1.5rem;">Section Classroom: ${c.section_name}</p>
                        
                        <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                            <button class="academic-btn" style="width:100%; font-size:0.75rem;" onclick="viewClassAssessments(${c.assignment_id}, ${c.section_id}, '${c.subject_name} - ${c.section_name}')">Assessments Core</button>
                            <button class="academic-btn-outline" style="width:100%; font-size:0.75rem;" onclick="viewClassRoster(${c.section_id}, '${c.section_name}')">Roster List</button>
                        </div>
                    </div>
                `).join('');
            }

            const homeroomDiv = document.getElementById('teacherHomeroomConfig');
            if (!res.homeroom_class) {
                homeroomDiv.innerHTML = `
                    <div style="border:1px solid #ffcdd2; background: #ffebee; color:#b71c1c; padding:1.5rem; font-family:'Inter'; font-weight:600;">
                        You are not currently assigned as a homeroom tutor for any classroom section.
                    </div>
                `;
            } else {
                const sect = res.homeroom_class;
                homeroomDiv.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #e0e0e0; padding-bottom:1rem; margin-bottom:2rem;">
                        <div>
                            <h3 style="font-size:1.8rem; font-weight:700; margin:0; color:#2c3e50;">Class Room ${sect.section_name} (Grade ${sect.grade_level})</h3>
                            <span class="academic-badge" style="background:#e8f5e9; border-color:#2e7d32; color:#2e7d32; margin-top:0.5rem;">Homeroom Active</span>
                        </div>
                        <button class="academic-btn" onclick="loadHomeroomEvaluationsTable(${sect.section_id})">Compute Class Ranks & promotion</button>
                    </div>
                    <div id="homeroomEvaluationsRoster">
                        <p style="font-style:italic; color:#546e7a;">Select the rank trigger above to open the promotion roster ledger.</p>
                    </div>
                `;
            }
        }

async function viewClassAssessments(assignmentId, sectionId, className) {
    const res = await apiRequest(`teacher/assessments?assignment_id=${assignmentId}`);

    if (!res || !res.success) return;

    const types = res.assessment_types;

    const modalHtml = `
        <h2 style="font-size: 1.8rem; font-weight:700; color:#2c3e50; margin-top:0;">
            Assessments
        </h2>

        <p style="font-family:'Inter'; font-size:0.95rem; color:#546e7a; margin-top:-0.5rem; margin-bottom:1.5rem;">
            Class Section: ${className}
        </p>

        <div class="academic-card" style="background:#fafafa; padding:1.5rem; margin-bottom:1.5rem; border-top-color:#e0e0e0;">
            
            <h4 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#2c3e50; text-transform:uppercase; letter-spacing:0.05em;">
                Add Assessment Ledger
            </h4>

            <form
                onsubmit="handleCreateAssessment(event, ${assignmentId}, ${sectionId}, '${className.replace(/'/g, "\\'")}')"
                style="display:grid; grid-template-columns:2fr 1fr 1.5fr 1.2fr 1fr; gap:0.5rem; align-items:center;"
            >

                <input
                    type="text"
                    id="newAssTitle"
                    class="academic-input"
                    style="margin-bottom:0;"
                    required
                    placeholder="Title (e.g. Quiz 1)"
                >

                <input
                    type="number"
                    id="newAssMax"
                    class="academic-input"
                    style="margin-bottom:0;"
                    required
                    placeholder="Max Score"
                    min="1"
                    max="1000"
                >

                <select
                    id="newAssType"
                    class="academic-input"
                    style="margin-bottom:0; height:auto;"
                    required
                >
                    ${types.map(t => `
                        <option value="${t.id}">
                            ${t.name} (${t.weight * 100}%)
                        </option>
                    `).join('')}
                </select>

                <input
                    type="date"
                    id="newAssDate"
                    class="academic-input"
                    style="margin-bottom:0;"
                >

                <button
                    type="submit"
                    class="academic-btn"
                    style="padding:0.8rem 1rem;"
                >
                    CREATE
                </button>

            </form>
        </div>

        <div class="academic-table-container">
            <table class="academic-table">

                <thead>
                    <tr>
                        <th>Assessment Ledger</th>
                        <th>Type</th>
                        <th>Weight</th>
                        <th>Max Points</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    ${res.assessments.map(a => `
                        <tr>

                            <td>${a.title}</td>
                            <td>${a.type_name}</td>
                            <td>${a.weight * 100}%</td>
                            <td>${a.max_score}</td>
                            <td>${a.date || '—'}</td>

                            <td style="display:flex; gap:0.35rem; flex-wrap:wrap;">

                                <button
                                    class="academic-btn-outline"
                                    style="padding:0.3rem 0.7rem; font-size:0.72rem;"
                                    onclick="openEditAssessmentForm(
                                        ${a.id},
                                        '${a.title.replace(/'/g, "\\'")}',
                                        ${a.max_score},
                                        ${a.type_id},
                                        '${a.date || ''}',
                                        ${JSON.stringify(types).replace(/"/g, '&quot;')},
                                        ${assignmentId},
                                        ${sectionId},
                                        '${className.replace(/'/g, "\\'")}'
                                    )"
                                >
                                    EDIT
                                </button>

                                <button
                                    class="academic-btn academic-btn-danger"
                                    style="padding:0.3rem 0.7rem; font-size:0.72rem;"
                                    onclick="handleDeleteAssessment(
                                        ${a.id},
                                        ${assignmentId},
                                        ${sectionId},
                                        '${className.replace(/'/g, "\\'")}'
                                    )"
                                >
                                    DEL
                                </button>

                                <button
                                    class="academic-btn academic-btn-success"
                                    style="padding:0.3rem 0.7rem; font-size:0.72rem;"
                                    onclick="loadGradeEntryForm(
                                        ${a.id},
                                        ${sectionId},
                                        '${a.title.replace(/'/g, "\\'")}',
                                        ${a.max_score},
                                        ${assignmentId},
                                        '${className.replace(/'/g, "\\'")}'
                                    )"
                                >
                                    GRADES
                                </button>

                            </td>

                        </tr>
                    `).join('')}
                </tbody>

            </table>
        </div>

        ${res.students && res.students.length > 0 ? `

        <div style="margin-top:1.5rem;">

            <h4 style="color:#2c3e50; font-size:1rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:1rem;">
                Student Grade Book
            </h4>

            <div class="academic-table-container">

                <table class="academic-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Grade Book</th>
                        </tr>
                    </thead>

                    <tbody>
                        ${res.students.map(s => `
                            <tr>

                                <td>${s.student_id}</td>
                                <td>${s.full_name}</td>

                                <td>
                                    <button
                                        class="academic-btn"
                                        style="padding:0.3rem 0.9rem; font-size:0.75rem;"
                                        onclick="openStudentGradesEditor(
                                            ${s.id},
                                            '${s.full_name.replace(/'/g, "\\'")}',
                                            ${assignmentId},
                                            ${sectionId},
                                            '${className.replace(/'/g, "\\'")}'
                                        )"
                                    >
                                        VIEW / GRADE
                                    </button>
                                </td>

                            </tr>
                        `).join('')}
                    </tbody>

                </table>

            </div>

        </div>

        ` : ''}

    `;

    showModal(modalHtml);
}


        async function handleCreateAssessment(e, assignmentId, sectionId, className) {
            e.preventDefault();
            const title = document.getElementById('newAssTitle').value;
            const max_score = document.getElementById('newAssMax').value;
            const type_id = document.getElementById('newAssType').value;
            const date = document.getElementById('newAssDate').value;

            const res = await apiRequest('teacher/create-assessment', 'POST', {
                assignment_id: assignmentId,
                title,
                max_score,
                type_id,
                date
            });

            if (res && res.success) {
                alert(res.message);
                viewClassAssessments(assignmentId, sectionId, className);
            } else {
                alert(res.message || "Failed to create assessment.");
            }
        }

        async function openEditAssessmentForm(assessmentId, title, maxScore, typeId, date, types, assignmentId, sectionId, className) {
            const modalHtml = `
                <h2 style="font-size: 1.8rem; font-weight:700; color:#2c3e50; margin-top:0;">Edit Assessment</h2>
                <form onsubmit="handleEditAssessment(event, ${assessmentId}, ${assignmentId}, ${sectionId}, '${className}')" style="display:flex; flex-direction:column; gap:1rem; margin-top:1.5rem;">
                    <div>
                        <label style="display:block; margin-bottom:0.5rem; color:#546e7a; font-family:'Inter'; font-size:0.85rem;">Assessment Title</label>
                        <input type="text" id="editAssTitle" class="academic-input" required value="${title}">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.5rem; color:#546e7a; font-family:'Inter'; font-size:0.85rem;">Max Score</label>
                        <input type="number" id="editAssMax" class="academic-input" required value="${maxScore}" min="1" max="1000">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.5rem; color:#546e7a; font-family:'Inter'; font-size:0.85rem;">Assessment Type</label>
                        <select id="editAssType" class="academic-input" style="height:auto;" required>
                            ${types.map(t => `<option value="${t.id}" ${t.id == typeId ? 'selected' : ''}>${t.name} (${t.weight * 100}%)</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.5rem; color:#546e7a; font-family:'Inter'; font-size:0.85rem;">Assessment Date</label>
                        <input type="date" id="editAssDate" class="academic-input" value="${date}">
                    </div>
                    <div style="display:flex; gap:0.5rem; margin-top:0.5rem; justify-content:flex-end;">
                        <button type="button" class="academic-btn-outline" onclick="viewClassAssessments(${assignmentId}, ${sectionId}, '${className}')">Cancel</button>
                        <button type="submit" class="academic-btn">Save Changes</button>
                    </div>
                </form>
            `;
            showModal(modalHtml);
        }

        async function handleEditAssessment(e, assessmentId, assignmentId, sectionId, className) {
            e.preventDefault();
            const title = document.getElementById('editAssTitle').value;
            const max_score = document.getElementById('editAssMax').value;
            const type_id = document.getElementById('editAssType').value;
            const date = document.getElementById('editAssDate').value;

            const res = await apiRequest('teacher/update-assessment', 'POST', {
                assessment_id: assessmentId,
                title,
                max_score,
                type_id,
                date
            });

            if (res && res.success) {
                alert(res.message);
                viewClassAssessments(assignmentId, sectionId, className);
            } else {
                alert(res.message || "Failed to update assessment.");
            }
        }

        async function handleDeleteAssessment(assessmentId, assignmentId, sectionId, className) {
            if (!confirm('Delete this assessment and ALL associated scores? This is permanent!')) return;
            const res = await apiRequest('teacher/delete-assessment', 'POST', { assessment_id: assessmentId });
            if (res && res.success) {
                alert(res.message);
                viewClassAssessments(assignmentId, sectionId, className);
            } else {
                alert(res.message || 'Failed to delete assessment.');
            }
        }

        async function openStudentGradesEditor(studentId, studentName, assignmentId, sectionId, className) {
            const res = await apiRequest(`teacher/student-assignment-grades?student_id=${studentId}&assignment_id=${assignmentId}`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 1.8rem; font-weight:700; color:#2c3e50; margin-top:0;">Grade Book Panel</h2>
                <p style="font-family:'Inter'; font-size:0.95rem; color:#546e7a; margin-top:-0.5rem; margin-bottom:1.5rem;">Student: <span style="color:var(--primary); font-weight:600;">${studentName}</span></p>
                <form onsubmit="submitStudentSingleGrades(event, ${studentId}, ${assignmentId}, ${sectionId}, '${className}')">
                    <div class="academic-table-container" style="margin-bottom:1.5rem;">
                        <table class="academic-table">
                            <thead>
                                <tr>
                                    <th>Assessment</th>
                                    <th>Category</th>
                                    <th>Max Score</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${res.grades.map(g => `
                                    <tr>
                                        <td><strong>${g.title}</strong></td>
                                        <td>${g.type_name}</td>
                                        <td>${g.max_score}</td>
                                        <td>
                                            <input type="number" step="0.01" class="academic-input student-single-score-input"
                                                   data-assessment-id="${g.assessment_id}" min="0" max="${g.max_score}"
                                                   style="margin-bottom:0; width:110px;" placeholder="Score"
                                                   value="${g.score !== null ? g.score : ''}">
                                        </td>
                                    </tr>
                                `).join('')}
                                ${res.grades.length === 0 ? '<tr><td colspan="4" style="text-align:center; font-style:italic; color:#546e7a;">No assessments yet. Create assessments first.</td></tr>' : ''}
                            </tbody>
                        </table>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                        <button type="button" class="academic-btn-outline" onclick="viewClassAssessments(${assignmentId}, ${sectionId}, '${className}')">Back</button>
                        <button type="submit" class="academic-btn">SAVE RESULTS</button>
                    </div>
                </form>
            `;
            showModal(modalHtml);
        }

        async function submitStudentSingleGrades(e, studentId, assignmentId, sectionId, className) {
            e.preventDefault();
            const scores = [];
            document.querySelectorAll('.student-single-score-input').forEach(input => {
                scores.push({ assessment_id: input.getAttribute('data-assessment-id'), score: input.value });
            });
            const res = await apiRequest('teacher/submit-student-grades', 'POST', { student_id: studentId, scores });
            if (res && res.success) {
                alert(res.message);
                viewClassAssessments(assignmentId, sectionId, className);
            } else {
                alert(res.message || 'Failed to save grades.');
            }
        }

        async function loadGradeEntryForm(assessmentId, sectionId, assTitle, maxScore) {
            const studentsRes = await apiRequest(`teacher/class-students?section_id=${sectionId}`);
            if (!studentsRes || !studentsRes.success) return;

            const html = `
                <h2 style="font-size: 1.8rem; font-weight:700; color:#2c3e50; margin-top:0;">ENTER SCORINGS SHEET</h2>
                <p style="font-family:'Inter'; font-size:0.95rem; color:#546e7a; margin-top:-0.5rem; margin-bottom:1.5rem;">Ledger: ${assTitle} (Out of ${maxScore} points)</p>
                
                <form onsubmit="submitStudentGrades(event, ${assessmentId}, ${sectionId})">
                    <div class="academic-table-container">
                        <table class="academic-table">
                            <thead>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Raw Score Point (Max ${maxScore})</th>
                            </thead>
                            <tbody>
                                ${studentsRes.students.map(s => `
                                    <tr>
                                        <td>${s.student_id}</td>
                                        <td>${s.full_name}</td>
                                        <td>
                                            <input type="number" step="0.01" class="academic-input student-score-input" 
                                                   data-student-id="${s.id}" min="0" max="${maxScore}" style="margin-bottom:0; width:120px;" placeholder="Score">
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="academic-btn">SAVE RECORDS</button>
                    </div>
                </form>
            `;
            showModal(html);
        }

        async function submitStudentGrades(e, assessmentId, sectionId) {
            e.preventDefault();
            const scores = [];
            document.querySelectorAll('.student-score-input').forEach(input => {
                scores.push({
                    student_id: input.getAttribute('data-student-id'),
                    score: input.value
                });
            });

            const res = await apiRequest('teacher/submit-grades', 'POST', {
                assessment_id: assessmentId,
                scores
            });

            if (res && res.success) {
                alert(res.message);
                closeModal();
            } else {
                alert(res.message || "Failed to save grades.");
            }
        }

        // Homeroom Year-End rankings
        async function loadHomeroomEvaluationsTable(sectionId) {
            const res = await apiRequest(`teacher/homeroom-roster?section_id=${sectionId}`);
            if (!res || !res.success) {
                alert(res.message || "Unable to load homeroom sheet.");
                return;
            }

            const rosterArea = document.getElementById('homeroomEvaluationsRoster');
            
            if (!res.is_final_active) {
                rosterArea.innerHTML = `
                    <div style="border:1px solid #ffcdd2; background: #ffebee; color:#b71c1c; padding:1.5rem; font-family:'Inter'; font-weight:600; margin-top:1.5rem;">
                        COMPLIANCE LOCK: Year-End final evaluations are locked currently by administration.
                    </div>
                `;
                return;
            }

            rosterArea.innerHTML = `
                <div class="academic-card" style="border-top-color:#2e7d32; background:#e8f5e9; margin-bottom:1.5rem;">
                    <p style="font-family:'Inter'; font-weight:600; font-size:0.95rem; color:#1b5e20; margin:0;">CLASS COMPLIANCE ALERT</p>
                    <p style="font-family:'Inter'; font-size:0.9rem; color:#2e7d32; margin:0.25rem 0 0;">Weighted averages, class rankings, and promotion pass/fail triggers computed below automatically using Grading Service formula.</p>
                </div>
                
                <form onsubmit="submitHomeroomEvaluations(event, ${sectionId})">
                    <div class="academic-table-container">
                        <table class="academic-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Class Term Average</th>
                                    <th>Promotion Decision</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${res.roster.map(s => `
                                    <tr>
                                        <td><strong>#${s.rank}</strong></td>
                                        <td>${s.student_code}</td>
                                        <td>${s.full_name}</td>
                                        <td>
                                            <span style="font-weight:600; font-family:'Inter'; background: #fafafa; border:1px solid #e0e0e0; padding:0.2rem 0.5rem;">
                                                ${s.average}%
                                            </span>
                                            <input type="hidden" class="eval-student-avg" data-student-id="${s.id}" value="${s.average}">
                                            <input type="hidden" class="eval-student-rank" data-student-id="${s.id}" value="${s.rank}">
                                        </td>
                                        <td>
                                            <select class="academic-input eval-student-status" data-student-id="${s.id}" style="margin-bottom:0; width:180px; height:auto;">
                                                <option value="pass" ${s.status === 'pass' || s.average >= 50 ? 'selected' : ''}>PROMOTED (PASS)</option>
                                                <option value="fail" ${s.status === 'fail' || s.average < 50 ? 'selected' : ''}>FAILED (FAIL)</option>
                                            </select>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="academic-btn">SUBMIT COMPILED YEAR-END RECORD</button>
                    </div>
                </form>
            `;
        }

        async function submitHomeroomEvaluations(e, sectionId) {
            e.preventDefault();
            const evaluations = [];
            
            document.querySelectorAll('.eval-student-status').forEach(select => {
                const studentId = select.getAttribute('data-student-id');
                const status = select.value;
                
                const avgInput = document.querySelector(`.eval-student-avg[data-student-id="${studentId}"]`);
                const rankInput = document.querySelector(`.eval-student-rank[data-student-id="${studentId}"]`);
                
                evaluations.push({
                    student_id: studentId,
                    average: avgInput ? avgInput.value : 0,
                    rank: rankInput ? rankInput.value : 99,
                    status: status
                });
            });

            const res = await apiRequest('teacher/submit-evaluations', 'POST', {
                section_id: sectionId,
                evaluations
            });

            if (res && res.success) {
                alert(res.message);
                loadHomeroomEvaluationsTable(sectionId);
            } else {
                alert(res.message || "Failed to submit evaluations.");
            }
        }

        // ==========================================
        // PARENT PORTAL CONTROLS
        // ==========================================

        async function loadParentPortalData() {
            const res = await apiRequest('parent/students');
            if (!res || !res.success) return;

            const grid = document.getElementById('parentStudentsGrid');
            if (res.students.length === 0) {
                grid.innerHTML = `<div class="academic-card" style="grid-column: 1/-1;"><p style="font-style:italic;">No student files are linked to your portal. Please contact School Registry.</p></div>`;
            } else {
                grid.innerHTML = res.students.map(s => `
                    <div class="academic-card" style="background: #fff;">
                        <span class="academic-badge" style="margin-bottom: 1rem; border-color:var(--primary); color:var(--primary);">Dependent File</span>
                        <h3 style="font-size: 1.8rem; font-weight: 700; margin: 0; color:#2c3e50;">${s.full_name}</h3>
                        <p style="font-family:'Inter'; font-size: 0.9rem; color:#546e7a; margin-top: 0.25rem; margin-bottom:1.5rem;">Grade Stream: ${s.section_name || 'Unassigned'}</p>
                        
                        <div style="margin-bottom: 1.5rem; padding: 1rem; border: 1px solid #e0e0e0; background: #fafafa; display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-family:'Inter'; font-size:0.85rem; color:#546e7a;">COMPLETED AVERAGE POINT:</span>
                            <span style="font-size:1.4rem; font-weight:700; color:var(--primary);">${s.overall_average}%</span>
                        </div>

                        <div style="display:flex; gap:0.5rem;">
                            <button class="academic-btn" style="width:100%; font-size:0.75rem;" onclick="viewChildAcademicProgress(${s.id}, '${s.full_name}')">Track Scorecards</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        async function viewChildAcademicProgress(studentId, studentName) {
            const res = await apiRequest(`student/courses`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 2rem; font-weight:700; color:#2c3e50; margin-top:0;">Progress Tracker Ledger</h2>
                <p style="font-family:'Inter'; font-size:0.95rem; color:#546e7a; margin-top:-0.5rem; margin-bottom:1.5rem;">Dependent File: ${studentName}</p>

                <div class="academic-card" style="background: #fafafa; border-top-color:#e0e0e0; margin-bottom: 2rem; padding:1.25rem 2rem;">
                    <p style="font-family:'Inter'; font-weight:600; font-size:0.95rem; color:#546e7a; margin:0;">COMBINED GPAS CUMULATIVE: <strong style="color:var(--primary); font-size:1.25rem;">${res.overall_average}%</strong></p>
                </div>

                <div class="academic-table-container">
                    <table class="academic-table">
                        <thead>
                            <tr>
                                <th>Academic Course</th>
                                <th>Instructor</th>
                                <th>Ledger Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.courses.map(c => `
                                <tr>
                                    <td>${c.subject_name}</td>
                                    <td>${c.teacher_name || 'TBD'}</td>
                                    <td>
                                        <button class="academic-btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.75rem;"
                                                onclick="viewStudentGrades(${studentId}, '${c.subject_name}', '${c.teacher_name}')">
                                            VIEW RECORD
                                        </button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            showModal(modalHtml);
        }

        // ==========================================
        // DIRECTOR PORTAL CONTROLS
        // ==========================================

        async function loadDirectorOverviewData() {
            const res = await apiRequest('director/stats');
            if (!res || !res.success) return;

            const grid = document.getElementById('directorStatsGrid');
            const stats = res.stats;
            grid.innerHTML = `
                <div class="academic-card" style="border-top-color: var(--primary);">
                    <h2 style="font-size: 3rem; font-weight: 700; margin: 0; color:var(--primary);">${stats.total_students}</h2>
                    <p style="font-family:'Inter'; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:#546e7a; letter-spacing:0.05em; margin-top: 0.5rem;">Total Pupils Enrolled</p>
                </div>
                <div class="academic-card" style="border-top-color: #2e7d32;">
                    <h2 style="font-size: 3rem; font-weight: 700; margin: 0; color:#2e7d32;">${stats.total_teachers}</h2>
                    <p style="font-family:'Inter'; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:#546e7a; letter-spacing:0.05em; margin-top: 0.5rem;">Academic Faculty</p>
                </div>
                <div class="academic-card" style="border-top-color: #b77f00;">
                    <h2 style="font-size: 3rem; font-weight: 700; margin: 0; color:#b77f00;">${stats.total_subjects}</h2>
                    <p style="font-family:'Inter'; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:#546e7a; letter-spacing:0.05em; margin-top: 0.5rem;">Approved Subjects</p>
                </div>
                <div class="academic-card" style="border-top-color: #37474f;">
                    <h2 style="font-size: 3rem; font-weight: 700; margin: 0; color:#37474f;">${stats.total_sections}</h2>
                    <p style="font-family:'Inter'; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:#546e7a; letter-spacing:0.05em; margin-top: 0.5rem;">Section classrooms</p>
                </div>
            `;
        }

        async function loadDirectorAssignmentsData() {
            const res = await apiRequest('director/assignment-data');
            if (!res || !res.success) return;

            const container = document.getElementById('directorAssignmentsConfig');
            
            container.innerHTML = `
                <div class="academic-card" style="background:#fafafa; border-top-color:#e0e0e0; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.1rem; font-weight:700; color:#2c3e50;">Schedule Faculty to Course Class</h3>
                    <form onsubmit="handleAssignTeacher(event)" style="display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:0.5rem;">
                        <select id="assignTeacherSelect" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Teacher --</option>
                            ${res.teachers.map(t => `<option value="${t.id}">${t.full_name} (${t.specialization || 'Tutor'})</option>`).join('')}
                        </select>
                        <select id="assignSubjectSelect" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Subject --</option>
                            ${res.subjects.map(s => `<option value="${s.id}">${s.name} (Grade ${s.grade_level})</option>`).join('')}
                        </select>
                        <select id="assignSectionSelect" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Class Section --</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - ${sec.section_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="academic-btn">SCHEDULE</button>
                    </form>
                </div>

                <div class="academic-card" style="background:#fafafa; border-top-color:#e0e0e0; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.1rem; font-weight:700; color:#2c3e50;">Set homeroom teacher assignment</h3>
                    <form onsubmit="handleAssignHomeroom(event)" style="display:grid; grid-template-columns: 1fr 1fr auto; gap:0.5rem;">
                        <select id="homeroomSectionSelect" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Select Class --</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - Section ${sec.section_name} (${sec.homeroom_teacher_name ? `Current Homeroom: ${sec.homeroom_teacher_name}` : 'No Homeroom Assigned'})</option>`).join('')}
                        </select>
                        <select id="homeroomTeacherSelect" class="academic-input" style="margin-bottom:0; height:auto;">
                            <option value="">-- Set Unassigned --</option>
                            ${res.teachers.map(t => `<option value="${t.id}">${t.full_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="academic-btn-outline" style="padding: 0.8rem 1.5rem;">SET HOMEROOM</button>
                    </form>
                </div>

                <h3 style="font-size:1.4rem; font-weight:700; color:#2c3e50; margin-top:2rem;">Allocated Roster Schedules</h3>
                <div class="academic-table-container">
                    <table class="academic-table">
                        <thead>
                            <tr>
                                <th>Allocated Tutor</th>
                                <th>Subject Course</th>
                                <th>Section Classroom</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.assignments.map(a => `
                                <tr>
                                    <td>${a.teacher_name}</td>
                                    <td>${a.subject_name}</td>
                                    <td>Grade ${a.grade_level} - Section ${a.section_name}</td>
                                    <td>
                                        <button class="academic-btn-outline academic-btn-danger" style="padding:0.3rem 0.8rem; font-size:0.75rem;" onclick="removeTeacherAssignment(${a.assignment_id})">REMOVE</button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function handleAssignTeacher(e) {
            e.preventDefault();
            const teacher_id = document.getElementById('assignTeacherSelect').value;
            const subject_id = document.getElementById('assignSubjectSelect').value;
            const section_id = document.getElementById('assignSectionSelect').value;

            const res = await apiRequest('director/assign-teacher', 'POST', { teacher_id, subject_id, section_id });
            if (res && res.success) {
                alert(res.message);
                loadDirectorAssignmentsData();
            } else {
                alert(res.message || "Failed to schedule teaching assignment.");
            }
        }

        async function handleAssignHomeroom(e) {
            e.preventDefault();
            const section_id = document.getElementById('homeroomSectionSelect').value;
            const teacher_id = document.getElementById('homeroomTeacherSelect').value;

            const res = await apiRequest('director/assign-homeroom', 'POST', { section_id, teacher_id });
            if (res && res.success) {
                alert(res.message);
                loadDirectorAssignmentsData();
            } else {
                alert(res.message || "Failed to set homeroom assignment.");
            }
        }

        async function removeTeacherAssignment(assignmentId) {
            if (!confirm("Are you sure you want to remove this teaching assignment schedule?")) return;
            const res = await apiRequest('director/remove-assignment', 'POST', { assignment_id: assignmentId });
            if (res && res.success) {
                alert(res.message);
                loadDirectorAssignmentsData();
            } else {
                alert(res.message || "Failed to delete assignment.");
            }
        }

        async function loadDirectorSectioningData() {
            const res = await apiRequest('director/student-sectioning-data');
            if (!res || !res.success) return;

            const container = document.getElementById('directorSectioningConfig');
            
            container.innerHTML = `
                <div class="academic-card" style="background:#fafafa; border-top-color:#e0e0e0; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.1rem; font-weight:700; color:#2c3e50;">Auto-Sectioning (Random Assignment)</h3>
                    <p style="font-family:'Inter'; font-size:0.9rem; color:#546e7a; margin-top:-0.5rem; margin-bottom:1rem;">
                        Distributes all unsectioned students in the registry evenly across active sections of Grade Level 10.
                    </p>
                    <form onsubmit="handleRandomSectioning(event)" style="display:grid; grid-template-columns: 2fr auto; gap:0.5rem;">
                        <select id="randomSectioningGrade" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Grade Level --</option>
                            <option value="10">Grade 10 General</option>
                        </select>
                        <button type="submit" class="academic-btn">RUN AUTO DISTRIBUTION</button>
                    </form>
                </div>

                <div class="academic-card" style="background:#fafafa; border-top-color:#e0e0e0; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.1rem; font-weight:700; color:#2c3e50;">Manual Roster Transfer (Preferred Assignment)</h3>
                    <form onsubmit="handleAssignStudentSection(event)" style="display:grid; grid-template-columns: 1fr 1fr auto; gap:0.5rem;">
                        <select id="assignStudentSelect" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Student profile --</option>
                            ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_code}) [${s.section_name ? `Class: ${s.section_name}` : 'Unassigned'}]</option>`).join('')}
                        </select>
                        <select id="studentSectionSelect" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Target Section --</option>
                            <option value="">Unassign / Remove from Classroom</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - ${sec.section_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="academic-btn-outline" style="padding: 0.8rem 1.5rem;">SAVE TRANSFER</button>
                    </form>
                </div>

                <h3 style="font-size:1.4rem; font-weight:700; color:#2c3e50; margin-top:2rem;">Student Directory</h3>
                <div class="academic-table-container">
                    <table class="academic-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Email Address</th>
                                <th>Allocated Class</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.students.map(s => `
                                <tr>
                                    <td>${s.student_code}</td>
                                    <td>${s.full_name}</td>
                                    <td>${s.email || 'N/A'}</td>
                                    <td>
                                        <span class="academic-badge" style="color: ${s.section_name ? '#2e7d32' : '#c62828'}; border-color: ${s.section_name ? '#e8f5e9' : '#ffebee'}">
                                            ${s.section_name ? `Grade ${s.grade_level} - ${s.section_name}` : 'Unassigned'}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function handleRandomSectioning(e) {
            e.preventDefault();
            const grade_level = document.getElementById('randomSectioningGrade').value;
            const res = await apiRequest('director/random-sectioning', 'POST', { grade_level });
            if (res && res.success) {
                alert(res.message);
                loadDirectorSectioningData();
            } else {
                alert(res.message || "Failed to distribute randomly.");
            }
        }

        async function handleAssignStudentSection(e) {
            e.preventDefault();
            const student_id = document.getElementById('assignStudentSelect').value;
            const section_id = document.getElementById('studentSectionSelect').value;

            const res = await apiRequest('director/assign-student-section', 'POST', { student_id, section_id });
            if (res && res.success) {
                alert(res.message);
                loadDirectorSectioningData();
            } else {
                alert(res.message || "Failed to update student section.");
            }
        }

        async function loadDirectorParentsData() {
            const res = await apiRequest('director/parents-list');
            if (!res || !res.success) return;

            const container = document.getElementById('directorParentsConfig');
            
            container.innerHTML = `
                <div class="academic-card" style="background:#fafafa; border-top-color:#e0e0e0; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.1rem; font-weight:700; color:#2c3e50;">Link Parent to Student Profile</h3>
                    <form onsubmit="handleLinkParent(event)" style="display:grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap:0.5rem;">
                        <input type="text" id="parentName" class="academic-input" style="margin-bottom:0;" required placeholder="Parent Full Name">
                        <input type="email" id="parentEmail" class="academic-input" style="margin-bottom:0;" required placeholder="Parent Email">
                        <input type="text" id="parentPhone" class="academic-input" style="margin-bottom:0;" required placeholder="Phone Number">
                        <select id="parentStudentSelect" class="academic-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Target Child --</option>
                            ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_id})</option>`).join('')}
                        </select>
                        <button type="submit" class="academic-btn">LINK & SAVE</button>
                    </form>
                </div>

                <h3 style="font-size:1.4rem; font-weight:700; color:#2c3e50; margin-top:2rem;">Registered Parents Directory</h3>
                <div class="academic-table-container">
                    <table class="academic-table">
                        <thead>
                            <tr>
                                <th>Parent Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.parents.map(p => `
                                <tr>
                                    <td>${p.full_name}</td>
                                    <td>${p.email}</td>
                                    <td>${p.phone || 'N/A'}</td>
                                    <td>
                                        <span class="academic-badge" style="color: #2e7d32; background: #e8f5e9; border-color: #2e7d32;">Linked</span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function handleLinkParent(e) {
            e.preventDefault();
            const full_name = document.getElementById('parentName').value;
            const email = document.getElementById('parentEmail').value;
            const phone = document.getElementById('parentPhone').value;
            const student_id = document.getElementById('parentStudentSelect').value;

            const res = await apiRequest('director/create-parent', 'POST', {
                full_name, email, phone, student_id, relation_type: 'Father'
            });

            if (res && res.success) {
                alert(res.message);
                loadDirectorParentsData();
            } else {
                alert(res.message || "Failed to link parent.");
            }
        }

        // ==========================================
        // DIRECTOR SUBJECTS & USERS (NEW)
        // ==========================================
        
        async function loadDirectorSubjectsData() {
            const res = await apiRequest('director/subjects');
            if (!res || !res.success) return;

            const container = document.getElementById('directorSubjectsConfig');
            container.innerHTML = `
                <div style="display:grid; grid-template-columns: 1fr 2fr; gap:2rem;">
                    <div>
                        <h4 style="margin-top:0; font-family:'Inter'; font-weight:600; color:#2c3e50;">Add New Subject</h4>
                        <form onsubmit="handleAddSubject(event)">
                            <label class="form-lbl">Subject Name</label>
                            <input type="text" id="newSubjectName" class="academic-input" required placeholder="e.g. Advanced Calculus">
                            <label class="form-lbl">Grade Level</label>
                            <input type="number" id="newSubjectGrade" class="academic-input" required min="1" max="12" placeholder="e.g. 10">
                            <button type="submit" class="academic-btn" style="width:100%;">Create Subject</button>
                        </form>
                    </div>
                    <div>
                        <h4 style="margin-top:0; font-family:'Inter'; font-weight:600; color:#2c3e50;">Active Subjects</h4>
                        <div class="academic-table-container">
                            <table class="academic-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${res.subjects.map(sub => `
                                        <tr>
                                            <td>${sub.name}</td>
                                            <td>Grade ${sub.grade_level}</td>
                                            <td>
                                                <button class="academic-btn-danger" style="padding: 0.3rem 0.8rem; font-size: 0.75rem;" onclick="handleDeleteSubject(${sub.id})">Delete</button>
                                            </td>
                                        </tr>
                                    `).join('')}
                                    ${res.subjects.length === 0 ? '<tr><td colspan="3" style="text-align:center; font-style:italic;">No subjects found.</td></tr>' : ''}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        }

        async function handleAddSubject(e) {
            e.preventDefault();
            const name = document.getElementById('newSubjectName').value;
            const grade_level = document.getElementById('newSubjectGrade').value;

            const res = await apiRequest('director/subjects', 'POST', { name, grade_level });
            if (res && res.success) {
                alert(res.message);
                loadDirectorSubjectsData();
                loadDirectorAssignmentsData(); // refresh dropdowns
            } else {
                alert(res.message || "Failed to add subject.");
            }
        }

        async function handleDeleteSubject(id) {
            if(!confirm("Are you sure you want to delete this subject?")) return;
            const res = await apiRequest('director/subjects', 'DELETE', { subject_id: id });
            if (res && res.success) {
                alert(res.message);
                loadDirectorSubjectsData();
                loadDirectorAssignmentsData();
            } else {
                alert(res.message || "Failed to delete subject.");
            }
        }

        async function loadDirectorUsersData() {
            const container = document.getElementById('directorUsersConfig');
            container.innerHTML = `
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:2rem;">
                    <!-- Single User Form -->
                    <div style="background: #fafafa; border:1px solid #e0e0e0; padding:1.5rem;">
                        <h3 style="margin-top:0; font-size:1.5rem; color:#2c3e50; border-bottom:1px solid #e0e0e0; padding-bottom:0.5rem;">Single User Registration</h3>
                        <form onsubmit="handleSingleUserReg(event)">
                            <div style="margin-bottom:1rem;">
                                <label class="form-lbl">Full Name</label>
                                <input type="text" id="suName" class="academic-input" required placeholder="John Doe">
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label class="form-lbl">Email Address</label>
                                <input type="email" id="suEmail" class="academic-input" required placeholder="john@example.com">
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label class="form-lbl">Role</label>
                                <select id="suRole" class="academic-input" style="height:auto;">
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher / Faculty</option>
                                </select>
                            </div>
                            <button type="submit" class="academic-btn" style="width:100%;" id="suBtn">Register Account</button>
                        </form>
                    </div>

                    <!-- Mass User Form -->
                    <div style="background: #fafafa; border:1px solid #e0e0e0; padding:1.5rem;">
                        <h3 style="margin-top:0; font-size:1.5rem; color:#2c3e50; border-bottom:1px solid #e0e0e0; padding-bottom:0.5rem;">Mass CSV Registration</h3>
                        <p style="font-family:'Inter'; font-size:0.9rem; color:#546e7a;">Upload a CSV with columns <b>Full Name</b> and <b>Email</b>.</p>
                        <form onsubmit="handleMassReg(event)">
                            <div style="margin-bottom:1rem;">
                                <label class="form-lbl">Select CSV File</label>
                                <input type="file" id="muFile" class="academic-input" accept=".csv" required style="padding:0.6rem;">
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label class="form-lbl">Target Role</label>
                                <select id="muRole" class="academic-input" style="height:auto;">
                                    <option value="student">Students</option>
                                    <option value="teacher">Teachers / Faculty</option>
                                </select>
                            </div>
                            <button type="submit" class="academic-btn" style="width:100%; background:var(--secondary); border-color:var(--secondary);" id="muBtn">Process CSV Import</button>
                        </form>
                    </div>
                </div>
            `;
        }

        async function handleSingleUserReg(e) {
            e.preventDefault();
            const btn = document.getElementById('suBtn');
            btn.disabled = true; btn.textContent = 'Processing...';

            const payload = {
                full_name: document.getElementById('suName').value,
                email: document.getElementById('suEmail').value,
                role: document.getElementById('suRole').value
            };

            const res = await apiRequest('director/create-user', 'POST', payload);
            btn.disabled = false; btn.textContent = 'Register Account';

            if(res && res.success) {
                alert(res.message + "\\nUser ID: " + res.id_code + "\\nPassword: " + res.password);
                e.target.reset();
                loadDirectorOverviewData();
            } else {
                alert((res ? res.message : "Error creating user."));
            }
        }

        async function handleMassReg(e) {
            e.preventDefault();
            const fileInput = document.getElementById('muFile');
            if(!fileInput.files[0]) return;

            const btn = document.getElementById('muBtn');
            btn.disabled = true; btn.textContent = 'Uploading...';

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('role', document.getElementById('muRole').value);
            formData.append('school_id', localStorage.getItem('school_id') || 1);

            try {
                const response = await fetch('/api/users/mass-register', {
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                    body: formData
                });
                const res = await response.json();
                
                btn.disabled = false; btn.textContent = 'Process CSV Import';

                if (res && res.success) {
                    let msg = "Import complete!\\nSuccessfully imported: " + res.count + " rows.";
                    if (res.skipped > 0) msg += "\\nSkipped/Failed: " + res.skipped + " rows.";
                    alert(msg);
                    e.target.reset();
                    loadDirectorOverviewData();
                } else {
                    alert(res ? res.message : "Failed to import CSV.");
                }
            } catch(err) {
                btn.disabled = false; btn.textContent = 'Process CSV Import';
                alert("Network error processing CSV.");
            }
        }

        async function loadDirectorConfigData() {
            const statsRes = await apiRequest('director/stats');
            if (!statsRes || !statsRes.success) return;

            const isFinalActive = statsRes.stats.is_final_active;
            const container = document.getElementById('directorConfigBlock');

            container.innerHTML = `
                <div class="academic-card" style="border-top-color: ${isFinalActive ? '#2e7d32' : 'var(--primary)'}; background: ${isFinalActive ? '#fafafa' : '#fff'}; border-style: ${isFinalActive ? 'solid' : 'dashed'};">
                    <h3 style="margin-top:0; font-size:1.5rem; font-weight:700; color:#2c3e50;">Year-End Assessment Trigger</h3>
                    <p style="font-family:'Inter'; font-size:0.95rem; color:#546e7a; margin-bottom:1.5rem;">
                        Toggling this trigger decides the active "End of Year" phase. When active, all homeroom teachers gain access to computing average GPAs, class rankings, and submitting student promotion decisions (pass/fail status). When disabled, teachers cannot modify compiled rosters.
                    </p>
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <span style="font-family:'Inter'; font-weight:600; font-size:0.95rem; color:#546e7a;">STATUS: </span>
                            <span class="academic-badge" style="color: ${isFinalActive ? '#2e7d32' : '#c62828'}; border-color: ${isFinalActive ? '#e8f5e9' : '#ffebee'}">
                                ${isFinalActive ? 'OPEN / ACTIVE' : 'CLOSED / INACTIVE'}
                            </span>
                        </div>
                        <button class="academic-btn ${isFinalActive ? 'academic-btn-danger' : 'academic-btn-success'}" onclick="toggleFinalMode(${isFinalActive ? 0 : 1})">
                            ${isFinalActive ? 'CLOSE YEAR-END PORTALS' : 'OPEN YEAR-END PORTALS'}
                        </button>
                    </div>
                </div>
            `;
        }

        async function toggleFinalMode(activeState) {
            const res = await apiRequest('director/toggle-final-assessment', 'POST', { active: activeState });
            if (res && res.success) {
                alert(res.message);
                loadDirectorConfigData();
                loadDirectorOverviewData();
            } else {
                alert(res.message || "Failed to update configuration.");
            }
        }

        // ==========================================
        // DYNAMIC MESSENGER MODULE (All Roles)
        // ==========================================

        async function loadMessenger(role) {
            const containerId = `${role}ChatPanel`;
            const container = document.getElementById(containerId);
            if (!container) return;

            const res = await apiRequest('communications/list');
            if (!res || !res.success) {
                container.innerHTML = `<p style="font-style:italic;">Failed to load message ledger.</p>`;
                return;
            }

            container.innerHTML = `
                <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.5rem;">
                    <div>
                        <h4 style="margin-top:0; font-family:'Inter'; font-weight:600; font-size:1rem; border-bottom:1px solid #e0e0e0; padding-bottom:0.5rem; color:#2c3e50;">Send New Message</h4>
                        <form onsubmit="handleSendMessage(event, '${role}')">
                            <select id="chatTargetSelect" class="academic-input" style="height:auto;" required>
                                <option value="">-- Choose Contact --</option>
                                ${res.contacts.map(c => `<option value="${c.id}" data-role="${c.role}">${c.full_name} (${c.role.toUpperCase()})</option>`).join('')}
                            </select>
                            <textarea id="chatMessageText" class="academic-input" style="height:100px; resize:none;" required placeholder="Type your message here..."></textarea>
                            <button type="submit" class="academic-btn" style="width:100%;">SEND MESSAGE</button>
                        </form>
                    </div>

                    <div style="border-left:1px solid #e0e0e0; padding-left:1.5rem;">
                        <h4 style="margin-top:0; font-family:'Inter'; font-weight:600; font-size:1rem; border-bottom:1px solid #e0e0e0; padding-bottom:0.5rem; color:#2c3e50;">Conversations Log</h4>
                        <div id="chatHistoryBox" style="height:350px; overflow-y:auto; display:flex; flex-direction:column; gap:1rem; padding:0.5rem; background:#fafafa; border:1px solid #e0e0e0;">
                            ${res.messages.length === 0 ? `<p style="font-style:italic; text-align:center; margin-top:6rem; color:#9ca3af; font-family:'Inter';">No messages logged yet.</p>` : 
                                res.messages.map(m => {
                                    const isSelf = m.sender_role === role && m.sender_id == localStorage.getItem('user_id');
                                    return `
                                        <div style="align-self: ${isSelf ? 'flex-end' : 'flex-start'}; max-width: 80%; background: ${isSelf ? '#e8f5e9' : '#fff'}; border:1px solid #e0e0e0; padding:0.8rem; box-shadow:0 2px 8px rgba(0,0,0,0.02); font-family:'Inter';">
                                            <span style="font-size:0.7rem; font-weight:600; text-transform:uppercase; display:block; border-bottom:1px solid #f1f3f5; padding-bottom:0.25rem; margin-bottom:0.4rem; color: ${isSelf ? '#2e7d32' : 'var(--primary)'}">
                                                ${isSelf ? 'ME' : `${m.sender_name} (${m.sender_role.toUpperCase()})`}
                                            </span>
                                            <p style="margin:0; font-size:0.9rem; color:#2c3e50;">${m.message}</p>
                                            <span style="font-size:0.65rem; color:#90a4ae; text-align:right; display:block; margin-top:0.4rem;">
                                                ${new Date(m.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                                            </span>
                                        </div>
                                    `;
                                }).join('')
                            }
                        </div>
                    </div>
                </div>
            `;

            // Scroll chat log to bottom
            const box = document.getElementById('chatHistoryBox');
            if (box) box.scrollTop = box.scrollHeight;
        }

        async function handleSendMessage(e, role) {
            e.preventDefault();
            const select = document.getElementById('chatTargetSelect');
            const targetId = select.value;
            const targetRole = select.options[select.selectedIndex].getAttribute('data-role');
            const message = document.getElementById('chatMessageText').value;

            const res = await apiRequest('communications/send', 'POST', {
                receiver_role: targetRole,
                receiver_id: targetId,
                message: message
            });

            if (res && res.success) {
                alert(res.message);
                loadMessenger(role);
            } else {
                alert(res.message || "Failed to send message.");
            }
        }
    </script>
</body>
</html>
