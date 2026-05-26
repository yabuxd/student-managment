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
    <title>Dynamic Portal Dashboard | SIS</title>
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root {
            --border-dark: var(--border-color, #000000);
            --brutal-yellow: var(--accent-1, #facc15);
            --brutal-cyan: var(--accent-2, #22d3ee);
            --brutal-pink: var(--accent-3, #f43f5e);
            --brutal-purple: #a855f7;
            --brutal-green: #4ade80;
            --primary: var(--accent-1, #000000);
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-color, #f3f4f6);
            color: var(--text-color, #000);
            line-height: 1.4;
            padding-bottom: 5rem;
            margin: 0;
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
            margin-bottom: 2rem;
            box-shadow: 8px 8px 0 var(--border-dark);
        }
        .brutal-card {
            border: 4px solid var(--border-dark);
            background: #fff;
            padding: 2rem;
            box-shadow: 8px 8px 0 var(--border-dark);
            margin-bottom: 2rem;
            position: relative;
            transition: all 0.15s cubic-bezier(0, 0, 0, 1);
        }
        .brutal-card:hover {
            transform: translate(-3px, -3px);
            box-shadow: 11px 11px 0 var(--border-dark);
        }
        .brutal-badge {
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            border: 2px solid var(--border-dark);
            padding: 0.2rem 0.6rem;
            display: inline-block;
            box-shadow: 2px 2px 0 var(--border-dark);
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
        .brutal-btn.danger {
            background-color: var(--brutal-pink);
        }
        .brutal-btn.success {
            background-color: var(--brutal-green);
            color: #000;
        }
        .brutal-btn.warning {
            background-color: var(--brutal-yellow);
            color: #000;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .brutal-table-container {
            border: 4px solid var(--border-dark);
            box-shadow: 6px 6px 0 var(--border-dark);
            background: #fff;
            overflow-x: auto;
            margin-bottom: 2rem;
        }
        .brutal-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .brutal-table th {
            background: var(--brutal-yellow);
            font-weight: 900;
            text-transform: uppercase;
            padding: 1rem;
            border-bottom: 4px solid var(--border-dark);
            border-right: 2px solid var(--border-dark);
        }
        .brutal-table td {
            padding: 1rem;
            font-weight: 700;
            border-bottom: 2px solid var(--border-dark);
            border-right: 2px solid var(--border-dark);
        }
        .brutal-table tr:last-child td {
            border-bottom: none;
        }
        .brutal-table td:last-child, .brutal-table th:last-child {
            border-right: none;
        }
        /* Custom Tab System styling */
        .brutal-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 2.5rem;
            border-bottom: 4px solid var(--border-dark);
            padding-bottom: 1rem;
        }
        .tab-btn {
            background: #fff;
            border: 3px solid var(--border-dark);
            font-weight: 900;
            text-transform: uppercase;
            padding: 0.6rem 1.2rem;
            cursor: pointer;
            box-shadow: 3px 3px 0 var(--border-dark);
            transition: all 0.1s;
        }
        .tab-btn.active {
            background: var(--brutal-yellow);
            transform: translate(-2px, -2px);
            box-shadow: 5px 5px 0 var(--border-dark);
        }
        .tab-panel {
            display: none;
        }
        .tab-panel.active {
            display: block;
        }
        /* Elegant Form fields */
        .brutal-input {
            border: 3px solid var(--border-dark);
            padding: 0.8rem;
            font-weight: 700;
            background: var(--bg-card, #fff);
            color: var(--text-color, #000);
            width: 100%;
            box-sizing: border-box;
            outline: none;
            margin-bottom: 1rem;
        }
        .brutal-input:focus {
            box-shadow: 4px 4px 0 var(--border-dark);
        }
        /* Modal Neo-Brutalist overlay */
        .brutal-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .brutal-modal-content {
            background: var(--bg-card, #fff);
            color: var(--text-color, #000);
            border: 4px solid var(--border-dark);
            box-shadow: 12px 12px 0 var(--border-dark);
            padding: 2.5rem;
            width: 90%;
            max-width: 600px;
            position: relative;
        }
        .close-modal {
            position: absolute;
            top: 1rem; right: 1rem;
            font-size: 1.5rem; font-weight: 900;
            cursor: pointer;
            border: 3px solid var(--border-dark);
            background: var(--brutal-pink);
            color: #fff;
            padding: 0.2rem 0.6rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <h2 style="margin:0; background: var(--brutal-cyan); padding: 0.5rem 1rem; border: 3px solid var(--border-dark); font-weight:900; font-size:1.35rem; box-shadow: 4px 4px 0 var(--border-dark);">SIS ACADEMY CORE</h2>
            <div class="nav-links" style="display: flex; align-items: center;">
                <div style="text-align: right; margin-right: 1.5rem;">
                    <span id="userNameDisplay" style="font-weight: 900; text-transform: uppercase; display:block; font-size: 0.95rem;">GUEST</span>
                    <span id="roleBadge" class="brutal-badge" style="background: var(--brutal-yellow); padding: 0.1rem 0.4rem; display:inline-block; margin-top:0.25rem;">ROLE</span>
                </div>
                <div style="width: 3px; height: 35px; background: var(--border-dark); margin-right: 1.5rem;"></div>
                <a href="#" onclick="logout()" style="font-weight: 900; color: var(--brutal-pink); text-decoration: none; text-transform: uppercase; border-bottom: 3px solid var(--brutal-pink);">LOGOUT X</a>
            </div>
        </nav>

        <div id="dashboardContent"></div>
    </div>

    <!-- Details/Grades Modal -->
    <div id="brutalModal" class="brutal-modal">
        <div class="brutal-modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        if (window.location.search.includes('preview_subdomain')) {
            let nd = document.getElementById('userNameDisplay'); if(nd) nd.textContent = 'Preview';
            let rb = document.getElementById('roleBadge'); if(rb) rb.textContent = 'DIRECTOR';
            if(typeof loadRolePortal === 'function') loadRolePortal('director');
            return;
        }
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
            if (window.location.search.includes('preview_subdomain')) return new Promise(() => {});
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
            document.getElementById('brutalModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('brutalModal').style.display = 'none';
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
                    <div class="brutal-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-student-courses')">My Courses</button>
                        <button class="tab-btn" onclick="switchTab('tab-student-report')">Year-End Report Card</button>
                        <button class="tab-btn" onclick="switchTab('tab-student-chat')">Parent-Teacher Messenger</button>
                    </div>

                    <div id="tab-student-courses" class="tab-panel active">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 4px solid var(--border-dark); padding-bottom: 1rem;">
                            <div>
                                <h1 style="font-size: 3rem; font-weight: 900; text-transform: uppercase; margin:0;">MY ACADEMIC LEDGER</h1>
                                <p id="studentSectionName" class="brutal-badge" style="background: var(--brutal-yellow); margin-top:0.5rem;"></p>
                            </div>
                            <div class="brutal-card" style="background: var(--brutal-cyan); padding: 1rem; margin:0;">
                                <span style="font-weight:900; font-size:0.9rem;">GPA SUMMARY</span>
                                <h2 id="studentOverallGpa" style="font-size: 2.2rem; font-weight:900; margin:0;">0.00</h2>
                            </div>
                        </div>

                        <div id="studentCoursesList" class="dashboard-grid">
                            <p style="font-weight:800;">Loading courses...</p>
                        </div>
                    </div>

                    <div id="tab-student-report" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.5rem; font-weight: 900; text-transform: uppercase; margin-top:0;">Official Year-End Roster Report</h2>
                            <div id="studentFinalEvaluationBlock">
                                <p style="font-weight:800;">Checking evaluation status...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-student-chat" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0;">Send Message</h2>
                            <div id="studentChatPanel">
                                <p style="font-weight:800;">Loading messenger contacts...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadStudentCoursesData();
                await loadStudentFinalEvaluationData();
                await loadMessenger('student');

            } else if (role === 'teacher') {
                contentArea.innerHTML = `
                    <div class="brutal-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-teacher-courses')">My Assignments</button>
                        <button class="tab-btn" onclick="switchTab('tab-teacher-homeroom')">Homeroom Core</button>
                        <button class="tab-btn" onclick="switchTab('tab-teacher-chat')">Parent Messenger</button>
                    </div>

                    <div id="tab-teacher-courses" class="tab-panel active">
                        <h1 style="font-size: 3rem; font-weight: 900; text-transform: uppercase; margin:0 0 2rem;">FACULTY CONTROL COMMAND</h1>
                        <div class="dashboard-grid" id="teacherAssignmentsGrid">
                            <p style="font-weight:800;">Loading teaching assignments...</p>
                        </div>
                    </div>

                    <div id="tab-teacher-homeroom" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.5rem; font-weight: 900; text-transform: uppercase; margin-top:0;">Homeroom Section Management</h2>
                            <div id="teacherHomeroomConfig">
                                <p style="font-weight:800;">Verifying homeroom assignment...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-teacher-chat" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0;">Parent Messaging Core</h2>
                            <div id="teacherChatPanel">
                                <p style="font-weight:800;">Loading chat log...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadTeacherPortalData();
                await loadMessenger('teacher');

            } else if (role === 'parent') {
                contentArea.innerHTML = `
                    <div class="brutal-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-parent-students')">My Children</button>
                        <button class="tab-btn" onclick="switchTab('tab-parent-chat')">Teacher Messenger</button>
                    </div>

                    <div id="tab-parent-students" class="tab-panel active">
                        <h1 style="font-size: 3rem; font-weight: 900; text-transform: uppercase; margin:0 0 2rem;">PARENT RADAR HUB</h1>
                        <div class="dashboard-grid" id="parentStudentsGrid">
                            <p style="font-weight:800;">Loading linked student files...</p>
                        </div>
                    </div>

                    <div id="tab-parent-chat" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0;">Teacher Messenger Console</h2>
                            <div id="parentChatPanel">
                                <p style="font-weight:800;">Loading chat logs...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadParentPortalData();
                await loadMessenger('parent');

            } else if (role === 'director') {
                contentArea.innerHTML = `
                    <div class="brutal-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-director-overview')">Director Overview</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-assignments')">Faculty Scheduling</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-subjects')">Curriculum Subjects</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-sectioning')">Roster Sectioning</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-parents')">Parent Logs</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-config')">System Config</button>
                    </div>

                    <div id="tab-director-overview" class="tab-panel active">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 4px solid var(--border-dark); padding-bottom: 1rem;">
                            <div>
                                <h1 style="font-size: 3rem; font-weight: 900; text-transform: uppercase; margin:0;">GLOBAL CENTRAL CONTROL</h1>
                                <span class="brutal-badge" style="background: var(--brutal-yellow); margin-top:0.5rem;">Global System Active</span>
                            </div>
                        </div>
                        <div class="dashboard-grid" id="directorStatsGrid">
                            <p style="font-weight:800;">Loading dashboard statistics...</p>
                        </div>
                        <div class="brutal-card" style="margin-top: 2rem;">
                            <h3 style="margin-top:0; font-size:1.8rem; font-weight:900; text-transform:uppercase;">CSV Mass User Registration</h3>
                            <p style="font-weight:800; font-size:1.05rem; margin-bottom:1.5rem;">
                                Select the target role, choose a CSV file with "Full Name" and "Email", and click Process.
                            </p>
                            <form id="massRegForm" onsubmit="handleMassRegistration(event)">
                                <div style="display:grid; grid-template-columns:1fr 2fr; gap:1rem; align-items:center; margin-bottom:1rem;">
                                    <select id="importRole" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                                        <option value="student">Students</option>
                                        <option value="teacher">Teachers</option>
                                    </select>
                                    <input type="file" id="csvFile" accept=".csv" class="brutal-input" style="margin-bottom:0;" required>
                                </div>
                                <button type="submit" class="brutal-btn success" id="uploadBtn">PROCESS CSV IMPORT</button>
                            </form>
                            <div id="importResults" style="margin-top:1.5rem; display:none;">
                                <div style="display:flex; justify-content:space-between; align-items:center; border-top:3px dashed #000; padding-top:1rem; margin-top:1rem;">
                                    <h4 style="margin:0; font-weight:900; font-size:1.2rem; text-transform:uppercase;">Import Results</h4>
                                    <button class="brutal-btn" id="downloadImportCsvBtn" style="background:#fff; color:#000;">DOWNLOAD CREDENTIALS</button>
                                </div>
                                <div style="max-height: 250px; overflow-y:auto; border:3px solid #000; margin-top:1rem;">
                                    <table style="width:100%; border-collapse:collapse;">
                                        <thead>
                                            <tr style="background:var(--bg-color, #f3f4f6);">
                                                <th style="border-bottom:3px solid #000; padding:0.5rem; font-weight:800; text-align:left;">Name</th>
                                                <th style="border-bottom:3px solid #000; padding:0.5rem; font-weight:800; text-align:left;">Generated ID</th>
                                                <th style="border-bottom:3px solid #000; padding:0.5rem; font-weight:800; text-align:left;">Password</th>
                                            </tr>
                                        </thead>
                                        <tbody id="importResultsBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-assignments" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0; border-bottom: 3px dashed #000; padding-bottom: 0.5rem;">Subject & Section Allocation</h2>
                            <div style="margin-top: 1.5rem;" id="directorAssignmentsConfig">
                                <p style="font-weight:800;">Loading roster fields...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-sectioning" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0; border-bottom: 3px dashed #000; padding-bottom: 0.5rem;">Student sectioning allocation</h2>
                            <div style="margin-top: 1.5rem;" id="directorSectioningConfig">
                                <p style="font-weight:800;">Loading student directory...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-parents" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0; border-bottom: 3px dashed #000; padding-bottom: 0.5rem;">Parent-Student Linkage & Logs</h2>
                            <div style="margin-top: 1.5rem;" id="directorParentsConfig">
                                <p style="font-weight:800;">Loading parent directory...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-subjects" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0; border-bottom: 3px dashed #000; padding-bottom: 0.5rem;">Curriculum Subjects</h2>
                            <div style="margin-top: 1.5rem;" id="directorSubjectsConfig">
                                <p style="font-weight:800;">Loading curriculum subjects...</p>
                            </div>
                        </div>
                    </div>

                    <div id="tab-director-config" class="tab-panel">
                        <div class="brutal-card">
                            <h2 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin-top:0; border-bottom: 3px dashed #000; padding-bottom: 0.5rem;">System Configuration Console</h2>
                            <div style="margin-top: 2rem;" id="directorConfigBlock">
                                <p style="font-weight:800;">Loading settings configuration...</p>
                            </div>
                        </div>
                    </div>
                `;
                await loadDirectorOverviewData();
                await loadDirectorAssignmentsData();
                await loadDirectorSubjectsData();
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
                container.innerHTML = `<div class="brutal-card" style="grid-column: 1/-1;"><p style="font-weight:800;">No subjects are registered/scheduled for your section yet.</p></div>`;
                return;
            }

            container.innerHTML = res.courses.map(course => `
                <div class="brutal-card" style="background: #fff; cursor:pointer;" onclick="viewStudentGrades(${course.subject_id}, '${course.subject_name}', '${course.teacher_name}')">
                    <span class="brutal-badge" style="background: var(--brutal-cyan); margin-bottom: 1rem;">Subject</span>
                    <h3 style="font-size: 1.8rem; font-weight: 900; text-transform: uppercase; margin: 0;">${course.subject_name}</h3>
                    <p style="font-weight: 800; color: var(--brutal-pink); margin-top: 0.25rem;">Teacher: ${course.teacher_name || 'TBD'}</p>
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 3px dashed #000; text-align:right;">
                        <span class="brutal-btn" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">View Scorecard &rarr;</span>
                    </div>
                </div>
            `).join('');
        }

        async function viewStudentGrades(subjectId, subjectName, teacherName) {
            const res = await apiRequest(`student/course-grades?subject_id=${subjectId}`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 2.2rem; font-weight:900; text-transform:uppercase; margin-top:0;">${subjectName} Scorecard</h2>
                <p style="font-weight:800; color: var(--brutal-pink); margin-top: -0.5rem; margin-bottom: 1.5rem;">Teacher: ${teacherName}</p>
                
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead>
                            <tr>
                                <th>Assessment</th>
                                <th>Type</th>
                                <th>Weight</th>
                                <th>My Score / Max</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.grades.map(g => `
                                <tr>
                                    <td>${g.title}</td>
                                    <td>${g.type_name}</td>
                                    <td>${(g.weight * 100)}%</td>
                                    <td>
                                        <span class="brutal-badge" style="background: ${g.score !== null ? 'var(--brutal-green)' : 'var(--brutal-pink)'}">
                                            ${g.score !== null ? `${g.score} / ${g.max_score}` : 'Ungraded'}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <div class="brutal-card" style="background: var(--brutal-yellow); margin-bottom:0; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-weight:900; font-size:1.15rem; text-transform:uppercase;">Normalized Course Average:</span>
                    <span style="font-weight:900; font-size:2rem; border:3px solid #000; padding:0.2rem 0.8rem; background:#fff;">${res.weighted_average}%</span>
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
                    <div style="background: var(--brutal-pink); border:4px solid #000; padding:1.5rem; font-weight:900; box-shadow: 4px 4px 0 #000;">
                        Year-end final evaluations are currently LOCKED/CLOSED by the Director Office. Grades compiling in progress.
                    </div>
                `;
                return;
            }

            if (res.status === 'pending') {
                block.innerHTML = `
                    <div style="background: var(--brutal-yellow); border:4px solid #000; padding:1.5rem; font-weight:900; box-shadow: 4px 4px 0 #000;">
                        Year-end final evaluations are OPEN, but your Homeroom Teacher is currently compiling your class averages and ranks. Please check back later.
                    </div>
                `;
                return;
            }

            const eval = res.evaluation;
            block.innerHTML = `
                <div class="dashboard-grid">
                    <div class="brutal-card" style="background: var(--brutal-green); margin:0;">
                        <span style="font-weight:900;">YEAR CUMULATIVE AVERAGE</span>
                        <h2 style="font-size:3.5rem; font-weight:900; margin:0;">${eval.average_score}%</h2>
                    </div>
                    <div class="brutal-card" style="background: var(--brutal-cyan); margin:0;">
                        <span style="font-weight:900;">OFFICIAL CLASS RANK</span>
                        <h2 style="font-size:3.5rem; font-weight:900; margin:0;">#${eval.class_rank}</h2>
                    </div>
                    <div class="brutal-card" style="background: ${eval.status === 'pass' ? 'var(--brutal-green)' : 'var(--brutal-pink)'}; margin:0;">
                        <span style="font-weight:900;">PROMOTION DECISION</span>
                        <h2 style="font-size:3.5rem; font-weight:900; text-transform:uppercase; margin:0;">${eval.status}</h2>
                    </div>
                </div>
                <div class="brutal-card" style="background: #fff; margin-top:2rem; border-style:dashed;">
                    <p style="font-weight:800; margin:0; font-size:1.05rem;">Signed and approved by homeroom teacher: <strong>${eval.evaluator_name || 'System Auto'}</strong> on ${new Date(eval.evaluated_at).toLocaleDateString()}</p>
                </div>
            `;
        }

        // ==========================================
        // TEACHER DASHBOARD CONTROLS
        // ==========================================

        async function loadTeacherPortalData() {
            const res = await apiRequest('teacher/classes');
            if (!res || !res.success) return;

            const grid = document.getElementById('teacherAssignmentsGrid');
            if (res.classes.length === 0) {
                grid.innerHTML = `<div class="brutal-card" style="grid-column: 1/-1;"><p style="font-weight:800;">No classes or subjects have been assigned to you by the Director yet.</p></div>`;
            } else {
                grid.innerHTML = res.classes.map(c => `
                    <div class="brutal-card" style="background: #fff;">
                        <span class="brutal-badge" style="background: var(--brutal-cyan); margin-bottom: 1rem;">Grade ${c.grade_level} ${c.stream}</span>
                        <h3 style="font-size: 1.8rem; font-weight: 900; text-transform: uppercase; margin: 0;">${c.subject_name}</h3>
                        <p style="font-weight: 800; color: var(--brutal-pink); margin-top: 0.25rem;">Section Classroom: ${c.section_name}</p>
                        
                        <div style="display: flex; gap: 0.5rem; flex-direction:column; margin-top: 1.5rem; padding-top: 1rem; border-top: 3px dashed #000;">
                            <button class="brutal-btn" style="background: var(--brutal-yellow); color:#000; width:100%;" onclick="viewClassAssessments(${c.assignment_id}, ${c.section_id}, '${c.subject_name} - ${c.section_name}')">Continuous Assessments &rarr;</button>
                            <button class="brutal-btn" style="background: #fff; color:#000; width:100%;" onclick="viewClassRoster(${c.section_id}, '${c.section_name}')">Roster Directory</button>
                        </div>
                    </div>
                `).join('');
            }

            const homeroomDiv = document.getElementById('teacherHomeroomConfig');
            if (!res.homeroom_class) {
                homeroomDiv.innerHTML = `
                    <div style="background: var(--brutal-pink); border:4px solid #000; padding:1.5rem; font-weight:900; box-shadow: 4px 4px 0 #000;">
                        You are not currently designated as a Homeroom Teacher for any class.
                    </div>
                `;
            } else {
                const sect = res.homeroom_class;
                homeroomDiv.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:4px solid #000; padding-bottom:1rem; margin-bottom:2rem;">
                        <div>
                            <h3 style="font-size:2rem; font-weight:900; margin:0; text-transform:uppercase;">Class ${sect.section_name} (Grade ${sect.grade_level})</h3>
                            <span class="brutal-badge" style="background: var(--brutal-green); margin-top:0.5rem;">Homeroom Active</span>
                        </div>
                        <button class="brutal-btn warning" onclick="loadHomeroomEvaluationsTable(${sect.section_id})">Compute Class Ranks & averages</button>
                    </div>
                    <div id="homeroomEvaluationsRoster">
                        <p style="font-weight:800;">Select option to load evaluations sheet.</p>
                    </div>
                `;
            }
        }

        async function viewClassRoster(sectionId, sectionName) {
            const res = await apiRequest(`teacher/class-students?section_id=${sectionId}`);
            if (!res || !res.success) return;

            const modalHtml = `
                <h2 style="font-size: 2.2rem; font-weight:900; text-transform:uppercase; margin-top:0;">Roster for Section ${sectionName}</h2>
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
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
                <h2 style="font-size: 2rem; font-weight:900; text-transform:uppercase; margin-top:0;">Edit Assessment</h2>
                <form onsubmit="handleEditAssessment(event, ${assessmentId}, ${assignmentId}, ${sectionId}, '${className}')" style="display:flex; flex-direction:column; gap:1rem; margin-top:1.5rem;">
                    <div>
                        <label style="display:block; margin-bottom:0.4rem; font-weight:800; font-size:0.85rem; text-transform:uppercase;">Title</label>
                        <input type="text" id="editAssTitle" class="brutal-input" required value="${title}">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.4rem; font-weight:800; font-size:0.85rem; text-transform:uppercase;">Max Score</label>
                        <input type="number" id="editAssMax" class="brutal-input" required value="${maxScore}" min="1" max="1000">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.4rem; font-weight:800; font-size:0.85rem; text-transform:uppercase;">Type</label>
                        <select id="editAssType" class="brutal-input" style="height:auto;" required>
                            ${types.map(t => `<option value="${t.id}" ${t.id == typeId ? 'selected' : ''}>${t.name} (${t.weight * 100}%)</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:0.4rem; font-weight:800; font-size:0.85rem; text-transform:uppercase;">Date</label>
                        <input type="date" id="editAssDate" class="brutal-input" value="${date}">
                    </div>
                    <div style="display:flex; gap:0.5rem; margin-top:0.5rem; justify-content:flex-end;">
                        <button type="button" class="brutal-btn" style="background:#fff; box-shadow:2px 2px 0 #000;" onclick="viewClassAssessments(${assignmentId}, ${sectionId}, '${className}')">Cancel</button>
                        <button type="submit" class="brutal-btn success" style="box-shadow:2px 2px 0 #000;">Save</button>
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
                <h2 style="font-size: 2rem; font-weight:900; text-transform:uppercase; margin-top:0;">Grade Book Panel</h2>
                <p style="font-weight:800; color:var(--brutal-pink); margin-top:-0.5rem; margin-bottom:1.5rem;">Student: ${studentName}</p>
                <form onsubmit="submitStudentSingleGrades(event, ${studentId}, ${assignmentId}, ${sectionId}, '${className}')">
                    <div class="brutal-table-container" style="margin-bottom:1.5rem;">
                        <table class="brutal-table">
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
                                            <input type="number" step="0.01" class="brutal-input student-single-score-input"
                                                   data-assessment-id="${g.assessment_id}" min="0" max="${g.max_score}"
                                                   style="margin-bottom:0; width:110px;" placeholder="Score"
                                                   value="${g.score !== null ? g.score : ''}">
                                        </td>
                                    </tr>
                                `).join('')}
                                ${res.grades.length === 0 ? '<tr><td colspan="4" style="text-align:center; font-style:italic;">No assessments yet. Create assessments first.</td></tr>' : ''}
                            </tbody>
                        </table>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                        <button type="button" class="brutal-btn" style="background:#fff; box-shadow:2px 2px 0 #000;" onclick="viewClassAssessments(${assignmentId}, ${sectionId}, '${className}')">Back</button>
                        <button type="submit" class="brutal-btn success" style="box-shadow:2px 2px 0 #000;">SAVE RESULTS</button>
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

            // Bulk grade entry: one score per student for this assessment
            const html = `
                <h2 style="font-size: 2.2rem; font-weight:900; text-transform:uppercase; margin-top:0;">ENTER SCORINGS SHEET</h2>
                <p style="font-weight:800; color: var(--brutal-pink); margin-top:-0.5rem; margin-bottom:1.5rem;">Ledger: ${assTitle} (Out of ${maxScore} points)</p>
                
                <form onsubmit="submitStudentGrades(event, ${assessmentId}, ${sectionId})">
                    <div class="brutal-table-container">
                        <table class="brutal-table">
                            <thead>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>Raw Score Input (Max ${maxScore})</th>
                            </thead>
                            <tbody>
                                ${studentsRes.students.map(s => `
                                    <tr>
                                        <td>${s.student_id}</td>
                                        <td>${s.full_name}</td>
                                        <td>
                                            <input type="number" step="0.01" class="brutal-input student-score-input" 
                                                   data-student-id="${s.id}" min="0" max="${maxScore}" style="margin-bottom:0; width:120px;" placeholder="Score">
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="brutal-btn success">SAVE SCORES LEDGER</button>
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
                    <div style="background: var(--brutal-pink); border:4px solid #000; padding:1.5rem; font-weight:900; box-shadow: 4px 4px 0 #000; margin-top:1.5rem;">
                        ERROR: Year-End final evaluations are currently LOCKED by the Director. You cannot submit evaluations yet.
                    </div>
                `;
                return;
            }

            rosterArea.innerHTML = `
                <div class="brutal-card" style="background: var(--brutal-green); margin-bottom:2.5rem;">
                    <p style="font-weight:900; margin:0;">CLASS COMPLIANCE ALERT</p>
                    <p style="font-weight:800; margin:0.25rem 0 0;">Weighted averages, class rankings, and promotion pass/fail triggers computed below automatically using Grading Service formula.</p>
                </div>
                
                <form onsubmit="submitHomeroomEvaluations(event, ${sectionId})">
                    <div class="brutal-table-container">
                        <table class="brutal-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
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
                                            <span style="font-weight:900; background: var(--brutal-yellow); padding:0.2rem 0.5rem; border:2px solid #000;">
                                                ${s.average}%
                                            </span>
                                            <input type="hidden" class="eval-student-avg" data-student-id="${s.id}" value="${s.average}">
                                            <input type="hidden" class="eval-student-rank" data-student-id="${s.id}" value="${s.rank}">
                                        </td>
                                        <td>
                                            <select class="brutal-input eval-student-status" data-student-id="${s.id}" style="margin-bottom:0; width:150px; height:auto;">
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
                        <button type="submit" class="brutal-btn success">SUBMIT FINAL COMPILED CLASS LEDGER</button>
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
                grid.innerHTML = `<div class="brutal-card" style="grid-column: 1/-1;"><p style="font-weight:800;">No students are currently linked to your parent portal. Please contact the school Administration.</p></div>`;
            } else {
                grid.innerHTML = res.students.map(s => `
                    <div class="brutal-card" style="background: #fff;">
                        <span class="brutal-badge" style="background: var(--brutal-cyan); margin-bottom: 1rem;">Child Profile</span>
                        <h3 style="font-size: 2.2rem; font-weight: 900; text-transform: uppercase; margin: 0;">${s.full_name}</h3>
                        <p style="font-weight: 800; color: var(--brutal-pink); margin-top: 0.25rem; font-size: 1.05rem;">Grade Section: ${s.section_name || 'Unassigned'}</p>
                        
                        <div style="margin-top: 1.5rem; padding: 1rem; border: 3px solid #000; background: var(--brutal-yellow); display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-weight:900;">Overall Average Score:</span>
                            <span style="font-weight:900; font-size:1.6rem;">${s.overall_average}%</span>
                        </div>

                        <div style="margin-top: 1.5rem; display:flex; gap:0.5rem;">
                            <button class="brutal-btn" style="width:100%; background:#fff; color:#000;" onclick="viewChildAcademicProgress(${s.id}, '${s.full_name}')">Track Ledgers &rarr;</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        async function viewChildAcademicProgress(studentId, studentName) {
            const res = await apiRequest(`student/courses`); // query student courses
            if (!res || !res.success) return;

            // Fetch course averages
            const modalHtml = `
                <h2 style="font-size: 2.2rem; font-weight:900; text-transform:uppercase; margin-top:0;">Progress Tracker</h2>
                <p style="font-weight:800; color: var(--brutal-pink); margin-top:-0.5rem; margin-bottom:1.5rem;">Child File: ${studentName}</p>

                <div class="brutal-card" style="background: var(--brutal-yellow); margin-bottom: 2rem;">
                    <p style="font-weight:900; margin:0;">CUMULATIVE COMBINED GPA: <strong>${res.overall_average}%</strong></p>
                </div>

                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead>
                            <tr>
                                <th>Subject Course</th>
                                <th>Course Tutor</th>
                                <th>Scorecard</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.courses.map(c => `
                                <tr>
                                    <td>${c.subject_name}</td>
                                    <td>${c.teacher_name || 'TBD'}</td>
                                    <td>
                                        <button class="brutal-btn" style="padding: 0.3rem 0.8rem; font-size: 0.8rem; box-shadow: 2px 2px 0 #000; background:var(--brutal-cyan); color:#000;"
                                                onclick="viewStudentGrades(${studentId}, '${c.subject_name}', '${c.teacher_name}')">
                                            VIEW LEDGER &rarr;
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
                <div class="brutal-card" style="background: var(--brutal-cyan);">
                    <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">${stats.total_students}</h2>
                    <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">Students Enrolled</p>
                </div>
                <div class="brutal-card" style="background: var(--brutal-yellow);">
                    <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">${stats.total_teachers}</h2>
                    <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">Active Faculty</p>
                </div>
                <div class="brutal-card" style="background: var(--brutal-pink);">
                    <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">${stats.total_subjects}</h2>
                    <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem;">Academic Subjects</p>
                </div>
                <div class="brutal-card" style="background: var(--brutal-purple); color: #fff;">
                    <h2 style="font-size: 4rem; font-weight: 900; margin: 0; line-height: 1;">${stats.total_sections}</h2>
                    <p style="font-weight: 900; font-size: 1.3rem; text-transform: uppercase; margin-top: 0.5rem; color:#fff;">Active Section Rooms</p>
                </div>
            `;
        }

        async function loadDirectorAssignmentsData() {
            const res = await apiRequest('director/assignment-data');
            if (!res || !res.success) return;

            const container = document.getElementById('directorAssignmentsConfig');
            
            container.innerHTML = `
                <div class="brutal-card" style="background:#f9fafb; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.3rem; font-weight:900;">Assign Teacher to Course Section</h3>
                    <form onsubmit="handleAssignTeacher(event)" style="display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:0.5rem;">
                        <select id="assignTeacherSelect" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Select Teacher --</option>
                            ${res.teachers.map(t => `<option value="${t.id}">${t.full_name} (${t.specialization || 'General'})</option>`).join('')}
                        </select>
                        <select id="assignSubjectSelect" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Select Subject --</option>
                            ${res.subjects.map(s => `<option value="${s.id}">${s.name} (Grade ${s.grade_level})</option>`).join('')}
                        </select>
                        <select id="assignSectionSelect" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Select Class --</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - Section ${sec.section_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="brutal-btn success">SCHEDULE</button>
                    </form>
                </div>

                <div class="brutal-card" style="background:#f9fafb; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.3rem; font-weight:900;">Section Homeroom Teacher Assignment</h3>
                    <form onsubmit="handleAssignHomeroom(event)" style="display:grid; grid-template-columns: 1fr 1fr auto; gap:0.5rem;">
                        <select id="homeroomSectionSelect" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Select Class --</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - Section ${sec.section_name} (${sec.homeroom_teacher_name ? `Current Homeroom: ${sec.homeroom_teacher_name}` : 'No Homeroom Assigned'})</option>`).join('')}
                        </select>
                        <select id="homeroomTeacherSelect" class="brutal-input" style="margin-bottom:0; height:auto;">
                            <option value="">-- No Homeroom (Unset) --</option>
                            ${res.teachers.map(t => `<option value="${t.id}">${t.full_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="brutal-btn warning">SET HOMEROOM</button>
                    </form>
                </div>

                <h3 style="font-size:1.6rem; font-weight:900; text-transform:uppercase; margin-top:2rem;">Current Teaching Assignments Schedules</h3>
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead>
                            <tr>
                                <th>Assigned Teacher</th>
                                <th>Subject Course</th>
                                <th>Section Classroom</th>
                                <th>Command</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.assignments.map(a => `
                                <tr>
                                    <td>${a.teacher_name}</td>
                                    <td>${a.subject_name}</td>
                                    <td>Grade ${a.grade_level} - Section ${a.section_name}</td>
                                    <td>
                                        <button class="brutal-btn danger" style="padding:0.3rem 0.8rem; font-size:0.85rem; box-shadow: 2px 2px 0 #000;" onclick="removeTeacherAssignment(${a.assignment_id})">REMOVE</button>
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
            if (!confirm("Are you sure you want to delete this teaching assignment schedule?")) return;
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
                <div class="brutal-card" style="background:#f9fafb; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.3rem; font-weight:900;">Auto-Sectioning (Random Assignment)</h3>
                    <p style="font-weight:700; font-size:0.9rem; margin-top:-0.5rem; margin-bottom:1rem; color:var(--brutal-pink);">
                        Randomly distributes all unsectioned students in the school evenly across active Grade Level sections.
                    </p>
                    <form onsubmit="handleRandomSectioning(event)" style="display:grid; grid-template-columns: 2fr auto; gap:0.5rem;">
                        <select id="randomSectioningGrade" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Grade Level --</option>
                            <option value="10">Grade 10 General</option>
                        </select>
                        <button type="submit" class="brutal-btn warning">EXECUTE RANDOM ASSIGNMENT</button>
                    </form>
                </div>

                <div class="brutal-card" style="background:#f9fafb; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.3rem; font-weight:900;">Manual Roster Transfer (Preferred Assignment)</h3>
                    <form onsubmit="handleAssignStudentSection(event)" style="display:grid; grid-template-columns: 1fr 1fr auto; gap:0.5rem;">
                        <select id="assignStudentSelect" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Student --</option>
                            ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_code}) [${s.section_name ? `Class: ${s.section_name}` : 'Unassigned'}]</option>`).join('')}
                        </select>
                        <select id="studentSectionSelect" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Target Section --</option>
                            <option value="">Unassign / Remove from Class</option>
                            ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} - Section ${sec.section_name}</option>`).join('')}
                        </select>
                        <button type="submit" class="brutal-btn success">SAVE TRANSFER</button>
                    </form>
                </div>

                <h3 style="font-size:1.6rem; font-weight:900; text-transform:uppercase; margin-top:2rem;">School Student Directory</h3>
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Student Email</th>
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
                                        <span class="brutal-badge" style="background: ${s.section_name ? 'var(--brutal-green)' : 'var(--brutal-pink)'}">
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
                <div class="brutal-card" style="background:#f9fafb; padding:1.5rem; margin-bottom:2rem;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.3rem; font-weight:900;">Link Parent to Student Profile</h3>
                    <form onsubmit="handleLinkParent(event)" style="display:grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap:0.5rem;">
                        <input type="text" id="parentName" class="brutal-input" style="margin-bottom:0;" required placeholder="Parent Full Name">
                        <input type="email" id="parentEmail" class="brutal-input" style="margin-bottom:0;" required placeholder="Parent Email">
                        <input type="text" id="parentPhone" class="brutal-input" style="margin-bottom:0;" required placeholder="Phone (e.g. +251...)">
                        <select id="parentStudentSelect" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Choose Child --</option>
                            ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_id})</option>`).join('')}
                        </select>
                        <button type="submit" class="brutal-btn success">LINK & SAVE</button>
                    </form>
                </div>

                <h3 style="font-size:1.6rem; font-weight:900; text-transform:uppercase; margin-top:2rem;">Registered Parents Directory</h3>
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead>
                            <tr>
                                <th>Parent Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Linked Child</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.parents.map(p => `
                                <tr>
                                    <td>${p.full_name}</td>
                                    <td>${p.email}</td>
                                    <td>${p.phone || 'N/A'}</td>
                                    <td>
                                        <span class="brutal-badge" style="background: var(--brutal-cyan)">Connected</span>
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

            // Fetch terms ledger
            const termsRes = await apiRequest('director/terms');
            let termsLedgerHtml = `<p style="font-weight:800;">No active terms found. Please reconfigure terms below.</p>`;
            
            if (termsRes && termsRes.success && termsRes.terms && termsRes.terms.length > 0) {
                termsLedgerHtml = `
                    <div class="brutal-table-container" style="margin-top:1rem;">
                        <table class="brutal-table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Term Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${termsRes.terms.map(t => `
                                    <tr>
                                        <td><strong>${t.name}</strong></td>
                                        <td>
                                            <span class="brutal-badge" style="background:${t.is_active ? 'var(--brutal-green)' : 'var(--brutal-pink)'}">
                                                ${t.is_active ? 'ACTIVE' : 'INACTIVE'}
                                            </span>
                                        </td>
                                        <td>
                                            ${t.is_active ? 
                                                `<span style="font-weight:800; font-size:0.9rem; color:#888;">CURRENT ACTIVE</span>` : 
                                                `<button class="brutal-btn success" style="padding:0.2rem 0.6rem; font-size:0.8rem;" onclick="handleSetTermActive(${t.id})">ACTIVATE</button>`
                                            }
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            container.innerHTML = `
                <div class="brutal-card" style="background: ${isFinalActive ? 'var(--brutal-green)' : '#fff'}; border-style: ${isFinalActive ? 'solid' : 'dashed'}; margin-bottom: 2rem;">
                    <h3 style="margin-top:0; font-size:1.8rem; font-weight:900; text-transform:uppercase;">Year-End Assessment Trigger</h3>
                    <p style="font-weight:800; font-size:1.05rem; margin-bottom:1.5rem;">
                        Toggling this trigger decides the active "End of Year" phase. When active, all homeroom teachers gain access to computing average GPAs, class rankings, and Promotion Pass/Fail status.
                    </p>
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                        <div>
                            <span style="font-weight:900; font-size:1.15rem;">STATUS:</span>
                            <span class="brutal-badge" style="background:${isFinalActive ? '#fff' : 'var(--brutal-pink)'}; font-size:1.15rem;">
                                ${isFinalActive ? 'OPEN / ACTIVE' : 'CLOSED / INACTIVE'}
                            </span>
                        </div>
                        <button class="brutal-btn ${isFinalActive ? 'danger' : 'success'}" onclick="toggleFinalMode(${isFinalActive ? 0 : 1})">
                            ${isFinalActive ? 'CLOSE YEAR-END PORTALS' : 'OPEN YEAR-END PORTALS'}
                        </button>
                    </div>
                </div>

                <div class="brutal-card">
                    <h3 style="margin-top:0; font-size:1.8rem; font-weight:900; text-transform:uppercase;">Academic Term Configuration</h3>
                    <p style="font-weight:800; font-size:1.05rem; margin-bottom:1.5rem;">
                        Choose whether your school operates on a 2-term (Semester) or 3-term (Trimester) cycle. 
                        <br><strong style="color:var(--brutal-pink);">WARNING:</strong> Reconfiguring terms will wipe existing terms and active logs for the active year.
                    </p>
                    <form onsubmit="handleConfigureTerms(event)" style="display:flex; gap:1rem; align-items:center; margin-bottom:2rem; flex-wrap:wrap;">
                        <select id="termSystemSelect" class="brutal-input" style="margin-bottom:0; width:280px; height:auto;" required>
                            <option value="2-term">Semester System (2 Terms)</option>
                            <option value="3-term">Trimester System (3 Terms)</option>
                        </select>
                        <button type="submit" class="brutal-btn" style="background:var(--brutal-yellow); color:#000;">RECONFIGURE TERMS</button>
                    </form>

                    <h4 style="font-weight:900; text-transform:uppercase; margin-top:2rem; margin-bottom:0.75rem; border-bottom:3px solid #000; padding-bottom:0.25rem;">Active Term Ledger</h4>
                    ${termsLedgerHtml}
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

        async function handleConfigureTerms(e) {
            e.preventDefault();
            const system_type = document.getElementById('termSystemSelect').value;
            if (!confirm("Are you absolutely sure? This will delete existing terms for the active year!")) return;

            const res = await apiRequest('director/terms', 'POST', { system_type });
            if (res && res.success) {
                alert(res.message);
                loadDirectorConfigData();
            } else {
                alert(res.message || "Failed to configure terms.");
            }
        }

        async function handleSetTermActive(termId) {
            const res = await apiRequest('director/terms', 'PUT', { term_id: termId });
            if (res && res.success) {
                alert(res.message);
                loadDirectorConfigData();
            } else {
                alert(res.message || "Failed to set term active.");
            }
        }

        // ==========================================
        // MASS USER REGISTRATION HANDLER
        // ==========================================
        let generatedCredentialsCsv = "";

        async function handleMassRegistration(e) {
            e.preventDefault();
            const fileInput = document.getElementById('csvFile');
            const role = document.getElementById('importRole').value;

            if (fileInput.files.length === 0) {
                alert("Please select a CSV file.");
                return;
            }

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('role', role);
            formData.append('school_id', localStorage.getItem('school_id') || 1);

            const uploadBtn = document.getElementById('uploadBtn');
            uploadBtn.textContent = "PROCESSING CSV IMPORT...";
            uploadBtn.disabled = true;

            try {
                const response = await fetch('/api/users/mass-register', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    body: formData
                });
                const res = await response.json();
                
                uploadBtn.textContent = "PROCESS CSV IMPORT";
                uploadBtn.disabled = false;

                if (res && res.success) {
                    let msg = `Import complete!\nSuccessfully imported: ${res.count} rows.`;
                    if (res.skipped > 0) {
                        msg += `\nSkipped/Failed: ${res.skipped} rows. Check detailed skipped report in browser console.`;
                    }
                    alert(msg);

                    // Show results table
                    document.getElementById('importResults').style.display = 'block';
                    const tbody = document.getElementById('importResultsBody');
                    
                    if (res.results && res.results.length > 0) {
                        tbody.innerHTML = res.results.map(r => `
                            <tr>
                                <td style="padding:0.5rem; border-bottom:1px solid #000;">${r.full_name}</td>
                                <td style="padding:0.5rem; border-bottom:1px solid #000;"><strong>${r.id_code}</strong></td>
                                <td style="padding:0.5rem; border-bottom:1px solid #000;"><code style="background:var(--brutal-yellow); padding:0.1rem 0.3rem;">${r.password}</code></td>
                            </tr>
                        `).join('');
                        
                        generatedCredentialsCsv = res.csv;
                    } else {
                        tbody.innerHTML = `<tr><td colspan="3" style="text-align:center; padding:1rem; font-weight:800;">No new users registered. All rows skipped.</td></tr>`;
                        generatedCredentialsCsv = "";
                    }

                    if (res.skipped_details && res.skipped_details.length > 0) {
                        console.warn("Mass Registration Skipped Details:", res.skipped_details);
                    }
                } else {
                    alert(res.message || "Failed to process mass registration CSV.");
                }
            } catch (err) {
                uploadBtn.textContent = "PROCESS CSV IMPORT";
                uploadBtn.disabled = false;
                alert("An error occurred during file upload.");
                console.error(err);
            }
        }

        // Wire up download import credentials CSV
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'downloadImportCsvBtn') {
                if (!generatedCredentialsCsv) {
                    alert("No credentials CSV found.");
                    return;
                }
                const blob = new Blob([generatedCredentialsCsv], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.setAttribute("href", url);
                link.setAttribute("download", "generated_credentials.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });

        // ==========================================
        // CURRICULUM SUBJECTS CRUD
        // ==========================================
        async function loadDirectorSubjectsData() {
            const res = await apiRequest('director/subjects');
            if (!res || !res.success) return;

            const container = document.getElementById('directorSubjectsConfig');
            
            container.innerHTML = `
                <div class="brutal-card" style="background:#f9fafb; padding:1.5rem; margin-bottom:2rem; border-style:dashed;">
                    <h3 style="margin-top:0; text-transform:uppercase; font-size:1.3rem; font-weight:900;">Add New Curriculum Subject</h3>
                    <form onsubmit="handleCreateSubject(event)" style="display:grid; grid-template-columns: 2fr 1fr auto; gap:0.5rem;">
                        <input type="text" id="newSubjectName" class="brutal-input" style="margin-bottom:0;" required placeholder="Subject Name (e.g. Chemistry)">
                        <select id="newSubjectGrade" class="brutal-input" style="margin-bottom:0; height:auto;" required>
                            <option value="">-- Select Grade Level --</option>
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                        <button type="submit" class="brutal-btn success">ADD SUBJECT</button>
                    </form>
                </div>

                <div class="brutal-table-container">
                    <table class="brutal-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Grade Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${res.subjects.length === 0 ? `<tr><td colspan="3" style="text-align:center; font-weight:800;">No curriculum subjects added yet.</td></tr>` : 
                                res.subjects.map(s => `
                                <tr>
                                    <td><strong>${s.name}</strong></td>
                                    <td><span class="brutal-badge" style="background:var(--brutal-cyan)">Grade ${s.grade_level}</span></td>
                                    <td>
                                        <button class="brutal-btn success" style="padding:0.2rem 0.6rem; font-size:0.8rem; margin-right:0.25rem;" onclick="handleEditSubject(${s.id}, '${s.name.replace(/'/g, "\\'")}', ${s.grade_level})">EDIT</button>
                                        <button class="brutal-btn danger" style="padding:0.2rem 0.6rem; font-size:0.8rem;" onclick="handleDeleteSubject(${s.id})">DELETE</button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function handleCreateSubject(e) {
            e.preventDefault();
            const name = document.getElementById('newSubjectName').value;
            const grade_level = document.getElementById('newSubjectGrade').value;

            const res = await apiRequest('director/subjects', 'POST', { name, grade_level });
            if (res && res.success) {
                alert(res.message);
                loadDirectorSubjectsData();
                if (typeof loadDirectorAssignmentsData === 'function') loadDirectorAssignmentsData();
            } else {
                alert(res.message || "Failed to create subject.");
            }
        }

        async function handleEditSubject(id, currentName, currentGrade) {
            const newName = prompt("Enter new subject name:", currentName);
            if (newName === null) return;
            const newGrade = prompt("Enter new grade level (9, 10, 11, 12):", currentGrade);
            if (newGrade === null) return;

            const res = await apiRequest('director/subjects', 'PUT', { subject_id: id, name: newName, grade_level: newGrade });
            if (res && res.success) {
                alert(res.message);
                loadDirectorSubjectsData();
                if (typeof loadDirectorAssignmentsData === 'function') loadDirectorAssignmentsData();
            } else {
                alert(res.message || "Failed to update subject.");
            }
        }

        async function handleDeleteSubject(id) {
            if (!confirm("Are you sure you want to delete this subject? This will delete all teaching assignments, assessments, and grade entries associated with this subject!")) return;

            const res = await apiRequest('director/subjects', 'DELETE', { subject_id: id });
            if (res && res.success) {
                alert(res.message);
                loadDirectorSubjectsData();
                if (typeof loadDirectorAssignmentsData === 'function') loadDirectorAssignmentsData();
            } else {
                alert(res.message || "Failed to delete subject.");
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
                container.innerHTML = `<p style="font-weight:800;">Failed to load messages logs.</p>`;
                return;
            }

            container.innerHTML = `
                <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.5rem;">
                    <div>
                        <h4 style="margin-top:0; text-transform:uppercase; font-weight:900; font-size:1.1rem; border-bottom:3px solid #000; padding-bottom:0.25rem;">Start New Chat</h4>
                        <form onsubmit="handleSendMessage(event, '${role}')">
                            <select id="chatTargetSelect" class="brutal-input" style="height:auto;" required>
                                <option value="">-- Choose Contact --</option>
                                ${res.contacts.map(c => `<option value="${c.id}" data-role="${c.role}">${c.full_name} (${c.role.toUpperCase()})</option>`).join('')}
                            </select>
                            <textarea id="chatMessageText" class="brutal-input" style="height:100px; resize:none;" required placeholder="Type message..."></textarea>
                            <button type="submit" class="brutal-btn success" style="width:100%;">SEND MESSAGE</button>
                        </form>
                    </div>

                    <div style="border-left:3px dashed #000; padding-left:1.5rem;">
                        <h4 style="margin-top:0; text-transform:uppercase; font-weight:900; font-size:1.1rem; border-bottom:3px solid #000; padding-bottom:0.25rem;">Chat Log Ledger</h4>
                        <div id="chatHistoryBox" style="height:350px; overflow-y:auto; display:flex; flex-direction:column; gap:1rem; padding:0.5rem; background:#f9fafb; border:3px solid #000; box-shadow:inset 3px 3px 0 rgba(0,0,0,0.05);">
                            ${res.messages.length === 0 ? `<p style="font-weight:800; text-align:center; margin-top:6rem; color:#9ca3af;">No communications logged yet.</p>` : 
                                res.messages.map(m => {
                                    const isSelf = m.sender_role === role && m.sender_id == localStorage.getItem('user_id');
                                    return `
                                        <div style="align-self: ${isSelf ? 'flex-end' : 'flex-start'}; max-width: 80%; background: ${isSelf ? 'var(--brutal-yellow)' : '#fff'}; border:3px solid #000; padding:0.8rem; box-shadow:3px 3px 0 #000;">
                                            <span style="font-size:0.75rem; font-weight:900; text-transform:uppercase; display:block; border-bottom:1.5px solid #000; padding-bottom:0.15rem; margin-bottom:0.4rem; color: ${isSelf ? '#000' : 'var(--brutal-pink)'}">
                                                ${isSelf ? 'ME (Sender)' : `${m.sender_name} (${m.sender_role.toUpperCase()})`}
                                            </span>
                                            <p style="margin:0; font-weight:800; font-size:0.95rem;">${m.message}</p>
                                            <span style="font-size:0.65rem; color:#6b7280; font-weight:800; text-align:right; display:block; margin-top:0.4rem;">
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

