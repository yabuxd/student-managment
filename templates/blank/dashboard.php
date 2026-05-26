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
         /* ---- Form grid utilities ---- */
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; align-items: end; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; align-items: end; }
        .form-grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 0.75rem; align-items: end; }
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
                        <button class="tab-btn" onclick="switchTab('tab-director-subjects')">Subjects</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-sections')">Section(Classes)</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-users')">Users &amp; Onboarding</button>
                        <button class="tab-btn" onclick="switchTab('tab-director-academic-years')">Academic Years &amp; Assessments</button>
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

                    <div id="tab-director-subjects" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem; letter-spacing:-0.02em;">Subject Curriculum</h2>
                            <div style="margin-top: 1.5rem;" id="directorSubjectsConfig">
                                <p style="font-style:italic;">Loading curriculum subjects...</p>
                            </div>
                        </div>
                    </div>

                <!-- Sections Management -->
                <div id="tab-director-sections" class="tab-panel">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                        <div>
                            <h1 style="font-size: 2.2rem; font-weight: 700; margin:0; letter-spacing:-0.03em;">Section Management</h1>
                            <p style="color: rgba(255,255,255,0.45); margin-top: 0.5rem;">Create and manage grade-level classroom sections</p>
                        </div>
                    </div>
                    <div class="bento-card" style="margin-bottom: 1.5rem;">
                        <h3 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.75rem; margin-bottom:1.25rem;">⊕ Create New Section</h3>
                        <form id="createSectionForm" onsubmit="handleCreateSection(event)">
                            <div class="form-grid-3" style="margin-bottom:1rem;">
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(255,255,255,0.45);display:block;margin-bottom:.4rem;">Section Name</label>
                                    <input type="text" id="newSectionName" class="enterprise-input" style="margin-bottom:0;" placeholder="e.g. Emerald, Alpha, 1A" required>
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(255,255,255,0.45);display:block;margin-bottom:.4rem;">Grade Level</label>
                                    <select id="newSectionGrade" class="enterprise-input" style="margin-bottom:0;height:auto;" required>
                                        <option value="">— Select Grade —</option>
                                        <option value="9">Grade 9</option>
                                        <option value="10">Grade 10</option>
                                        <option value="11">Grade 11</option>
                                        <option value="12">Grade 12</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(255,255,255,0.45);display:block;margin-bottom:.4rem;">Stream</label>
                                    <select id="newSectionStream" class="enterprise-input" style="margin-bottom:0;height:auto;" required>
                                        <option value="general">General</option>
                                        <option value="natural_science">Natural Science</option>
                                        <option value="social_science">Social Science</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="enterprise-btn success" id="createSectionBtn">Create Section</button>
                        </form>
                        <div id="sectionCreateNotice" style="margin-top:1rem;"></div>
                    </div>

                    <div class="bento-card">
                        <h3 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.75rem; margin-bottom:1.25rem;">Active Sections Directory</h3>
                        <div id="sectionsList">
                            <p style="color:rgba(255,255,255,0.45)">Loading sections...</p>
                        </div>
                    </div>
                </div>


                    <div id="tab-director-academic-years" class="tab-panel">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                            <div>
                                <h1 style="font-size: 2.2rem; font-weight: 700; margin:0; letter-spacing:-0.03em;">Academic Years &amp; Assessments</h1>
                                <p style="color: rgba(255,255,255,0.45); margin-top: 0.5rem;">Create and manage academic calendar cycles and institutional assessment types</p>
                            </div>
                        </div>

                        <!-- Academic Year Management -->
                        <h2 style="font-size: 1.5rem; color: #fff; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem;">Academic Years</h2>
                        <div class="bento-card" style="margin-bottom: 1.5rem;">
                            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.75rem; margin-bottom:1.25rem;">⊕ Add New Academic Year</h3>
                            <form id="createAcademicYearForm" onsubmit="handleCreateAcademicYear(event)">
                                <div class="form-grid-2" style="margin-bottom:1rem;">
                                    <div>
                                        <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(255,255,255,0.45);display:block;margin-bottom:.4rem;">Year Name</label>
                                        <input type="text" id="newYearName" class="enterprise-input" style="margin-bottom:0;" placeholder="e.g. 2016/17 E.C or 2024/25" required>
                                    </div>
                                    <div style="display:flex; flex-direction:column; justify-content:flex-end;">
                                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; margin-bottom:0.75rem;">
                                            <input type="checkbox" id="setYearActive" style="width:16px;height:16px;"> 
                                            <span style="font-size:0.85rem; font-weight:600; color:rgba(255,255,255,0.7);">Set as Active Year immediately</span>
                                        </label>
                                        <button type="submit" class="enterprise-btn success" id="createYearBtn">Create Academic Year</button>
                                    </div>
                                </div>
                            </form>
                            <div id="yearCreateNotice" style="margin-top:1rem;"></div>
                        </div>
                        <div class="bento-card" style="margin-bottom: 3rem;">
                            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.75rem; margin-bottom:1.25rem;">Academic Year Registry</h3>
                            <div id="academicYearsList"><p style="color:rgba(255,255,255,0.45);">Loading academic years...</p></div>
                        </div>

                        <!-- Assessment Types Management -->
                        <h2 style="font-size: 1.5rem; color: #fff; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem;">Assessment Types</h2>
                        <div class="bento-card" style="margin-bottom: 1.5rem;">
                            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.75rem; margin-bottom:1.25rem;">⊕ Add New Assessment Type</h3>
                            <form id="createAssessmentTypeForm" onsubmit="handleCreateAssessmentType(event)">
                                <div class="form-grid-3" style="margin-bottom:1rem;">
                                    <div>
                                        <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(255,255,255,0.45);display:block;margin-bottom:.4rem;">Assessment Name</label>
                                        <input type="text" id="newAssessmentName" class="enterprise-input" style="margin-bottom:0;" placeholder="e.g. Mid Exam, Quiz 1, Final" required>
                                    </div>
                                    <div>
                                        <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(255,255,255,0.45);display:block;margin-bottom:.4rem;">Weight (e.g. 0.3 = 30%)</label>
                                        <input type="number" id="newAssessmentWeight" step="0.01" min="0.01" max="1" class="enterprise-input" style="margin-bottom:0;" placeholder="e.g. 0.3" required>
                                    </div>
                                    <div style="display:flex; align-items:flex-end;">
                                        <button type="submit" class="enterprise-btn success" id="createAssessmentBtn" style="width:100%;">Create Assessment</button>
                                    </div>
                                </div>
                            </form>
                            <div id="assessmentCreateNotice" style="margin-top:1rem;"></div>
                        </div>
                        <div class="bento-card">
                            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.75rem; margin-bottom:1.25rem;">Active Assessment Types</h3>
                            <div id="assessmentTypesList"><p style="color:rgba(255,255,255,0.45);">Loading assessment types...</p></div>
                        </div>
                    </div>

                    <div id="tab-director-users" class="tab-panel">
                        <div class="bento-card">
                            <h2 style="font-size: 1.8rem; font-weight: 700; margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.5rem; letter-spacing:-0.02em;">User Administration & Onboarding</h2>
                            <div style="margin-top: 1.5rem;" id="directorUsersConfig">
                                <p style="font-style:italic;">Loading onboarding tools...</p>
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
                await loadDirectorSubjectsData();
                await loadDirectorUsersData();
                await loadDirectorConfigData();
                await loadSectionsList();
                await loadAcademicYearsList();
                await loadAssessmentTypesList();
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
                <h2 style="font-size: 1.6rem; font-weight:700; color:#fff; margin-top:0;">Grade Book Panel</h2>
                <p style="font-size:0.95rem; color:rgba(255,255,255,0.45); margin-top:-0.5rem; margin-bottom:1.5rem;">Student: <span style="color:var(--primary); font-weight:600;">${studentName}</span></p>
                <form onsubmit="submitStudentSingleGrades(event, ${studentId}, ${assignmentId}, ${sectionId}, '${className}')">
                    <div class="enterprise-table-container" style="margin-bottom:1.5rem;">
                        <table class="enterprise-table">
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
                                            <input type="number" step="0.01" class="enterprise-input student-single-score-input"
                                                   data-assessment-id="${g.assessment_id}" min="0" max="${g.max_score}"
                                                   style="margin-bottom:0; width:110px;" placeholder="Score"
                                                   value="${g.score !== null ? g.score : ''}">
                                        </td>
                                    </tr>
                                `).join('')}
                                ${res.grades.length === 0 ? '<tr><td colspan="4" style="text-align:center; color:rgba(255,255,255,0.3); font-style:italic;">No assessments yet. Create assessments first.</td></tr>' : ''}
                            </tbody>
                        </table>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                        <button type="button" class="enterprise-btn-outline" onclick="viewClassAssessments(${assignmentId}, ${sectionId}, '${className}')">Back</button>
                        <button type="submit" class="enterprise-btn">SAVE RESULTS</button>
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
                        <h4 style="margin-top:0; font-family:'Inter'; font-weight:600; color:#fff;">Add New Subject</h4>
                        <form onsubmit="handleAddSubject(event)">
                            <label style="display:block; margin-bottom:0.5rem; color:rgba(255,255,255,0.7); font-size:0.85rem;">Subject Name</label>
                            <input type="text" id="newSubjectName" class="enterprise-input" required placeholder="e.g. Advanced Calculus">
                            <label style="display:block; margin-bottom:0.5rem; color:rgba(255,255,255,0.7); font-size:0.85rem;">Grade Level</label>
                            <input type="number" id="newSubjectGrade" class="enterprise-input" required min="1" max="12" placeholder="e.g. 10">
                            <button type="submit" class="enterprise-btn" style="width:100%;">Create Subject</button>
                        </form>
                    </div>
                    <div>
                        <h4 style="margin-top:0; font-family:'Inter'; font-weight:600; color:#fff;">Active Subjects</h4>
                        <div class="enterprise-table-container">
                            <table class="enterprise-table">
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
                                                <button class="enterprise-btn danger" style="padding: 0.3rem 0.8rem; font-size: 0.75rem;" onclick="handleDeleteSubject(${sub.id})">Delete</button>
                                            </td>
                                        </tr>
                                    `).join('')}
                                    ${res.subjects.length === 0 ? '<tr><td colspan="3" style="text-align:center; font-style:italic; color:rgba(255,255,255,0.45);">No subjects found.</td></tr>' : ''}
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
                    <div style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); padding:1.5rem; border-radius:1rem;">
                        <h3 style="margin-top:0; font-size:1.2rem; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.5rem;">Single User Registration</h3>
                        <form onsubmit="handleSingleUserReg(event)">
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; margin-bottom:0.5rem; color:rgba(255,255,255,0.7); font-size:0.85rem;">Full Name</label>
                                <input type="text" id="suName" class="enterprise-input" required placeholder="hayle girma">
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; margin-bottom:0.5rem; color:rgba(255,255,255,0.7); font-size:0.85rem;">Email Address</label>
                                <input type="email" id="suEmail" class="enterprise-input" required placeholder="hayle@example.com">
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; margin-bottom:0.5rem; color:rgba(255,255,255,0.7); font-size:0.85rem;">Role</label>
                                <select id="suRole" class="enterprise-input" style="height:auto;">
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher / Faculty</option>
                                </select>
                            </div>
                            <button type="submit" class="enterprise-btn" style="width:100%;" id="suBtn">Register Account</button>
                        </form>
                    </div>

                    <!-- Mass User Form -->
                    <div style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); padding:1.5rem; border-radius:1rem;">
                        <h3 style="margin-top:0; font-size:1.2rem; color:#fff; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.5rem;">Mass CSV Registration</h3>
                        <p style="font-size:0.9rem; color:rgba(255,255,255,0.5);">Upload a CSV with columns <b>Full Name</b> and <b>Email</b>.</p>
                        <form onsubmit="handleMassReg(event)">
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; margin-bottom:0.5rem; color:rgba(255,255,255,0.7); font-size:0.85rem;">Select CSV File</label>
                                <input type="file" id="muFile" class="enterprise-input" accept=".csv" required style="padding:0.6rem;">
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; margin-bottom:0.5rem; color:rgba(255,255,255,0.7); font-size:0.85rem;">Target Role</label>
                                <select id="muRole" class="enterprise-input" style="height:auto;">
                                    <option value="student">Students</option>
                                    <option value="teacher">Teachers / Faculty</option>
                                </select>
                            </div>
                            <button type="submit" class="enterprise-btn" style="width:100%; background:var(--secondary, #30d158); border-color:var(--secondary, #30d158);" id="muBtn">Process CSV Import</button>
                        </form>
                        <div id="importResults" style="margin-top:1.5rem; display:none;">
                            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(255,255,255,0.08); padding-top:1rem; margin-top:1rem;">
                                <h4 style="margin:0; font-family:'Inter'; font-weight:600; color:#fff; font-size:1.1rem; text-transform:uppercase;">Import Results</h4>
                                <button class="enterprise-btn-outline" id="downloadImportCsvBtn" style="padding:0.3rem 0.8rem; font-size:0.8rem; border-radius:0.5rem;">DOWNLOAD CREDENTIALS</button>
                            </div>
                            <div style="max-height: 250px; overflow-y:auto; border:1px solid rgba(255,255,255,0.08); margin-top:1rem; border-radius:0.5rem;">
                                <table style="width:100%; border-collapse:collapse; font-family:'Inter'; font-size:0.9rem; color:#fff;">
                                    <thead>
                                        <tr style="background:rgba(255,255,255,0.02);">
                                            <th style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.5rem; font-weight:600; text-align:left; color:rgba(255,255,255,0.7);">Name</th>
                                            <th style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.5rem; font-weight:600; text-align:left; color:rgba(255,255,255,0.7);">Generated ID</th>
                                            <th style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.5rem; font-weight:600; text-align:left; color:rgba(255,255,255,0.7);">Password</th>
                                        </tr>
                                    </thead>
                                    <tbody id="importResultsBody"></tbody>
                                </table>
                            </div>
                        </div>
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
                alert(res.message + "\nUser ID: " + res.id_code + "\nPassword: " + res.password);
                e.target.reset();
                loadDirectorOverviewData();
            } else {
                alert((res ? res.message : "Error creating user."));
            }
        }

        let generatedCredentialsCsv = "";

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
                    let msg = "Import complete!\nSuccessfully imported: " + res.count + " rows.";
                    if (res.skipped > 0) msg += "\nSkipped/Failed: " + res.skipped + " rows.";
                    alert(msg);

                    // Show results table
                    document.getElementById('importResults').style.display = 'block';
                    const tbody = document.getElementById('importResultsBody');
                    
                    if (res.results && res.results.length > 0) {
                        tbody.innerHTML = res.results.map(r => `
                            <tr>
                                <td style="padding:0.5rem; border-bottom:1px solid rgba(255,255,255,0.08);">${r.full_name}</td>
                                <td style="padding:0.5rem; border-bottom:1px solid rgba(255,255,255,0.08);"><strong>${r.id_code}</strong></td>
                                <td style="padding:0.5rem; border-bottom:1px solid rgba(255,255,255,0.08);"><code style="background:rgba(255,255,255,0.05); padding:0.1rem 0.3rem; border-radius:3px; color:#fff;">${r.password}</code></td>
                            </tr>
                        `).join('');
                        
                        generatedCredentialsCsv = res.csv;
                    } else {
                        tbody.innerHTML = `<tr><td colspan="3" style="text-align:center; padding:1rem; font-style:italic; color:rgba(255,255,255,0.45);">No new users registered. All rows skipped.</td></tr>`;
                        generatedCredentialsCsv = "";
                    }

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
        /* ---- Section Creation ---- */
    async function handleCreateSection(e) {
        e.preventDefault();
        const btn = document.getElementById('createSectionBtn');
        btn.textContent = 'Creating...'; btn.disabled = true;

        const res = await apiRequest('director/create-section', 'POST', {
            name:        document.getElementById('newSectionName').value,
            grade_level: document.getElementById('newSectionGrade').value,
            stream:      document.getElementById('newSectionStream').value
        });

        btn.textContent = 'Create Section'; btn.disabled = false;

        const notice = document.getElementById('sectionCreateNotice');
        if (res && res.success) {
            if (notice) notice.innerHTML = `<div class="notice success">✓ ${res.message}</div>`;
            document.getElementById('createSectionForm').reset();
            await loadSectionsList();
            await loadDirectorAssignmentsData();
            await loadDirectorSectioningData();
        } else {
            if (notice) notice.innerHTML = `<div class="notice danger">✗ ${res ? res.message : 'Failed to create section.'}</div>`;
        }
    }

    async function loadSectionsList() {
        const res = await apiRequest('director/assignment-data');
        const container = document.getElementById('sectionsList');
        if (!container) return;

        if (!res || !res.success || !res.sections || res.sections.length === 0) {
            container.innerHTML = `<div class="notice info">No sections created yet. Use the form above to create one.</div>`;
            return;
        }

        container.innerHTML = `
            <div class="enterprise-table-container">
                <table class="enterprise-table">
                    <thead><tr><th>Section Name</th><th>Grade Level</th><th>Homeroom Teacher</th><th>Students</th><th>Actions</th></tr></thead>
                    <tbody>
                        ${res.sections.map(sec => `
                            <tr>
                                <td><strong>${sec.section_name}</strong></td>
                                <td><span class="section-chip">Grade ${sec.grade_level}</span></td>
                                <td>${sec.homeroom_teacher_name || '<span style="color:var(--theme-text-muted)">Not assigned</span>'}</td>
                                <td><span class="section-chip" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.25);color:#6ee7b7;">${sec.student_count ?? '—'} students</span></td>
                                <td style="display:flex;gap:.35rem;">
                                    <button class="enterprise-btn" style="padding:.3rem .7rem;font-size:.8rem;" onclick="openEditSectionModal(${sec.id}, '${sec.section_name.replace(/'/g, "\\'")}', ${sec.grade_level}, '${sec.stream || 'general'}')">Edit</button>
                                    <button class="enterprise-btn danger" style="padding:.3rem .7rem;font-size:.8rem;" onclick="handleDeleteSection(${sec.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    function openEditSectionModal(id, name, gradeLevel, stream) {
        showModal(`
            <h2 style="font-size:1.5rem;font-weight:900;text-transform:uppercase;margin-top:0;">Edit Section</h2>
            <form onsubmit="handleEditSection(event, ${id})" style="display:flex;flex-direction:column;gap:1rem;margin-top:1.5rem;">
                <div>
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;color:var(--theme-text-muted);">Section Name</label>
                    <input type="text" id="editSectionName" class="enterprise-input" required value="${name}">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;color:var(--theme-text-muted);">Grade Level</label>
                    <select id="editSectionGrade" class="enterprise-inputt" style="height:auto;" required>
                        <option value="9" ${gradeLevel == 9 ? 'selected' : ''}>Grade 9</option>
                        <option value="10" ${gradeLevel == 10 ? 'selected' : ''}>Grade 10</option>
                        <option value="11" ${gradeLevel == 11 ? 'selected' : ''}>Grade 11</option>
                        <option value="12" ${gradeLevel == 12 ? 'selected' : ''}>Grade 12</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;color:var(--theme-text-muted);">Stream</label>
                    <select id="editSectionStream" class="enterprise-input" style="height:auto;" required>
                        <option value="general" ${stream === 'general' ? 'selected' : ''}>General</option>
                        <option value="natural_science" ${stream === 'natural_science' ? 'selected' : ''}>Natural Science</option>
                        <option value="social_science" ${stream === 'social_science' ? 'selected' : ''}>Social Science</option>
                    </select>
                </div>
                <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:1rem;">
                    <button type="button" class="enterprise-btn" style="background:rgba(255,255,255,.05);border:1px solid var(--glass-border);" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="enterprise-btn success">Save Changes</button>
                </div>
            </form>
        `);
    }

    async function handleEditSection(e, sectionId) {
        e.preventDefault();
        const res = await apiRequest('director/update-section', 'POST', {
            section_id: sectionId,
            name: document.getElementById('editSectionName').value,
            grade_level: document.getElementById('editSectionGrade').value,
            stream: document.getElementById('editSectionStream').value
        });
        if (res && res.success) {
            alert(res.message);
            closeModal();
            await loadSectionsList();
            await loadDirectorAssignmentsData();
            await loadDirectorSectioningData();
        } else {
            alert(res ? res.message : 'Failed to update section.');
        }
    }

    async function handleDeleteSection(sectionId) {
        if (!confirm('Are you sure you want to delete this section? All related teaching assignments and assessment grades will be permanently deleted.')) return;
        const res = await apiRequest('director/delete-section', 'POST', { section_id: sectionId });
        if (res && res.success) {
            alert(res.message);
            await loadSectionsList();
            await loadDirectorAssignmentsData();
            await loadDirectorSectioningData();
        } else {
            alert(res ? res.message : 'Failed to delete section.');
        }
    }

    // ==========================================
    // ACADEMIC YEAR MANAGEMENT
    // ==========================================

    async function loadAcademicYearsList() {
        const res = await apiRequest('director/academic-years');
        const container = document.getElementById('academicYearsList');
        if (!container) return;

        if (!res || !res.success) {
            container.innerHTML = `<div style="border:1px solid rgba(255,69,58,0.3);background:rgba(255,69,58,0.05);color:#ff453a;padding:1rem;border-radius:0.5rem;">Failed to load academic years.</div>`;
            return;
        }

        if (!res.years || res.years.length === 0) {
            container.innerHTML = `<div style="border:1px solid rgba(255,255,255,0.08);padding:1rem;border-radius:0.5rem;color:rgba(255,255,255,0.45);">No academic years created yet. Use the form above to add one.</div>`;
            return;
        }

        container.innerHTML = `
            <div class="enterprise-table-container">
                <table class="enterprise-table">
                    <thead>
                        <tr>
                            <th>Academic Year</th>
                            <th>Status</th>
                            <th>Terms</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${res.years.map(y => `
                            <tr>
                                <td><strong>${y.name}</strong></td>
                                <td>
                                    <span class="enterprise-badge" style="color:${y.is_active ? '#30d158' : 'rgba(255,255,255,0.45)'}; border-color:${y.is_active ? 'rgba(48,209,88,0.2)' : 'rgba(255,255,255,0.08)'}; background:${y.is_active ? 'rgba(48,209,88,0.08)' : 'transparent'};">
                                        ${y.is_active ? '● Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td><span class="enterprise-badge">Semester System</span></td>
                                <td style="display:flex; gap:0.35rem; flex-wrap:wrap;">
                                    ${!y.is_active ? `<button class="enterprise-btn success" style="padding:0.3rem 0.8rem;font-size:0.75rem;" onclick="handleSetYearActive(${y.id})">Set Active</button>` : '<span style="font-size:0.78rem;color:rgba(255,255,255,0.45);">Current</span>'}
                                    ${!y.is_active ? `<button class="enterprise-btn danger" style="padding:0.3rem 0.8rem;font-size:0.75rem;" onclick="handleDeleteAcademicYear(${y.id})">Delete</button>` : ''}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    async function handleCreateAcademicYear(e) {
        e.preventDefault();
        const btn = document.getElementById('createYearBtn');
        btn.textContent = 'Creating...'; btn.disabled = true;

        const res = await apiRequest('director/academic-years', 'POST', {
            name: document.getElementById('newYearName').value,
            set_active: document.getElementById('setYearActive').checked ? 1 : 0
        });

        btn.textContent = 'Create Academic Year'; btn.disabled = false;
        const notice = document.getElementById('yearCreateNotice');
        if (res && res.success) {
            notice.innerHTML = `<div style="border:1px solid rgba(48,209,88,0.3);background:rgba(48,209,88,0.05);color:#30d158;padding:0.75rem;border-radius:0.5rem;font-weight:600;">&#10003; ${res.message}</div>`;
            document.getElementById('createAcademicYearForm').reset();
            await loadAcademicYearsList();
            await loadDirectorConfigData(); // refresh terms tab too
        } else {
            notice.innerHTML = `<div style="border:1px solid rgba(255,69,58,0.3);background:rgba(255,69,58,0.05);color:#ff453a;padding:0.75rem;border-radius:0.5rem;font-weight:600;">&#10007; ${res ? res.message : 'Failed to create year.'}</div>`;
        }
    }

    async function handleSetYearActive(yearId) {
        const res = await apiRequest('director/academic-years', 'PUT', { year_id: yearId });
        if (res && res.success) { alert(res.message); loadAcademicYearsList(); loadDirectorConfigData(); }
        else alert(res ? res.message : 'Failed to activate year.');
    }

    async function handleDeleteAcademicYear(yearId) {
        if (!confirm('Delete this academic year? All its terms will also be removed.')) return;
        const res = await apiRequest('director/academic-years', 'DELETE', { year_id: yearId });
        if (res && res.success) { alert(res.message); loadAcademicYearsList(); }
        else alert(res ? res.message : 'Failed to delete year.');
    }

    // ==========================================
    // ASSESSMENT TYPES MANAGEMENT
    // ==========================================

    async function loadAssessmentTypesList() {
        const res = await apiRequest('director/assessment-types');
        const container = document.getElementById('assessmentTypesList');
        if (!container) return;

        if (!res || !res.success) {
            container.innerHTML = `<div style="border:1px solid rgba(255,69,58,0.3);background:rgba(255,69,58,0.05);color:#ff453a;padding:1rem;border-radius:0.5rem;">Failed to load assessment types.</div>`;
            return;
        }

        if (!res.assessment_types || res.assessment_types.length === 0) {
            container.innerHTML = `<div style="border:1px solid rgba(255,255,255,0.08);padding:1rem;border-radius:0.5rem;color:rgba(255,255,255,0.45);">No assessment types created yet. Use the form above to add one.</div>`;
            return;
        }

        container.innerHTML = `
            <div class="enterprise-table-container">
                <table class="enterprise-table">
                    <thead>
                        <tr>
                            <th>Assessment Name</th>
                            <th>Weight</th>
                            <th>Percentage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${res.assessment_types.map(a => `
                            <tr>
                                <td><strong>${a.name}</strong></td>
                                <td>${a.weight}</td>
                                <td>${Math.round(a.weight * 100)}%</td>
                                <td>
                                    <button class="enterprise-btn danger" style="padding:0.3rem 0.8rem;font-size:0.75rem;" onclick="handleDeleteAssessmentType(${a.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    async function handleCreateAssessmentType(e) {
        e.preventDefault();
        const btn = document.getElementById('createAssessmentBtn');
        btn.textContent = 'Creating...'; btn.disabled = true;

        const res = await apiRequest('director/assessment-types', 'POST', {
            name: document.getElementById('newAssessmentName').value,
            weight: document.getElementById('newAssessmentWeight').value
        });

        btn.textContent = 'Create Assessment'; btn.disabled = false;
        const notice = document.getElementById('assessmentCreateNotice');
        if (res && res.success) {
            notice.innerHTML = `<div style="border:1px solid rgba(48,209,88,0.3);background:rgba(48,209,88,0.05);color:#30d158;padding:0.75rem;border-radius:0.5rem;font-weight:600;">&#10003; ${res.message}</div>`;
            document.getElementById('createAssessmentTypeForm').reset();
            await loadAssessmentTypesList();
        } else {
            notice.innerHTML = `<div style="border:1px solid rgba(255,69,58,0.3);background:rgba(255,69,58,0.05);color:#ff453a;padding:0.75rem;border-radius:0.5rem;font-weight:600;">&#10007; ${res ? res.message : 'Failed to create assessment type.'}</div>`;
        }
    }

    async function handleDeleteAssessmentType(typeId) {
        if (!confirm('Delete this assessment type? If teachers are already using it, it cannot be deleted.')) return;
        const res = await apiRequest('director/assessment-types', 'DELETE', { type_id: typeId });
        if (res && res.success) { alert(res.message); loadAssessmentTypesList(); }
        else alert(res ? res.message : 'Failed to delete assessment type.');
    }

    </script>
</body>
</html>

