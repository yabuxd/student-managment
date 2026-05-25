<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'modern-enterprise';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Outfit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Enterprise Campus Portal | Dashboard</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-color, #0b0f19);
            color: var(--text-color, #fff);
            margin: 0;
            padding-bottom: 5rem;
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
            padding: 0.6rem 1.4rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-family: inherit;
        }
        .enterprise-btn:hover {
            transform: translateY(-1px);
            opacity: 0.95;
            box-shadow: 0 4px 15px rgba(0, 113, 227, 0.3);
        }
        .enterprise-btn.danger {
            background: #ff453a;
        }
        .enterprise-btn.danger:hover {
            box-shadow: 0 4px 15px rgba(255, 69, 58, 0.3);
        }
        .enterprise-btn.success {
            background: #30d158;
        }
        .enterprise-btn.success:hover {
            box-shadow: 0 4px 15px rgba(48, 209, 88, 0.3);
        }
        .enterprise-btn.warning {
            background: #ff9f0a;
        }
        .enterprise-btn-outline {
            background: transparent;
            color: #fff;
            padding: 0.6rem 1.4rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.3s;
            cursor: pointer;
            font-family: inherit;
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
            position: relative;
        }
        .bento-card:hover {
            border-color: rgba(255,255,255,0.12);
            transform: translateY(-2px);
        }
        .enterprise-badge {
            font-family: inherit;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            display: inline-block;
            color: rgba(255,255,255,0.85);
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .enterprise-table-container {
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.01);
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .enterprise-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.95rem;
        }
        .enterprise-table th {
            background: rgba(255,255,255,0.03);
            color: rgba(255,255,255,0.45);
            font-weight: 600;
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.8rem;
        }
        .enterprise-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            color: #fff;
        }
        .enterprise-table tr:last-child td {
            border-bottom: none;
        }
        /* Tab System styling */
        .enterprise-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .tab-btn {
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            color: rgba(255,255,255,0.45);
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
        .enterprise-input {
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-family: inherit;
            background: rgba(255,255,255,0.03);
            color: #fff;
            width: 100%;
            box-sizing: border-box;
            outline: none;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }
        .enterprise-input:focus {
            border-color: var(--primary);
            background: rgba(255,255,255,0.08);
        }
        .enterprise-input option {
            background: #0b0f19;
            color: #fff;
        }
        /* Modal overlay */
        .enterprise-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }
        .enterprise-modal-content {
            background: #0b0f19;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            padding: 2.5rem;
            width: 90%;
            max-width: 650px;
            position: relative;
            color: #fff;
        }
        .close-modal {
            position: absolute;
            top: 1.25rem; right: 1.25rem;
            font-size: 1.5rem; font-weight: 300;
            cursor: pointer;
            color: rgba(255,255,255,0.4);
            transition: color 0.2s;
        }
        .close-modal:hover {
            color: #ff453a;
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
                <span id="roleBadge" class="enterprise-badge" style="border:none; padding:0; background:none; font-size: 0.7rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1rem; font-weight: 700; margin-top: 0.25rem;">Role</span>
            </div>
            <div style="height: 32px; width: 1px; background: rgba(255,255,255,0.15);"></div>
            <a href="#" onclick="logout()" class="enterprise-btn-outline">Sign Out</a>
        </div>
    </nav>

    <div class="container" style="padding: 2.5rem 5%; max-width: 1400px; margin: 0 auto;">
        <div id="dashboardContent"></div>
    </div>

    <!-- Details/Grades Modal -->
    <div id="enterpriseModal" class="enterprise-modal">
        <div class="enterprise-modal-content">
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
            document.getElementById('enterpriseModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('enterpriseModal').style.display = 'none';
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
                    <div class="enterprise-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-student-courses')">My Courses</button>
                        <button class="tab-btn" onclick="switchTab('tab-student-report')">Year-End Report Card</button>
                        <button class="tab-btn" onclick="switchTab('tab-student-chat')">Messenger</button>
                    </div>

                    <div id="tab-student-courses" class="tab-panel active">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                            <div>
                                <h1 style="font-size: 2.2rem; font-weight: 700; margin:0; letter-spacing:-0.03em;">Academic Transcript</h1>
                                <p id="studentSectionName" class="enterprise-badge" style="margin-top:0.5rem;"></p>
                            </div>
                            <div class="bento-card" style="padding: 1rem; margin:0;">
                                <span style="font-weight:500; font-size:0.75rem; color:rgba(255,255,255,0.45); display:block;">CUMULATIVE AVERAGE</span>
                                <h2 id="studentOverallGpa" style="font-size: 1.8rem; font-weight:700; margin:0; color:var(--primary);">0.00</h2>
                            </div>
                        </div>

                        <div id="studentCoursesList" class="dashboard-grid">
                            <p style="font-style:italic;">Loading enrolled courses...</p>
                        </div>
                    </div>

                    <div id="tab-student-report" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; letter-spacing:-0.02em;">Official Year-End Roster Report</h2>
                            <div id="studentFinalEvaluationBlock">
                                <p style="font-style:italic;">Checking evaluation status...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-student-chat" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; letter-spacing:-0.02em;">Communication Center</h2>
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
                    <div class="enterprise-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-teacher-courses')">My Scheduled Classes</button>
                        <button class="tab-btn" onclick="switchTab('tab-teacher-homeroom')">Homeroom Desk</button>
                        <button class="tab-btn" onclick="switchTab('tab-teacher-chat')">Parent Messages</button>
                    </div>

                    <div id="tab-teacher-courses" class="tab-panel active">
                        <h1 style="font-size: 2.2rem; font-weight: 700; margin:0 0 2rem; letter-spacing:-0.03em;">Faculty Workspace</h1>
                        <div class="dashboard-grid" id="teacherAssignmentsGrid">
                            <p style="font-style:italic;">Loading teaching assignments...</p>
                        </div>
                    </div>

                    <div id="tab-teacher-homeroom" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; letter-spacing:-0.02em;">Homeroom Section Management</h2>
                            <div id="teacherHomeroomConfig">
                                <p style="font-style:italic;">Verifying homeroom assignment...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-teacher-chat" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; letter-spacing:-0.02em;">Parent Messaging Console</h2>
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
                    <div class="enterprise-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-parent-students')">My Dependents</button>
                        <button class="tab-btn" onclick="switchTab('tab-parent-chat')">Teacher Chat Console</button>
                    </div>

                    <div id="tab-parent-students" class="tab-panel active">
                        <h1 style="font-size: 2.2rem; font-weight: 700; margin:0 0 2rem; letter-spacing:-0.03em;">Parent Guardian space</h1>
                        <div class="dashboard-grid" id="parentStudentsGrid">
                            <p style="font-style:italic;">Loading linked dependent files...</p>
                        </div>
                    </div>

                    <div id="tab-parent-chat" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; letter-spacing:-0.02em;">Tutor Communication Core</h2>
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
                    <div class="enterprise-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-director-overview')">Platform Overview</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-assignments')">Faculty Scheduling</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-sectioning')">Roster Sectioning</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-parents')">Parent Linkage</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-config')">System Config</button>
                    </div>

                    <div id="tab-director-overview" class="tab-panel active">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                            <div>
                                <h1 style="font-size: 2.2rem; font-weight: 700; margin:0; letter-spacing:-0.03em;">Platform Command Dashboard</h1>
                                <span class="enterprise-badge" style="margin-top:0.5rem;">Institutional Core Running</span>
                            </div>
                        </div>
                        <div class="dashboard-grid" id="directorStatsGrid">
                            <p style="font-style:italic;">Loading global statistics...</p>
                        </div>
                    </div>

                    <div id="tab-director-assignments" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem; letter-spacing:-0.02em;">Subject & Section Allocation</h2>
                            <div style="margin-top: 1.5rem;" id="directorAssignmentsConfig">
                                <p style="font-style:italic;">Loading scheduled allocations...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-sectioning" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem; letter-spacing:-0.02em;">Student sectioning allocation</h2>
                            <div style="margin-top: 1.5rem;" id="directorSectioningConfig">
                                <p style="font-style:italic;">Loading student list...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-parents" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem; letter-spacing:-0.02em;">Parent-Student Linkage</h2>
                            <div style="margin-top: 1.5rem;" id="directorParentsConfig">
                                <p style="font-style:italic;">Loading parent linkages...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-config" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem; letter-spacing:-0.02em;">Curriculum Configuration Command</h2>
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
                container.innerHTML = `<div class="bento-card" style="grid-column: 1/-1;"><p style="font-style:italic;">No course subjects are scheduled for your section currently.</p></div>`;
                return;
            }

            container.innerHTML = res.courses.map(course => `
                <div class="bento-card" style="background: rgba(255,255,255,0.02); cursor:pointer;" onclick="viewStudentGrades(${course.subject_id}, '${course.subject_name}', '${course.teacher_name}')">
                    <span class="enterprise-badge" style="color:var(--primary); margin-bottom: 1rem;">Course Subject</span>
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin: 0; color:#fff; letter-spacing:-0.01em;">${course.subject_name}</h3>
                    <p style="font-size: 0.9rem; color: rgba(255,255,255,0.45); margin-top: 0.25rem; margin-bottom:1.5rem;">Tutor: ${course.teacher_name || 'TBD'}</p>
                    <div style="text-align:right;">
                        <span class="enterprise-btn-outline" style="padding: 0.4rem 1rem; font-size: 0.75rem;">View Scorecard &rarr;</span>
                    </div>
                </div>
            `).join('');
        }

        async function viewStudentGrades(subjectId, subjectName, teacherName) {
            const res = await apiRequest(`student/course-grades?subject_id=${subjectId}`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 1.8rem; font-weight:700; margin-top:0; color:#fff; letter-spacing:-0.02em;">${subjectName} Scorecard</h2>
                <p style="font-size:0.95rem; color:rgba(255,255,255,0.45); margin-top: -0.5rem; margin-bottom: 1.5rem;">Tutor: ${teacherName}</p>
                
                <div class="enterprise-table-container">
                    <table class="enterprise-table">
                        <thead>
                            <tr>
                                <th>Assessment</th>
                                <th>Category</th>
                                <th>Weight</th>
                                <th>Raw Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.grades.map(g => `
                                <tr>
                                    <td>${g.title}</td>
                                    <td>${g.type_name}</td>
                                    <td>${(g.weight * 100)}%</td>
                                    <td>
                                        <span class="enterprise-badge" style="color: ${g.score !== null ? '#30d158' : '#ff453a'}">
                                            ${g.score !== null ? `${g.score} / ${g.max_score}` : 'Ungraded'}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <div class="bento-card" style="margin-bottom:0; display:flex; justify-content:space-between; align-items:center; padding:1.25rem;">
                    <span style="font-weight:500; font-size:0.95rem; color:rgba(255,255,255,0.45);">Normalized Course Average:</span>
                    <span style="font-size:1.6rem; font-weight:700; color:var(--primary);">${res.weighted_average}%</span>
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
                    <div style="border:1px solid rgba(255,69,58,0.3); background: rgba(255,69,58,0.05); color:#ff453a; padding:1.25rem; border-radius:0.75rem; font-weight:500;">
                        Year-end final evaluations are locked currently by the platform administrator. Scores compiling in progress.
                    </div>
                `;
                return;
            }

            if (res.status === 'pending') {
                block.innerHTML = `
                    <div style="border:1px solid rgba(255,159,10,0.3); background: rgba(255,159,10,0.05); color:#ff9f0a; padding:1.25rem; border-radius:0.75rem; font-weight:500;">
                        Year-end final evaluations are open. Your homeroom teacher is currently compiling class ranks and GPAs. Please hold.
                    </div>
                `;
                return;
            }

            const eval = res.evaluation;
            block.innerHTML = `
                <div class="dashboard-grid">
                    <div class="bento-card" style="margin:0; background:rgba(48,209,88,0.03); border-color: rgba(48,209,88,0.2);">
                        <span style="font-size:0.85rem; color:rgba(255,255,255,0.45);">YEAR CUMULATIVE AVERAGE</span>
                        <h2 style="font-size:2.4rem; font-weight:700; margin:0; color:#30d158;">${eval.average_score}%</h2>
                    </div>
                    <div class="bento-card" style="margin:0; background:rgba(0,125,250,0.03); border-color: rgba(0,125,250,0.2);">
                        <span style="font-size:0.85rem; color:rgba(255,255,255,0.45);">OFFICIAL CLASS RANK</span>
                        <h2 style="font-size:2.4rem; font-weight:700; margin:0; color:var(--primary);">#${eval.class_rank}</h2>
                    </div>
                    <div class="bento-card" style="margin:0; background: ${eval.status === 'pass' ? 'rgba(48,209,88,0.03)' : 'rgba(255,69,58,0.03)'}; border-color: ${eval.status === 'pass' ? 'rgba(48,209,88,0.2)' : 'rgba(255,69,58,0.2)'};">
                        <span style="font-size:0.85rem; color:rgba(255,255,255,0.45);">PROMOTION DECISION</span>
                        <h2 style="font-size:2.4rem; font-weight:700; text-transform:uppercase; margin:0; color: ${eval.status === 'pass' ? '#30d158' : '#ff453a'};">${eval.status}</h2>
                    </div>
                </div>
                <div class="bento-card" style="margin-top:1.5rem; border-style:dashed;">
                    <p style="font-size:0.95rem; color:rgba(255,255,255,0.45); margin:0;">Signed and verified by homeroom instructor: <strong>${eval.evaluator_name || 'System Administrator'}</strong> on ${new Date(eval.evaluated_at).toLocaleDateString()}</p>
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
                grid.innerHTML = `<div class="bento-card" style="grid-column: 1/-1;"><p style="font-style:italic;">No teaching assignments are mapped to your account by administration.</p></div>`;
            } else {
                grid.innerHTML = res.classes.map(c => `
                    <div class="bento-card" style="background: rgba(255,255,255,0.01);">
                        <span class="enterprise-badge" style="margin-bottom: 1rem; color:var(--primary);">Grade ${c.grade_level} ${c.stream}</span>
                        <h3 style="font-size: 1.4rem; font-weight: 700; margin: 0; color:#fff;">${c.subject_name}</h3>
                        <p style="font-size: 0.9rem; color:rgba(255,255,255,0.45); margin-top: 0.25rem; margin-bottom:1.5rem;">Section Classroom: ${c.section_name}</p>
                        
                        <div style="display: flex; gap: 0.5rem;">
                            <button class="enterprise-btn" style="width:100%; font-size:0.75rem;" onclick="viewClassAssessments(${c.assignment_id}, ${c.section_id}, '${c.subject_name} - ${c.section_name}')">Assessments</button>
                            <button class="enterprise-btn-outline" style="width:100%; font-size:0.75rem;" onclick="viewClassRoster(${c.section_id}, '${c.section_name}')">Roster List</button>
                        </div>
                    </div>
                `).join('');
            }

            const homeroomDiv = document.getElementById('teacherHomeroomConfig');
            if (!res.homeroom_class) {
                homeroomDiv.innerHTML = `
                    <div style="border:1px solid rgba(255,69,58,0.3); background: rgba(255,69,58,0.05); color:#ff453a; padding:1.25rem; border-radius:0.75rem; font-weight:500;">
                        You are not currently assigned as a homeroom tutor for any classroom section.
                    </div>
                `;
            } else {
                const sect = res.homeroom_class;
                homeroomDiv.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:1rem; margin-bottom:2rem;">
                        <div>
                            <h3 style="font-size:1.5rem; font-weight:700; margin:0; color:#fff;">Class Room ${sect.section_name} (Grade ${sect.grade_level})</h3>
                            <span class="enterprise-badge" style="background:rgba(48,209,88,0.1); color:#30d158; margin-top:0.5rem; border-color:rgba(48,209,88,0.2);">Homeroom Active</span>
                        </div>
                        <button class="enterprise-btn" onclick="loadHomeroomEvaluationsTable(${sect.section_id})">Compute Class Ranks</button>
                    </div>
                    <div id="homeroomEvaluationsRoster">
                        <p style="font-style:italic; color:rgba(255,255,255,0.45);">Select the rank trigger above to open the promotion roster ledger.</p>
                    </div>
                `;
            }
        }

        async function viewClassRoster(sectionId, sectionName) {
            const res = await apiRequest(`teacher/class-students?section_id=${sectionId}`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 1.6rem; font-weight:700; color:#fff; margin-top:0; letter-spacing:-0.02em;">Student Roster - Class ${sectionName}</h2>
                <div class="enterprise-table-container">
                    <table class="enterprise-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Student Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.students.map(s => `
                                <tr>
                                    <td>${s.student_id}</td>
                                    <td>${s.full_name}</td>
                                    <td>${s.email || 'N/A'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            showModal(modalHtml);
        }

        async function viewClassAssessments(assignmentId, sectionId, className) {
            const res = await apiRequest(`teacher/assessments?assignment_id=${assignmentId}`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 1.6rem; font-weight:700; color:#fff; margin-top:0;">Assessments</h2>
                <p style="font-size:0.95rem; color:rgba(255,255,255,0.45); margin-top:-0.5rem; margin-bottom:1.5rem;">Class Section: ${className}</p>

                <div class="bento-card" style="background: rgba(255,255,255,0.01); padding: 1.5rem; margin-bottom: 1.5rem; border-radius:0.75rem;">
                    <h4 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.05em;">Add assessment ledger</h4>
                    <form onsubmit="handleCreateAssessment(event, ${assignmentId}, ${sectionId}, '${className}')" style="display:grid; grid-template-columns: 2fr 1fr 1.5fr 1fr; gap:0.5rem; align-items:center;">
                        <input type="text" id="newAssTitle" class="enterprise-input" style="margin-bottom:0;" required placeholder="Assessment Title (e.g. Essay 1)">
                        <input type="number" id="newAssMax" class="enterprise-input" style="margin-bottom:0;" required placeholder="Max Score" min="1" max="100">
                        <select id="newAssType" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            ${res.assessment_types.map(t => `<option value="${t.id}">${t.name} (${t.weight * 100}%)</option>`).join('')}
                        </select>
                        <button type="submit" class="enterprise-btn" style="padding: 0.65rem 1rem;">CREATE</button>
                    </form>
                </div>

                <div class="enterprise-table-container">
                    <table class="enterprise-table">
                        <thead>
                            <tr>
                                <th>Assessment Ledger</th>
                                <th>Type</th>
                                <th>Weight</th>
                                <th>Max Points</th>
                                <th>Grade Sheet</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.assessments.map(a => `
                                <tr>
                                    <td>${a.title}</td>
                                    <td>${a.type_name}</td>
                                    <td>${a.weight * 100}%</td>
                                    <td>${a.max_score}</td>
                                    <td>
                                        <button class="enterprise-btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.75rem; border-radius:0.5rem;" 
                                                onclick="loadGradeEntryForm(${a.id}, ${sectionId}, '${a.title}', ${a.max_score})">
                                            GRADE SHEET
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

        async function handleCreateAssessment(e, assignmentId, sectionId, className) {
            e.preventDefault();
            const title = document.getElementById('newAssTitle').value;
            const max_score = document.getElementById('newAssMax').value;
            const type_id = document.getElementById('newAssType').value;

            const res = await apiRequest('teacher/create-assessment', 'POST', {
                assignment_id: assignmentId,
                title,
                max_score,
                type_id
            });

            if (res && res.success) {
                alert(res.message);
                closeModal();
                viewClassAssessments(assignmentId, sectionId, className);
            } else {
                alert(res.message || "Failed to create assessment.");
            }
        }

        async function loadGradeEntryForm(assessmentId, sectionId, assTitle, maxScore) {
            const studentsRes = await apiRequest(`teacher/class-students?section_id=${sectionId}`);
            if (!studentsRes || !studentsRes.success) return;

            const html = `
                <h2 style="font-size: 1.6rem; font-weight:700; color:#fff; margin-top:0;">ENTER SCORINGS SHEET</h2>
                <p style="font-size:0.95rem; color:rgba(255,255,255,0.45); margin-top:-0.5rem; margin-bottom:1.5rem;">Ledger: ${assTitle} (Out of ${maxScore} points)</p>
                
                <form onsubmit="submitStudentGrades(event, ${assessmentId}, ${sectionId})">
                    <div class="enterprise-table-container">
                        <table class="enterprise-table">
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
                                            <input type="number" step="0.01" class="enterprise-input student-score-input" 
                                                   data-student-id="${s.id}" min="0" max="${maxScore}" style="margin-bottom:0; width:120px;" placeholder="Score">
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="enterprise-btn">SAVE RECORDS</button>
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
                    <div style="border:1px solid rgba(255,69,58,0.3); background: rgba(255,69,58,0.05); color:#ff453a; padding:1.25rem; border-radius:0.75rem; font-weight:500; margin-top:1.5rem;">
                        COMPLIANCE LOCK: Year-End final evaluations are locked currently by administration.
                    </div>
                `;
                return;
            }

            rosterArea.innerHTML = `
                <div class="bento-card" style="background:rgba(48,209,88,0.03); border-color: rgba(48,209,88,0.2); margin-bottom:1.5rem;">
                    <p style="font-weight:600; font-size:0.95rem; color:#30d158; margin:0;">CLASS COMPLIANCE ALERT</p>
                    <p style="font-size:0.9rem; color:rgba(255,255,255,0.45); margin:0.25rem 0 0;">Weighted averages, class rankings, and promotion pass/fail triggers computed below automatically using Grading Service formula.</p>
                </div>
                
                <form onsubmit="submitHomeroomEvaluations(event, ${sectionId})">
                    <div class="enterprise-table-container">
                        <table class="enterprise-table">
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
                                            <span style="font-weight:500; background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08); padding:0.2rem 0.5rem; border-radius:4px;">
                                                ${s.average}%
                                            </span>
                                            <input type="hidden" class="eval-student-avg" data-student-id="${s.id}" value="${s.average}">
                                            <input type="hidden" class="eval-student-rank" data-student-id="${s.id}" value="${s.rank}">
                                        </td>
                                        <td>
                                            <select class="enterprise-input eval-student-status" data-student-id="${s.id}" style="margin-bottom:0; width:180px; height:auto;">
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
                        <button type="submit" class="enterprise-btn">SUBMIT YEAR-END RECORD</button>
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
                grid.innerHTML = `<div class="bento-card" style="grid-column: 1/-1;"><p style="font-style:italic;">No student files are linked to your portal. Please contact School Registry.</p></div>`;
            } else {
                grid.innerHTML = res.students.map(s => `
                    <div class="bento-card" style="background: rgba(255,255,255,0.01);">
                        <span class="enterprise-badge" style="margin-bottom: 1rem; color:var(--primary);">Dependent File</span>
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0; color:#fff;">${s.full_name}</h3>
                        <p style="font-size: 0.9rem; color:rgba(255,255,255,0.45); margin-top: 0.25rem; margin-bottom:1.5rem;">Grade Stream: ${s.section_name || 'Unassigned'}</p>
                        
                        <div style="margin-bottom: 1.5rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.02); display:flex; justify-content:space-between; align-items:center; border-radius:0.5rem;">
                            <span style="font-size:0.85rem; color:rgba(255,255,255,0.45);">COMPLETED AVERAGE POINT:</span>
                            <span style="font-size:1.4rem; font-weight:700; color:var(--primary);">${s.overall_average}%</span>
                        </div>

                        <div style="display:flex; gap:0.5rem;">
                            <button class="enterprise-btn" style="width:100%; font-size:0.75rem;" onclick="viewChildAcademicProgress(${s.id}, '${s.full_name}')">Track Scorecards</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        async function viewChildAcademicProgress(studentId, studentName) {
            const res = await apiRequest(`student/courses`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 1.6rem; font-weight:700; color:#fff; margin-top:0;">Progress Tracker Ledger</h2>
                <p style="font-size:0.95rem; color:rgba(255,255,255,0.45); margin-top:-0.5rem; margin-bottom:1.5rem;">Dependent File: ${studentName}</p>

                <div class="bento-card" style="background: rgba(255,255,255,0.02); margin-bottom: 2rem; padding:1rem; border-radius:0.75rem;">
                    <p style="font-weight:600; font-size:0.95rem; color:rgba(255,255,255,0.45); margin:0;">COMBINED GPAS CUMULATIVE: <strong style="color:var(--primary); font-size:1.25rem;">${res.overall_average}%</strong></p>
                </div>

                <div class="enterprise-table-container">
                    <table class="enterprise-table">
                        <thead>
                            <tr>
                                <th>Academic Course</th>
                                <th>Instructor</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.courses.map(c => `
                                <tr>
                                    <td>${c.subject_name}</td>
                                    <td>${c.teacher_name || 'TBD'}</td>
                                    <td>
                                        <button class="enterprise-btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.75rem; border-radius:0.5rem;"
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
                <div class="bento-card" style="background: rgba(0,125,250,0.02); border-color: rgba(0,125,250,0.1);">
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin: 0; color:var(--primary);">${stats.total_students}</h2>
                    <p style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:rgba(255,255,255,0.45); letter-spacing:0.05em; margin-top: 0.5rem;">Pupils Enrolled</p>
                </div>
                <div class="bento-card" style="background: rgba(48,209,88,0.02); border-color: rgba(48,209,88,0.1);">
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin: 0; color:#30d158;">${stats.total_teachers}</h2>
                    <p style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:rgba(255,255,255,0.45); letter-spacing:0.05em; margin-top: 0.5rem;">Academic Faculty</p>
                </div>
                <div class="bento-card" style="background: rgba(255,159,10,0.02); border-color: rgba(255,159,10,0.1);">
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin: 0; color:#ff9f0a;">${stats.total_subjects}</h2>
                    <p style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:rgba(255,255,255,0.45); letter-spacing:0.05em; margin-top: 0.5rem;">Subjects</p>
                </div>
                <div class="bento-card" style="background: rgba(142,142,147,0.02); border-color: rgba(142,142,147,0.1);">
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin: 0; color:#8e8e93;">${stats.total_sections}</h2>
                    <p style="font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color:rgba(255,255,255,0.45); letter-spacing:0.05em; margin-top: 0.5rem;">Section classrooms</p>
                </div>
            `;
        }

        async function loadDirectorAssignmentsData() {
            const res = await apiRequest('director/assignment-data');
            if (!res || !res.success) return;

            const container = document.getElementById('directorAssignmentsConfig');
            
            container.innerHTML = `
                <div class="bento-card" style="background:rgba(255,255,255,0.01); border-radius:0.75rem; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1rem; font-weight:700; color:#fff;">Schedule Faculty to Course Class</h3>
                    <form onsubmit="handleAssignTeacher(event)" style="display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:0.5rem;">
                        <select id="assignTeacherSelect" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Teacher --</option>
                            ${res.teachers.map(t => `<option value="${t.id}">${t.full_name} (${t.specialization || 'Tutor'})</option>`).join('')}
                        </select>
                        <select id="assignSubjectSelect" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Subject --</option>
                            ${res.subjects.map(s => `<option value="${s.id}">${s.name} (Grade ${s.grade_level})</option>`).join('')}
                        </select>
                        <select id="assignSectionSelect" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Class Section --</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - ${sec.section_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="enterprise-btn">SCHEDULE</button>
                    </form>
                </div>

                <div class="bento-card" style="background:rgba(255,255,255,0.01); border-radius:0.75rem; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1rem; font-weight:700; color:#fff;">Set homeroom teacher assignment</h3>
                    <form onsubmit="handleAssignHomeroom(event)" style="display:grid; grid-template-columns: 1fr 1fr auto; gap:0.5rem;">
                        <select id="homeroomSectionSelect" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Select Class --</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - Section ${sec.section_name} (${sec.homeroom_teacher_name ? `Current Homeroom: ${sec.homeroom_teacher_name}` : 'No Homeroom Assigned'})</option>`).join('')}
                        </select>
                        <select id="homeroomTeacherSelect" class="enterprise-input" style="margin-bottom:0; height:auto;">
                            <option value="">-- Set Unassigned --</option>
                            ${res.teachers.map(t => `<option value="${t.id}">${t.full_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="enterprise-btn-outline" style="padding: 0.6rem 1.5rem;">SET HOMEROOM</button>
                    </form>
                </div>

                <h3 style="font-size:1.3rem; font-weight:700; color:#fff; margin-top:2rem;">Allocated Roster Schedules</h3>
                <div class="enterprise-table-container">
                    <table class="enterprise-table">
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
                                        <button class="enterprise-btn-outline danger" style="padding:0.3rem 0.8rem; font-size:0.75rem; border-radius:0.5rem;" onclick="removeTeacherAssignment(${a.assignment_id})">REMOVE</button>
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
                <div class="bento-card" style="background:rgba(255,255,255,0.01); border-radius:0.75rem; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1rem; font-weight:700; color:#fff;">Auto-Sectioning (Random Assignment)</h3>
                    <p style="font-size:0.9rem; color:rgba(255,255,255,0.45); margin-top:-0.5rem; margin-bottom:1rem;">
                        Distributes all unsectioned students in the registry evenly across active sections of Grade Level 10.
                    </p>
                    <form onsubmit="handleRandomSectioning(event)" style="display:grid; grid-template-columns: 2fr auto; gap:0.5rem;">
                        <select id="randomSectioningGrade" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Grade Level --</option>
                            <option value="10">Grade 10 General</option>
                        </select>
                        <button type="submit" class="enterprise-btn">RUN AUTO DISTRIBUTION</button>
                    </form>
                </div>

                <div class="bento-card" style="background:rgba(255,255,255,0.01); border-radius:0.75rem; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1rem; font-weight:700; color:#fff;">Manual Roster Transfer (Preferred Assignment)</h3>
                    <form onsubmit="handleAssignStudentSection(event)" style="display:grid; grid-template-columns: 1fr 1fr auto; gap:0.5rem;">
                        <select id="assignStudentSelect" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Student profile --</option>
                            ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_code}) [${s.section_name ? `Class: ${s.section_name}` : 'Unassigned'}]</option>`).join('')}
                        </select>
                        <select id="studentSectionSelect" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Target Section --</option>
                            <option value="">Unassign / Remove from Classroom</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - ${sec.section_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="enterprise-btn-outline" style="padding: 0.6rem 1.5rem;">SAVE TRANSFER</button>
                    </form>
                </div>

                <h3 style="font-size:1.3rem; font-weight:700; color:#fff; margin-top:2rem;">Student Directory</h3>
                <div class="enterprise-table-container">
                    <table class="enterprise-table">
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
                                        <span class="enterprise-badge" style="color: ${s.section_name ? '#30d158' : '#ff453a'}; border-color: ${s.section_name ? 'rgba(48,209,88,0.2)' : 'rgba(255,69,58,0.2)'}">
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
                <div class="bento-card" style="background:rgba(255,255,255,0.01); border-radius:0.75rem; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1rem; font-weight:700; color:#fff;">Link Parent to Student Profile</h3>
                    <form onsubmit="handleLinkParent(event)" style="display:grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap:0.5rem;">
                        <input type="text" id="parentName" class="enterprise-input" style="margin-bottom:0;" required placeholder="Parent Full Name">
                        <input type="email" id="parentEmail" class="enterprise-input" style="margin-bottom:0;" required placeholder="Parent Email">
                        <input type="text" id="parentPhone" class="enterprise-input" style="margin-bottom:0;" required placeholder="Phone Number">
                        <select id="parentStudentSelect" class="enterprise-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Target Child --</option>
                            ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_id})</option>`).join('')}
                        </select>
                        <button type="submit" class="enterprise-btn">LINK & SAVE</button>
                    </form>
                </div>

                <h3 style="font-size:1.3rem; font-weight:700; color:#fff; margin-top:2rem;">Registered Parents Directory</h3>
                <div class="enterprise-table-container">
                    <table class="enterprise-table">
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
                                        <span class="enterprise-badge" style="color: #30d158; background: rgba(48,209,88,0.1); border-color: rgba(48,209,88,0.2);">Linked</span>
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

        async function loadDirectorConfigData() {
            const statsRes = await apiRequest('director/stats');
            if (!statsRes || !statsRes.success) return;

            const isFinalActive = statsRes.stats.is_final_active;
            const container = document.getElementById('directorConfigBlock');

            container.innerHTML = `
                <div class="bento-card" style="border-top: 4px solid ${isFinalActive ? '#30d158' : 'var(--primary)'}; background: rgba(255,255,255,0.01); border-style: ${isFinalActive ? 'solid' : 'dashed'};">
                    <h3 style="margin-top:0; font-size:1.5rem; font-weight:700; color:#fff;">Year-End Assessment Trigger</h3>
                    <p style="font-size:0.95rem; color:rgba(255,255,255,0.45); margin-bottom:1.5rem;">
                        Toggling this trigger decides the active "End of Year" phase. When active, all homeroom teachers gain access to computing average GPAs, class rankings, and submitting student promotion decisions (pass/fail status). When disabled, teachers cannot modify compiled rosters.
                    </p>
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <span style="font-weight:600; font-size:0.95rem; color:rgba(255,255,255,0.45);">STATUS: </span>
                            <span class="enterprise-badge" style="color: ${isFinalActive ? '#30d158' : '#ff453a'};">
                                ${isFinalActive ? 'OPEN / ACTIVE' : 'CLOSED / INACTIVE'}
                            </span>
                        </div>
                        <button class="enterprise-btn ${isFinalActive ? 'danger' : 'success'}" onclick="toggleFinalMode(${isFinalActive ? 0 : 1})">
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
                        <h4 style="margin-top:0; font-weight:700; font-size:1rem; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.5rem; color:#fff;">Send New Message</h4>
                        <form onsubmit="handleSendMessage(event, '${role}')">
                            <select id="chatTargetSelect" class="enterprise-input" style="height:auto;" required>
                                <option value="">-- Choose Contact --</option>
                                ${res.contacts.map(c => `<option value="${c.id}" data-role="${c.role}">${c.full_name} (${c.role.toUpperCase()})</option>`).join('')}
                            </select>
                            <textarea id="chatMessageText" class="enterprise-input" style="height:100px; resize:none;" required placeholder="Type your message here..."></textarea>
                            <button type="submit" class="enterprise-btn" style="width:100%;">SEND MESSAGE</button>
                        </form>
                    </div>

                    <div style="border-left:1px solid rgba(255,255,255,0.08); padding-left:1.5rem;">
                        <h4 style="margin-top:0; font-weight:700; font-size:1rem; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.5rem; color:#fff;">Conversations Log</h4>
                        <div id="chatHistoryBox" style="height:350px; overflow-y:auto; display:flex; flex-direction:column; gap:1rem; padding:0.5rem; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.08); border-radius:0.5rem;">
                            ${res.messages.length === 0 ? `<p style="font-style:italic; text-align:center; margin-top:6rem; color:#9ca3af;">No messages logged yet.</p>` : 
                                res.messages.map(m => {
                                    const isSelf = m.sender_role === role && m.sender_id == localStorage.getItem('user_id');
                                    return `
                                        <div style="align-self: ${isSelf ? 'flex-end' : 'flex-start'}; max-width: 80%; background: ${isSelf ? 'rgba(48,209,88,0.1)' : 'rgba(255,255,255,0.02)'}; border:1px solid rgba(255,255,255,0.08); padding:0.8rem; border-radius:0.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.2);">
                                            <span style="font-size:0.7rem; font-weight:600; text-transform:uppercase; display:block; border-bottom:1px solid rgba(255,255,255,0.04); padding-bottom:0.25rem; margin-bottom:0.4rem; color: ${isSelf ? '#30d158' : 'var(--primary)'}">
                                                ${isSelf ? 'ME' : `${m.sender_name} (${m.sender_role.toUpperCase()})`}
                                            </span>
                                            <p style="margin:0; font-size:0.9rem; color:#fff;">${m.message}</p>
                                            <span style="font-size:0.65rem; color:rgba(255,255,255,0.4); text-align:right; display:block; margin-top:0.4rem;">
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
