<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }
$templateName = $schoolSite['template_name'] ?? 'aurora';
$primaryColor = !empty($schoolSite['primary_color']) ? $schoolSite['primary_color'] : '#6366f1';
$themePath    = !empty($schoolSite['theme_path'])    ? $schoolSite['theme_path']    : 'assets/css/themes/theme1.css';
$typography   = !empty($schoolSite['typography'])    ? $schoolSite['typography']    : 'Plus Jakarta Sans';
$brandName    = !empty($schoolSite['name'])          ? $schoolSite['name']          : 'School Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Dashboard</title>
    <meta name="description" content="Institutional management dashboard for <?php echo htmlspecialchars($brandName); ?>">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Dynamic school theme (CSS variables) -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <!-- Aurora global design system -->
    <link rel="stylesheet" href="/templates/aurora/assets/css/style.css">
    <style>
        /* =====================================================
           AURORA DASHBOARD — Local overrides & utilities
           ===================================================== */
        :root {
            --font-sans:      '<?php echo htmlspecialchars($typography); ?>', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --primary:        var(--accent-1, #6366f1);
            --secondary:      var(--accent-2, #06b6d4);
            --tertiary:       var(--accent-3, #d946ef);
            --success:        #10b981;
            --warning:        #f59e0b;
            --danger:         #f43f5e;
            --grad-primary:   linear-gradient(135deg, var(--primary), #a855f7);
            --grad-secondary: linear-gradient(135deg, var(--secondary), #3b82f6);
            --grad-danger:    linear-gradient(135deg, var(--danger), #ec4899);
            --grad-success:   linear-gradient(135deg, var(--success), #059669);
            --grad-warning:   linear-gradient(135deg, var(--warning), #d97706);
            --glass-bg:       rgba(var(--bg-card-rgb, 16, 22, 38), 0.65);
            --glass-border:   rgba(255,255,255,0.07);
        }

        /* ---- Ambient decorations ---- */
        .deco-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
        }
        .deco-orb-1 {
            width: 420px; height: 420px;
            top: 2%; left: 2%;
            background: radial-gradient(circle, color-mix(in srgb, var(--primary) 10%, transparent) 0%, transparent 70%);
        }
        .deco-orb-2 {
            width: 380px; height: 380px;
            bottom: 2%; right: 2%;
            background: radial-gradient(circle, color-mix(in srgb, var(--secondary) 10%, transparent) 0%, transparent 70%);
        }
        .deco-orb-3 {
            width: 250px; height: 250px;
            top: 40%; left: 50%;
            background: radial-gradient(circle, color-mix(in srgb, var(--tertiary) 6%, transparent) 0%, transparent 70%);
        }

        /* ---- Layout ---- */
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.1rem 1.75rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            margin-bottom: 2.5rem;
            position: sticky;
            top: 1rem;
            z-index: 100;
        }
        .dash-brand {
            font-size: 1.2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background: var(--grad-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dash-user-area {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        .dash-user-info { text-align: right; }
        .dash-username {
            font-weight: 800;
            font-size: 0.95rem;
            text-transform: uppercase;
            display: block;
        }
        .dash-role-pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.2rem 0.65rem;
            border-radius: 99px;
            background: color-mix(in srgb, var(--primary) 15%, transparent);
            border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
            color: var(--primary);
            margin-top: 0.3rem;
        }
        .dash-logout {
            background: rgba(244,63,94,0.1);
            border: 1px solid rgba(244,63,94,0.25);
            color: var(--danger) !important;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.55rem 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .dash-logout:hover {
            background: var(--danger);
            color: #fff !important;
            border-color: var(--danger);
        }

        /* ---- Page title blocks ---- */
        .page-title {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
            background: linear-gradient(180deg, #ffffff 30%, color-mix(in srgb, var(--primary) 60%, #ffffff) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .page-subtitle {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--theme-text-muted, #94a3b8);
            margin-bottom: 2.5rem;
        }

        /* ---- Stat cards ---- */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }
        .stat-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 1.5rem;
            backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 3px;
        }
        .stat-card:nth-child(1)::after { background: var(--grad-primary); }
        .stat-card:nth-child(2)::after { background: var(--grad-secondary); }
        .stat-card:nth-child(3)::after { background: var(--grad-danger); }
        .stat-card:nth-child(4)::after { background: linear-gradient(135deg, #8b5cf6, #6366f1); }
        .stat-label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--theme-text-muted, #94a3b8);
            margin-bottom: 0.6rem;
        }
        .stat-value {
            font-size: 2.6rem;
            font-weight: 900;
            line-height: 1;
        }
        .stat-card:nth-child(1) .stat-value { color: var(--primary); }
        .stat-card:nth-child(2) .stat-value { color: var(--secondary); }
        .stat-card:nth-child(3) .stat-value { color: var(--tertiary); }
        .stat-card:nth-child(4) .stat-value { color: #a78bfa; }

        /* ---- Glass card with section line ---- */
        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2rem;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 2px;
            background: var(--grad-primary);
            opacity: 0.7;
        }
        .glass-card h2, .glass-card h3 {
            margin-top: 0;
            font-weight: 800;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 1.5rem;
            color: var(--theme-text, #f8fafc);
        }

        /* ---- Result / credential card ---- */
        .cred-card {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 0.75rem;
        }
        .cred-info { display: flex; flex-direction: column; gap: 0.25rem; }
        .cred-name { font-weight: 800; font-size: 1rem; }
        .cred-id-code {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--secondary);
            font-family: 'Courier New', monospace;
        }
        .cred-pwd {
            background: color-mix(in srgb, var(--primary) 12%, transparent);
            border: 1px solid color-mix(in srgb, var(--primary) 25%, transparent);
            padding: 0.35rem 0.9rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color: color-mix(in srgb, var(--primary) 70%, #ffffff);
        }
        .cred-role-badge {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.2rem 0.65rem;
            border-radius: 99px;
            background: rgba(6,182,212,0.12);
            border: 1px solid rgba(6,182,212,0.25);
            color: var(--secondary);
        }

        /* ---- Alert/notice box ---- */
        .notice {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            border: 1px solid;
        }
        .notice.success { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.25); color: #6ee7b7; }
        .notice.danger  { background: rgba(244,63,94,0.08);  border-color: rgba(244,63,94,0.25);  color: #fda4af; }
        .notice.warning { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.25); color: #fcd34d; }
        .notice.info    { background: color-mix(in srgb, var(--primary) 8%, transparent); border-color: color-mix(in srgb, var(--primary) 25%, transparent); color: color-mix(in srgb, var(--primary) 70%, #ffffff); }

        /* ---- Grid layout for course cards ---- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .course-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 1.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.4);
            border-color: color-mix(in srgb, var(--primary) 30%, transparent);
        }
        .course-card-tag {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--secondary);
            margin-bottom: 0.75rem;
            display: block;
        }
        .course-card-name {
            font-size: 1.25rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 0.3rem;
        }
        .course-card-teacher {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--tertiary);
        }

        /* ---- Section chip ---- */
        .section-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.9rem;
            background: color-mix(in srgb, var(--primary) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--primary) 25%, transparent);
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 700;
            color: color-mix(in srgb, var(--primary) 70%, #ffffff);
        }

        /* ---- GPA pill ---- */
        .gpa-pill {
            background: color-mix(in srgb, var(--secondary) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--secondary) 25%, transparent);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            text-align: center;
        }
        .gpa-value {
            font-size: 2.4rem;
            font-weight: 900;
            color: var(--secondary);
            line-height: 1;
        }
        .gpa-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--theme-text-muted, #94a3b8);
            margin-top: 0.3rem;
        }

        /* ---- Form grid utilities ---- */
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; align-items: end; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; align-items: end; }
        .form-grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 0.75rem; align-items: end; }
        @media (max-width: 768px) {
            .form-grid-2, .form-grid-3, .form-grid-4 { grid-template-columns: 1fr; }
            .dash-header { flex-direction: column; gap: 1rem; }
        }

        /* ---- Chat ---- */
        .chat-log {
            height: 340px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            padding: 1rem;
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
        }
        .chat-bubble {
            max-width: 78%;
            padding: 0.85rem 1.1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.4;
        }
        .chat-bubble.self {
            align-self: flex-end;
            background: color-mix(in srgb, var(--primary) 18%, transparent);
            border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
        }
        .chat-bubble.other {
            align-self: flex-start;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--glass-border);
        }
        .chat-sender {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.3rem;
            opacity: 0.7;
        }
        .chat-time {
            font-size: 0.65rem;
            opacity: 0.5;
            text-align: right;
            margin-top: 0.3rem;
        }

        /* ---- Promo bars for evaluation ---- */
        .eval-pass { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); border-radius: 10px; padding: 0.5rem 1rem; color: #6ee7b7; font-weight: 700; }
        .eval-fail { background: rgba(244,63,94,0.1);  border: 1px solid rgba(244,63,94,0.3);  border-radius: 10px; padding: 0.5rem 1rem; color: #fda4af; font-weight: 700; }

        /* ---- Section modal enhancements ---- */
        .brutal-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(6px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .brutal-modal-content {
            background: var(--theme-card, #101626);
            border: 1px solid var(--theme-border-color, rgba(255,255,255,0.08));
            box-shadow: 0 24px 64px rgba(0,0,0,0.7), 0 0 40px color-mix(in srgb, var(--primary) 15%, transparent);
            border-radius: 20px;
            padding: 2.25rem;
            width: 100%;
            max-width: 900px;
            max-height: 88vh;
            overflow-y: auto;
            position: relative;
        }
        .close-modal {
            position: absolute;
            top: 1.25rem; right: 1.25rem;
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--theme-text, #f8fafc);
            cursor: pointer;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .close-modal:hover {
            background: var(--danger);
            border-color: var(--danger);
        }

        /* ---- Download button special ---- */
        .btn-download {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff !important;
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1.4rem;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16,185,129,0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16,185,129,0.45);
        }
    </style>
</head>
<body>
    <!-- Ambient orbs -->
    <div class="deco-orb deco-orb-1"></div>
    <div class="deco-orb deco-orb-2"></div>
    <div class="deco-orb deco-orb-3"></div>

    <div class="container">
        <!-- Sticky top navigation -->
        <header class="dash-header">
            <div class="dash-brand">✦ <?php echo htmlspecialchars($brandName); ?></div>
            <div class="dash-user-area">
                <div class="dash-user-info">
                    <span id="userNameDisplay" class="dash-username">GUEST</span>
                    <span id="roleBadge" class="dash-role-pill">ROLE</span>
                </div>
                <a href="#" onclick="logout()" class="dash-logout">⏻ Logout</a>
            </div>
        </header>

        <!-- Main dynamic portal -->
        <main id="dashboardContent"></main>
    </div>

    <!-- Global Modal -->
    <div id="brutalModal" class="brutal-modal">
        <div class="brutal-modal-content">
            <button class="close-modal" onclick="closeModal()">&times;</button>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
    /* =====================================================
       AURORA DASHBOARD — Master JavaScript Controller
       ===================================================== */

    document.addEventListener('DOMContentLoaded', () => {
        const token = localStorage.getItem('token');
        const role  = localStorage.getItem('user_role');
        const name  = localStorage.getItem('user_name');

        if (!token || !role) { window.location.href = 'login.php'; return; }

        document.getElementById('userNameDisplay').textContent = name || 'USER';
        document.getElementById('roleBadge').textContent       = role.toUpperCase();

        loadRolePortal(role);
    });

    /* ---- Shared API helper ---- */
    async function apiRequest(endpoint, method = 'GET', body = null) {
        const token = localStorage.getItem('token');
        const opts  = {
            method,
            headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }
        };
        if (body) opts.body = JSON.stringify(body);
        try {
            const res = await fetch(`/api/${endpoint}`, opts);
            if (res.status === 401) { logout(); return null; }
            return await res.json();
        } catch (err) {
            console.error('API error:', err);
            return { success: false, message: 'Network error. Check connection.' };
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
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    function showNotice(containerId, message, type = 'info') {
        const el = document.getElementById(containerId);
        if (!el) return;
        el.innerHTML = `<div class="notice ${type}">${message}</div>`;
    }

    /* =====================================================
       MASTER PORTAL ROUTER
       ===================================================== */
    async function loadRolePortal(role) {
        const ca = document.getElementById('dashboardContent');

        if (role === 'student') {
            ca.innerHTML = `
                <div class="brutal-tabs">
                    <button class="tab-btn active" onclick="switchTab('tab-student-courses')">My Courses</button>
                    <button class="tab-btn" onclick="switchTab('tab-student-report')">Year-End Report</button>
                    <button class="tab-btn" onclick="switchTab('tab-student-chat')">Messenger</button>
                </div>

                <div id="tab-student-courses" class="tab-panel active">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:2.5rem;">
                        <div>
                            <h1 class="page-title">My Academic Ledger</h1>
                            <span id="studentSectionName" class="section-chip">Loading section...</span>
                        </div>
                        <div class="gpa-pill">
                            <div id="studentOverallGpa" class="gpa-value">—</div>
                            <div class="gpa-label">Overall Average</div>
                        </div>
                    </div>
                    <div id="studentCoursesList" class="dashboard-grid">
                        <div class="glass-card"><p style="color:var(--theme-text-muted)">Loading courses...</p></div>
                    </div>
                </div>

                <div id="tab-student-report" class="tab-panel">
                    <h1 class="page-title">Year-End Report Card</h1>
                    <div id="studentFinalEvaluationBlock" class="glass-card">
                        <p style="color:var(--theme-text-muted)">Checking evaluation status...</p>
                    </div>
                </div>

                <div id="tab-student-chat" class="tab-panel">
                    <h1 class="page-title">Messenger</h1>
                    <div id="studentChatPanel" class="glass-card">
                        <p style="color:var(--theme-text-muted)">Loading contacts...</p>
                    </div>
                </div>
            `;
            await loadStudentCoursesData();
            await loadStudentFinalEvaluationData();
            await loadMessenger('student');

        } else if (role === 'teacher') {
            ca.innerHTML = `
                <div class="brutal-tabs">
                    <button class="tab-btn active" onclick="switchTab('tab-teacher-courses')">My Classes</button>
                    <button class="tab-btn" onclick="switchTab('tab-teacher-homeroom')">Homeroom</button>
                    <button class="tab-btn" onclick="switchTab('tab-teacher-chat')">Messenger</button>
                </div>

                <div id="tab-teacher-courses" class="tab-panel active">
                    <h1 class="page-title">Faculty Control Center</h1>
                    <div class="dashboard-grid" id="teacherAssignmentsGrid">
                        <div class="glass-card"><p style="color:var(--theme-text-muted)">Loading classes...</p></div>
                    </div>
                </div>

                <div id="tab-teacher-homeroom" class="tab-panel">
                    <h1 class="page-title">Homeroom Management</h1>
                    <div class="glass-card" id="teacherHomeroomConfig">
                        <p style="color:var(--theme-text-muted)">Verifying homeroom...</p>
                    </div>
                </div>

                <div id="tab-teacher-chat" class="tab-panel">
                    <h1 class="page-title">Parent Messenger</h1>
                    <div class="glass-card" id="teacherChatPanel">
                        <p style="color:var(--theme-text-muted)">Loading messages...</p>
                    </div>
                </div>
            `;
            await loadTeacherPortalData();
            await loadMessenger('teacher');

        } else if (role === 'parent') {
            ca.innerHTML = `
                <div class="brutal-tabs">
                    <button class="tab-btn active" onclick="switchTab('tab-parent-students')">My Children</button>
                    <button class="tab-btn" onclick="switchTab('tab-parent-chat')">Teacher Messenger</button>
                </div>

                <div id="tab-parent-students" class="tab-panel active">
                    <h1 class="page-title">Parent Oversight Hub</h1>
                    <div class="dashboard-grid" id="parentStudentsGrid">
                        <div class="glass-card"><p style="color:var(--theme-text-muted)">Loading children...</p></div>
                    </div>
                </div>

                <div id="tab-parent-chat" class="tab-panel">
                    <h1 class="page-title">Teacher Messenger</h1>
                    <div class="glass-card" id="parentChatPanel">
                        <p style="color:var(--theme-text-muted)">Loading chats...</p>
                    </div>
                </div>
            `;
            await loadParentPortalData();
            await loadMessenger('parent');

        } else if (role === 'director') {
            ca.innerHTML = `
                <div class="brutal-tabs">
                    <button class="tab-btn active" onclick="switchTab('tab-director-overview')">Overview</button>
                    <button class="tab-btn" onclick="switchTab('tab-director-register')">Register Users</button>
                    <button class="tab-btn" onclick="switchTab('tab-director-sections')">Sections</button>
                    <button class="tab-btn" onclick="switchTab('tab-director-assignments')">Faculty Schedule</button>
                    <button class="tab-btn" onclick="switchTab('tab-director-subjects')">Curriculum</button>
                    <button class="tab-btn" onclick="switchTab('tab-director-sectioning')">Student Roster</button>
                    <button class="tab-btn" onclick="switchTab('tab-director-parents')">Parents</button>
                    <button class="tab-btn" onclick="switchTab('tab-director-config')">System Config</button>
                </div>

                <!-- Overview -->
                <div id="tab-director-overview" class="tab-panel active">
                    <h1 class="page-title">Global Control Panel</h1>
                    <p class="page-subtitle">Real-time school statistics and system health</p>
                    <div class="stat-grid" id="directorStatsGrid">
                        <div class="glass-card"><p style="color:var(--theme-text-muted)">Loading stats...</p></div>
                    </div>
                </div>

                <!-- Register Users -->
                <div id="tab-director-register" class="tab-panel">
                    <h1 class="page-title">User Registration</h1>
                    <p class="page-subtitle">Create single users or import in bulk via CSV</p>

                    <!-- Single User Registration -->
                    <div class="glass-card" style="margin-bottom:2rem;">
                        <h3 class="card-title">⊕ Add Single User</h3>
                        <form id="singleRegForm" onsubmit="handleSingleRegistration(event)">
                            <div class="form-grid-4" style="margin-bottom:1rem;">
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">Full Name</label>
                                    <input type="text" id="singleName" class="brutal-input" style="margin-bottom:0;" placeholder="e.g. Abebe Girma" required>
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">Email</label>
                                    <input type="email" id="singleEmail" class="brutal-input" style="margin-bottom:0;" placeholder="user@school.com" required>
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">Role</label>
                                    <select id="singleRole" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                        <option value="student">Student</option>
                                        <option value="teacher">Teacher</option>
                                    </select>
                                </div>
                                <button type="submit" class="brutal-btn" id="singleRegBtn" style="height:fit-content;">Create User</button>
                            </div>
                        </form>
                        <div id="singleRegResult" style="margin-top:1.25rem;"></div>
                    </div>

                    <!-- Mass CSV Registration -->
                    <div class="glass-card">
                        <h3 class="card-title">⊕ CSV Mass Import</h3>
                        <p style="font-size:0.88rem;font-weight:600;color:var(--theme-text-muted);margin-bottom:1.5rem;">
                            Upload a CSV with columns: <code style="background:rgba(99,102,241,0.1);padding:.15rem .5rem;border-radius:5px;font-size:.82rem;">Full Name, Email</code>
                        </p>
                        <form id="massRegForm" onsubmit="handleMassRegistration(event)">
                            <div class="form-grid-3" style="margin-bottom:1rem;">
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">Import Role</label>
                                    <select id="importRole" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                        <option value="student">Students</option>
                                        <option value="teacher">Teachers</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">CSV File</label>
                                    <input type="file" id="csvFile" accept=".csv" class="brutal-input" style="margin-bottom:0;" required>
                                </div>
                                <div style="align-self:flex-end;">
                                    <button type="submit" class="brutal-btn" id="uploadBtn" style="width:100%;">Process CSV Import</button>
                                </div>
                            </div>
                        </form>

                        <!-- Import Results Panel -->
                        <div id="importResults" style="display:none;margin-top:2rem;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:1px solid var(--glass-border);">
                                <h4 style="margin:0;font-weight:800;font-size:1.05rem;text-transform:uppercase;letter-spacing:.04em;">Import Results</h4>
                                <button class="btn-download" id="downloadImportCsvBtn">⬇ Download Credentials CSV</button>
                            </div>
                            <div id="importResultsCards"></div>
                        </div>
                    </div>
                </div>

                <!-- Sections Management -->
                <div id="tab-director-sections" class="tab-panel">
                    <h1 class="page-title">Section Management</h1>
                    <p class="page-subtitle">Create and manage grade-level classroom sections</p>
                    <div class="glass-card">
                        <h3 class="card-title">⊕ Create New Section</h3>
                        <form id="createSectionForm" onsubmit="handleCreateSection(event)">
                            <div class="form-grid-3" style="margin-bottom:1rem;">
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">Section Name</label>
                                    <input type="text" id="newSectionName" class="brutal-input" style="margin-bottom:0;" placeholder="e.g. Emerald, Alpha, 1A" required>
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">Grade Level</label>
                                    <select id="newSectionGrade" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                        <option value="">— Select Grade —</option>
                                        <option value="9">Grade 9</option>
                                        <option value="10">Grade 10</option>
                                        <option value="11">Grade 11</option>
                                        <option value="12">Grade 12</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.4rem;">Stream</label>
                                    <select id="newSectionStream" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                        <option value="general">General</option>
                                        <option value="natural_science">Natural Science</option>
                                        <option value="social_science">Social Science</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="brutal-btn success" id="createSectionBtn">Create Section</button>
                        </form>
                        <div id="sectionCreateNotice" style="margin-top:1rem;"></div>
                    </div>

                    <div class="glass-card">
                        <h3 class="card-title">Active Sections Directory</h3>
                        <div id="sectionsList">
                            <p style="color:var(--theme-text-muted)">Loading sections...</p>
                        </div>
                    </div>
                </div>

                <!-- Faculty Scheduling -->
                <div id="tab-director-assignments" class="tab-panel">
                    <h1 class="page-title">Faculty Schedule</h1>
                    <div id="directorAssignmentsConfig">
                        <p style="color:var(--theme-text-muted)">Loading roster fields...</p>
                    </div>
                </div>

                <!-- Curriculum Subjects -->
                <div id="tab-director-subjects" class="tab-panel">
                    <h1 class="page-title">Curriculum Subjects</h1>
                    <div id="directorSubjectsConfig">
                        <p style="color:var(--theme-text-muted)">Loading subjects...</p>
                    </div>
                </div>

                <!-- Student Sectioning -->
                <div id="tab-director-sectioning" class="tab-panel">
                    <h1 class="page-title">Student Roster Assignment</h1>
                    <div id="directorSectioningConfig">
                        <p style="color:var(--theme-text-muted)">Loading student directory...</p>
                    </div>
                </div>

                <!-- Parents -->
                <div id="tab-director-parents" class="tab-panel">
                    <h1 class="page-title">Parent–Student Links</h1>
                    <div id="directorParentsConfig">
                        <p style="color:var(--theme-text-muted)">Loading parent directory...</p>
                    </div>
                </div>

                <!-- System Config -->
                <div id="tab-director-config" class="tab-panel">
                    <h1 class="page-title">System Configuration</h1>
                    <div id="directorConfigBlock">
                        <p style="color:var(--theme-text-muted)">Loading settings...</p>
                    </div>
                </div>
            `;

            await loadDirectorOverviewData();
            await loadDirectorAssignmentsData();
            await loadDirectorSubjectsData();
            await loadDirectorSectioningData();
            await loadDirectorParentsData();
            await loadDirectorConfigData();
            await loadSectionsList();
        }
    }

    /* =====================================================
       STUDENT PORTAL
       ===================================================== */
    async function loadStudentCoursesData() {
        const res = await apiRequest('student/courses');
        if (!res || !res.success) return;

        const sectionEl = document.getElementById('studentSectionName');
        if (sectionEl) sectionEl.textContent = res.section_name || '⚠ Unassigned';

        const gpaEl = document.getElementById('studentOverallGpa');
        if (gpaEl) gpaEl.textContent = res.overall_average + '%';

        const container = document.getElementById('studentCoursesList');
        if (!container) return;

        if (!res.courses || res.courses.length === 0) {
            container.innerHTML = `<div class="glass-card" style="grid-column:1/-1;text-align:center;">
                <p style="color:var(--theme-text-muted);font-size:1rem;">No subjects scheduled for your section yet.</p></div>`;
            return;
        }

        container.innerHTML = res.courses.map(course => `
            <div class="course-card" onclick="viewStudentGrades(${course.subject_id}, '${course.subject_name}', '${course.teacher_name}')">
                <span class="course-card-tag">Subject</span>
                <div class="course-card-name">${course.subject_name}</div>
                <div class="course-card-teacher">👤 ${course.teacher_name || 'TBD'}</div>
                <div style="margin-top:1.25rem;text-align:right;">
                    <span class="section-chip" style="font-size:.72rem;">View Scorecard →</span>
                </div>
            </div>
        `).join('');
    }

    async function viewStudentGrades(subjectId, subjectName, teacherName) {
        const res = await apiRequest(`student/course-grades?subject_id=${subjectId}`);
        if (!res || !res.success) return;

        showModal(`
            <h2 style="font-size:1.65rem;font-weight:900;text-transform:uppercase;margin-top:0;">${subjectName} Scorecard</h2>
            <p style="color:var(--tertiary);font-size:.9rem;font-weight:600;margin-bottom:1.5rem;">Teacher: ${teacherName}</p>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Assessment</th><th>Type</th><th>Weight</th><th>Score / Max</th></tr></thead>
                    <tbody>
                        ${res.grades.map(g => `
                            <tr>
                                <td>${g.title}</td>
                                <td>${g.type_name}</td>
                                <td>${(g.weight * 100)}%</td>
                                <td>${g.score !== null
                                    ? `<span class="section-chip" style="background:rgba(16,185,129,.12);border-color:rgba(16,185,129,.25);color:#6ee7b7;">${g.score} / ${g.max_score}</span>`
                                    : `<span class="section-chip" style="background:rgba(244,63,94,.12);border-color:rgba(244,63,94,.25);color:#fda4af;">Ungraded</span>`
                                }</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);border-radius:12px;padding:1rem 1.5rem;margin-top:1rem;">
                <span style="font-weight:700;font-size:.9rem;text-transform:uppercase;letter-spacing:.05em;">Weighted Course Average</span>
                <span style="font-weight:900;font-size:2rem;color:var(--secondary);">${res.weighted_average}%</span>
            </div>
        `);
    }

    async function loadStudentFinalEvaluationData() {
        const res = await apiRequest('student/final-evaluation');
        if (!res || !res.success) return;

        const block = document.getElementById('studentFinalEvaluationBlock');
        if (!block) return;

        if (!res.is_active) {
            block.innerHTML = `<div class="notice danger">Year-end evaluations are currently LOCKED by the Director. Results compilation is in progress.</div>`;
            return;
        }
        if (res.status === 'pending') {
            block.innerHTML = `<div class="notice warning">Year-end evaluations are open, but your homeroom teacher is still compiling class averages. Check back soon.</div>`;
            return;
        }

        const ev = res.evaluation;
        block.innerHTML = `
            <div class="stat-grid" style="margin-bottom:1.5rem;">
                <div class="stat-card"><div class="stat-label">Year Average</div><div class="stat-value">${ev.average_score}%</div></div>
                <div class="stat-card"><div class="stat-label">Class Rank</div><div class="stat-value">#${ev.class_rank}</div></div>
                <div class="stat-card"><div class="stat-label">Promotion</div><div class="stat-value" style="font-size:1.8rem;text-transform:uppercase;color:${ev.status === 'pass' ? 'var(--success)' : 'var(--danger)'};">${ev.status}</div></div>
            </div>
            <div class="notice info">Signed by: <strong>${ev.evaluator_name || 'System Auto'}</strong> on ${new Date(ev.evaluated_at).toLocaleDateString()}</div>
        `;
    }

    /* =====================================================
       TEACHER PORTAL
       ===================================================== */
    async function loadTeacherPortalData() {
        const res = await apiRequest('teacher/classes');
        if (!res || !res.success) return;

        const grid = document.getElementById('teacherAssignmentsGrid');
        if (grid) {
            if (!res.classes || res.classes.length === 0) {
                grid.innerHTML = `<div class="glass-card" style="grid-column:1/-1;text-align:center;"><p style="color:var(--theme-text-muted)">No classes assigned yet. The director will schedule you soon.</p></div>`;
            } else {
                grid.innerHTML = res.classes.map(c => `
                    <div class="course-card">
                        <span class="course-card-tag">Grade ${c.grade_level} ${c.stream}</span>
                        <div class="course-card-name">${c.subject_name}</div>
                        <div class="course-card-teacher">📋 Section ${c.section_name}</div>
                        <div style="display:flex;gap:.6rem;flex-direction:column;margin-top:1.25rem;">
                            <button class="brutal-btn" style="font-size:.8rem;padding:.6rem 1rem;" onclick="viewClassAssessments(${c.assignment_id}, ${c.section_id}, '${c.subject_name} — ${c.section_name}')">Assessments →</button>
                            <button class="brutal-btn" style="font-size:.8rem;padding:.6rem 1rem;background:var(--grad-secondary);" onclick="viewClassRoster(${c.section_id}, '${c.section_name}')">View Roster</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        const homeroomDiv = document.getElementById('teacherHomeroomConfig');
        if (homeroomDiv) {
            if (!res.homeroom_class) {
                homeroomDiv.innerHTML = `<div class="notice warning">You are not currently designated as a Homeroom Teacher for any class.</div>`;
            } else {
                const sect = res.homeroom_class;
                homeroomDiv.innerHTML = `
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
                        <div>
                            <h3 style="font-size:1.5rem;font-weight:800;margin:0;text-transform:uppercase;">Class ${sect.section_name} — Grade ${sect.grade_level}</h3>
                            <span class="section-chip" style="background:rgba(16,185,129,.12);border-color:rgba(16,185,129,.25);color:#6ee7b7;margin-top:.5rem;">Homeroom Active</span>
                        </div>
                        <button class="brutal-btn warning" onclick="loadHomeroomEvaluationsTable(${sect.section_id})">Compute Class Rankings →</button>
                    </div>
                    <div id="homeroomEvaluationsRoster"><p style="color:var(--theme-text-muted)">Select option to load evaluation sheet.</p></div>
                `;
            }
        }
    }

    async function viewClassRoster(sectionId, sectionName) {
        const res = await apiRequest(`teacher/class-students?section_id=${sectionId}`);
        if (!res || !res.success) return;
        showModal(`
            <h2 style="font-size:1.6rem;font-weight:900;text-transform:uppercase;margin-top:0;">Roster — Section ${sectionName}</h2>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Student ID</th><th>Full Name</th><th>Email</th></tr></thead>
                    <tbody>${res.students.map(s => `<tr><td>${s.student_id}</td><td>${s.full_name}</td><td>${s.email || 'N/A'}</td></tr>`).join('')}</tbody>
                </table>
            </div>
        `);
    }

    async function viewClassAssessments(assignmentId, sectionId, className) {
        const res = await apiRequest(`teacher/assessments?assignment_id=${assignmentId}`);
        if (!res || !res.success) return;
        const types = res.assessment_types;

        showModal(`
            <h2 style="font-size:1.6rem;font-weight:900;text-transform:uppercase;margin-top:0;">Assessments</h2>
            <p style="color:var(--theme-text-muted);font-size:.9rem;margin-bottom:1.5rem;">${className}</p>
            <div class="glass-card" style="margin-bottom:1.5rem;">
                <h4 class="card-title" style="font-size:1rem;">Add Assessment</h4>
                <form onsubmit="handleCreateAssessment(event, ${assignmentId}, ${sectionId}, '${className.replace(/'/g,"\\'")}')">
                    <div style="display:grid;grid-template-columns:2fr 1fr 1.5fr 1.2fr 1fr;gap:.5rem;align-items:end;">
                        <input type="text" id="newAssTitle" class="brutal-input" style="margin-bottom:0;" placeholder="Title (e.g. Quiz 1)" required>
                        <input type="number" id="newAssMax" class="brutal-input" style="margin-bottom:0;" placeholder="Max Score" min="1" max="1000" required>
                        <select id="newAssType" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                            ${types.map(t => `<option value="${t.id}">${t.name} (${t.weight*100}%)</option>`).join('')}
                        </select>
                        <input type="date" id="newAssDate" class="brutal-input" style="margin-bottom:0;">
                        <button type="submit" class="brutal-btn" style="padding:.8rem 1rem;">Create</button>
                    </div>
                </form>
            </div>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Title</th><th>Type</th><th>Weight</th><th>Max</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                        ${res.assessments.map(a => `
                            <tr>
                                <td>${a.title}</td>
                                <td>${a.type_name}</td>
                                <td>${a.weight * 100}%</td>
                                <td>${a.max_score}</td>
                                <td>${a.date || '—'}</td>
                                <td style="display:flex;gap:.35rem;flex-wrap:wrap;">
                                    <button class="brutal-btn" style="padding:.3rem .7rem;font-size:.72rem;"
                                        onclick="openEditAssessmentForm(${a.id},'${a.title.replace(/'/g,"\\'")}',${a.max_score},${a.type_id},'${a.date||''}',${JSON.stringify(types).replace(/"/g,'&quot;')},${assignmentId},${sectionId},'${className.replace(/'/g,"\\'")}')">Edit</button>
                                    <button class="brutal-btn danger" style="padding:.3rem .7rem;font-size:.72rem;"
                                        onclick="handleDeleteAssessment(${a.id},${assignmentId},${sectionId},'${className.replace(/'/g,"\\'")}')">Del</button>
                                    <button class="brutal-btn success" style="padding:.3rem .7rem;font-size:.72rem;"
                                        onclick="loadGradeEntryForm(${a.id},${sectionId},'${a.title.replace(/'/g,"\\'")}',${a.max_score},${assignmentId},'${className.replace(/'/g,"\\'")}')">Grades</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            ${res.students && res.students.length > 0 ? `
                <h4 style="font-size:1rem;font-weight:800;text-transform:uppercase;margin:1.5rem 0 1rem;">Student Grade Book</h4>
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
                        <tbody>
                            ${res.students.map(s => `
                                <tr>
                                    <td>${s.student_id}</td>
                                    <td>${s.full_name}</td>
                                    <td><button class="brutal-btn" style="padding:.3rem .9rem;font-size:.75rem;"
                                        onclick="openStudentGradesEditor(${s.id},'${s.full_name.replace(/'/g,"\\'")}',${assignmentId},${sectionId},'${className.replace(/'/g,"\\'")}')">Grade / View</button></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            ` : ''}
        `);
    }

    async function handleCreateAssessment(e, assignmentId, sectionId, className) {
        e.preventDefault();
        const res = await apiRequest('teacher/create-assessment', 'POST', {
            assignment_id: assignmentId,
            title: document.getElementById('newAssTitle').value,
            max_score: document.getElementById('newAssMax').value,
            type_id: document.getElementById('newAssType').value,
            date: document.getElementById('newAssDate').value
        });
        if (res && res.success) { alert(res.message); viewClassAssessments(assignmentId, sectionId, className); }
        else alert(res.message || 'Failed to create assessment.');
    }

    async function openEditAssessmentForm(assessmentId, title, maxScore, typeId, date, types, assignmentId, sectionId, className) {
        showModal(`
            <h2 style="font-size:1.5rem;font-weight:900;text-transform:uppercase;margin-top:0;">Edit Assessment</h2>
            <form onsubmit="handleEditAssessment(event,${assessmentId},${assignmentId},${sectionId},'${className}')" style="display:flex;flex-direction:column;gap:1rem;margin-top:1.5rem;">
                <div><label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;">Title</label>
                    <input type="text" id="editAssTitle" class="brutal-input" required value="${title}"></div>
                <div><label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;">Max Score</label>
                    <input type="number" id="editAssMax" class="brutal-input" required value="${maxScore}" min="1" max="1000"></div>
                <div><label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;">Type</label>
                    <select id="editAssType" class="brutal-input" style="height:auto;" required>
                        ${types.map(t => `<option value="${t.id}" ${t.id == typeId ? 'selected' : ''}>${t.name} (${t.weight*100}%)</option>`).join('')}
                    </select></div>
                <div><label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;">Date</label>
                    <input type="date" id="editAssDate" class="brutal-input" value="${date}"></div>
                <div style="display:flex;gap:.5rem;justify-content:flex-end;">
                    <button type="button" class="brutal-btn" style="background:rgba(255,255,255,.05);border:1px solid var(--glass-border);" onclick="viewClassAssessments(${assignmentId},${sectionId},'${className}')">Cancel</button>
                    <button type="submit" class="brutal-btn success">Save Changes</button>
                </div>
            </form>
        `);
    }

    async function handleEditAssessment(e, assessmentId, assignmentId, sectionId, className) {
        e.preventDefault();
        const res = await apiRequest('teacher/update-assessment', 'POST', {
            assessment_id: assessmentId,
            title: document.getElementById('editAssTitle').value,
            max_score: document.getElementById('editAssMax').value,
            type_id: document.getElementById('editAssType').value,
            date: document.getElementById('editAssDate').value
        });
        if (res && res.success) { alert(res.message); viewClassAssessments(assignmentId, sectionId, className); }
        else alert(res.message || 'Failed to update.');
    }

    async function handleDeleteAssessment(assessmentId, assignmentId, sectionId, className) {
        if (!confirm('Delete this assessment and ALL scores? This is permanent.')) return;
        const res = await apiRequest('teacher/delete-assessment', 'POST', { assessment_id: assessmentId });
        if (res && res.success) { alert(res.message); viewClassAssessments(assignmentId, sectionId, className); }
        else alert(res.message || 'Failed to delete.');
    }

    async function openStudentGradesEditor(studentId, studentName, assignmentId, sectionId, className) {
        const res = await apiRequest(`teacher/student-assignment-grades?student_id=${studentId}&assignment_id=${assignmentId}`);
        if (!res || !res.success) return;
        showModal(`
            <h2 style="font-size:1.5rem;font-weight:900;text-transform:uppercase;margin-top:0;">Grade Book</h2>
            <p style="color:var(--tertiary);font-size:.9rem;margin-bottom:1.5rem;">Student: ${studentName}</p>
            <form onsubmit="submitStudentSingleGrades(event,${studentId},${assignmentId},${sectionId},'${className}')">
                <div class="brutal-table-container" style="margin-bottom:1.5rem;">
                    <table class="brutal-table">
                        <thead><tr><th>Assessment</th><th>Category</th><th>Max</th><th>Score</th></tr></thead>
                        <tbody>
                            ${res.grades.map(g => `
                                <tr>
                                    <td><strong>${g.title}</strong></td>
                                    <td>${g.type_name}</td>
                                    <td>${g.max_score}</td>
                                    <td><input type="number" step="0.01" class="brutal-input student-single-score-input"
                                        data-assessment-id="${g.assessment_id}" min="0" max="${g.max_score}"
                                        style="margin-bottom:0;width:110px;" placeholder="Score"
                                        value="${g.score !== null ? g.score : ''}"></td>
                                </tr>`).join('')}
                            ${res.grades.length === 0 ? '<tr><td colspan="4" style="text-align:center;color:var(--theme-text-muted)">No assessments yet. Create some first.</td></tr>' : ''}
                        </tbody>
                    </table>
                </div>
                <div style="display:flex;justify-content:flex-end;gap:.5rem;">
                    <button type="button" class="brutal-btn" style="background:rgba(255,255,255,.05);border:1px solid var(--glass-border);" onclick="viewClassAssessments(${assignmentId},${sectionId},'${className}')">Back</button>
                    <button type="submit" class="brutal-btn success">Save Results</button>
                </div>
            </form>
        `);
    }

    async function submitStudentSingleGrades(e, studentId, assignmentId, sectionId, className) {
        e.preventDefault();
        const scores = [];
        document.querySelectorAll('.student-single-score-input').forEach(inp => {
            scores.push({ assessment_id: inp.getAttribute('data-assessment-id'), score: inp.value });
        });
        const res = await apiRequest('teacher/submit-student-grades', 'POST', { student_id: studentId, scores });
        if (res && res.success) { alert(res.message); viewClassAssessments(assignmentId, sectionId, className); }
        else alert(res.message || 'Failed to save grades.');
    }

    async function loadGradeEntryForm(assessmentId, sectionId, assTitle, maxScore, assignmentId, className) {
        const sr = await apiRequest(`teacher/class-students?section_id=${sectionId}`);
        if (!sr || !sr.success) return;
        showModal(`
            <h2 style="font-size:1.5rem;font-weight:900;text-transform:uppercase;margin-top:0;">Score Entry Sheet</h2>
            <p style="color:var(--tertiary);font-size:.9rem;margin-bottom:1.5rem;">${assTitle} — Max: ${maxScore} pts</p>
            <form onsubmit="submitStudentGrades(event,${assessmentId},${sectionId})">
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead><tr><th>Student ID</th><th>Name</th><th>Score (Max ${maxScore})</th></tr></thead>
                        <tbody>
                            ${sr.students.map(s => `
                                <tr>
                                    <td>${s.student_id}</td>
                                    <td>${s.full_name}</td>
                                    <td><input type="number" step="0.01" class="brutal-input student-score-input"
                                        data-student-id="${s.id}" min="0" max="${maxScore}"
                                        style="margin-bottom:0;width:130px;" placeholder="Score"></td>
                                </tr>`).join('')}
                        </tbody>
                    </table>
                </div>
                <div style="text-align:right;margin-top:1rem;">
                    <button type="submit" class="brutal-btn success">Save All Scores</button>
                </div>
            </form>
        `);
    }

    async function submitStudentGrades(e, assessmentId, sectionId) {
        e.preventDefault();
        const scores = [];
        document.querySelectorAll('.student-score-input').forEach(inp => {
            scores.push({ student_id: inp.getAttribute('data-student-id'), score: inp.value });
        });
        const res = await apiRequest('teacher/submit-grades', 'POST', { assessment_id: assessmentId, scores });
        if (res && res.success) { alert(res.message); closeModal(); }
        else alert(res.message || 'Failed to save grades.');
    }

    async function loadHomeroomEvaluationsTable(sectionId) {
        const res = await apiRequest(`teacher/homeroom-roster?section_id=${sectionId}`);
        if (!res || !res.success) { alert(res.message || 'Unable to load.'); return; }
        const rosterArea = document.getElementById('homeroomEvaluationsRoster');
        if (!res.is_final_active) {
            rosterArea.innerHTML = `<div class="notice danger">Year-end evaluations are currently LOCKED by the Director. Cannot submit yet.</div>`;
            return;
        }
        rosterArea.innerHTML = `
            <div class="notice success" style="margin-bottom:1.5rem;">Weighted averages and class rankings computed automatically. Review and submit below.</div>
            <form onsubmit="submitHomeroomEvaluations(event,${sectionId})">
                <div class="brutal-table-container">
                    <table class="brutal-table">
                        <thead><tr><th>Rank</th><th>Student ID</th><th>Name</th><th>Average</th><th>Decision</th></tr></thead>
                        <tbody>
                            ${res.roster.map(s => `
                                <tr>
                                    <td><strong>#${s.rank}</strong></td>
                                    <td>${s.student_code}</td>
                                    <td>${s.full_name}</td>
                                    <td><span class="section-chip" style="background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.25);color:#fcd34d;">${s.average}%</span>
                                        <input type="hidden" class="eval-student-avg" data-student-id="${s.id}" value="${s.average}">
                                        <input type="hidden" class="eval-student-rank" data-student-id="${s.id}" value="${s.rank}"></td>
                                    <td>
                                        <select class="brutal-input eval-student-status" data-student-id="${s.id}" style="margin-bottom:0;width:160px;height:auto;">
                                            <option value="pass" ${s.average >= 50 ? 'selected' : ''}>PROMOTED (PASS)</option>
                                            <option value="fail" ${s.average < 50 ? 'selected' : ''}>FAILED (FAIL)</option>
                                        </select>
                                    </td>
                                </tr>`).join('')}
                        </tbody>
                    </table>
                </div>
                <div style="text-align:right;margin-top:1rem;">
                    <button type="submit" class="brutal-btn success">Submit Final Compiled Ledger</button>
                </div>
            </form>
        `;
    }

    async function submitHomeroomEvaluations(e, sectionId) {
        e.preventDefault();
        const evaluations = [];
        document.querySelectorAll('.eval-student-status').forEach(sel => {
            const sid = sel.getAttribute('data-student-id');
            evaluations.push({
                student_id: sid,
                average: document.querySelector(`.eval-student-avg[data-student-id="${sid}"]`).value,
                rank: document.querySelector(`.eval-student-rank[data-student-id="${sid}"]`).value,
                status: sel.value
            });
        });
        const res = await apiRequest('teacher/submit-evaluations', 'POST', { section_id: sectionId, evaluations });
        if (res && res.success) { alert(res.message); loadHomeroomEvaluationsTable(sectionId); }
        else alert(res.message || 'Failed to submit.');
    }

    /* =====================================================
       PARENT PORTAL
       ===================================================== */
    async function loadParentPortalData() {
        const res = await apiRequest('parent/students');
        if (!res || !res.success) return;
        const grid = document.getElementById('parentStudentsGrid');
        if (!grid) return;

        if (!res.students || res.students.length === 0) {
            grid.innerHTML = `<div class="glass-card" style="grid-column:1/-1;text-align:center;"><p style="color:var(--theme-text-muted)">No students linked. Contact administration.</p></div>`;
            return;
        }
        grid.innerHTML = res.students.map(s => `
            <div class="course-card">
                <span class="course-card-tag">Child Profile</span>
                <div class="course-card-name">${s.full_name}</div>
                <div class="course-card-teacher">📋 Section: ${s.section_name || 'Unassigned'}</div>
                <div class="gpa-pill" style="margin-top:1.25rem;">
                    <div class="gpa-value" style="font-size:1.8rem;">${s.overall_average}%</div>
                    <div class="gpa-label">Overall Average</div>
                </div>
                <button class="brutal-btn" style="width:100%;margin-top:1rem;font-size:.8rem;" onclick="viewChildAcademicProgress(${s.id},'${s.full_name}')">Track Progress →</button>
            </div>
        `).join('');
    }

    async function viewChildAcademicProgress(studentId, studentName) {
        const res = await apiRequest('student/courses');
        if (!res || !res.success) return;
        showModal(`
            <h2 style="font-size:1.5rem;font-weight:900;text-transform:uppercase;margin-top:0;">Progress Tracker</h2>
            <p style="color:var(--tertiary);font-size:.9rem;margin-bottom:1rem;">Child: ${studentName}</p>
            <div class="notice info" style="margin-bottom:1.5rem;">Cumulative GPA: <strong>${res.overall_average}%</strong></div>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Subject</th><th>Teacher</th><th>Scorecard</th></tr></thead>
                    <tbody>
                        ${res.courses.map(c => `
                            <tr>
                                <td>${c.subject_name}</td>
                                <td>${c.teacher_name || 'TBD'}</td>
                                <td><button class="brutal-btn" style="padding:.3rem .8rem;font-size:.8rem;" onclick="viewStudentGrades(${c.subject_id},'${c.subject_name}','${c.teacher_name}')">View →</button></td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            </div>
        `);
    }

    /* =====================================================
       DIRECTOR PORTAL
       ===================================================== */
    async function loadDirectorOverviewData() {
        const res = await apiRequest('director/stats');
        if (!res || !res.success) return;
        const grid = document.getElementById('directorStatsGrid');
        if (!grid) return;
        const s = res.stats;
        grid.innerHTML = `
            <div class="stat-card"><div class="stat-label">Students Enrolled</div><div class="stat-value">${s.total_students}</div></div>
            <div class="stat-card"><div class="stat-label">Active Faculty</div><div class="stat-value">${s.total_teachers}</div></div>
            <div class="stat-card"><div class="stat-label">Subjects</div><div class="stat-value">${s.total_subjects}</div></div>
            <div class="stat-card"><div class="stat-label">Active Sections</div><div class="stat-value">${s.total_sections}</div></div>
        `;
    }

    /* ---- Single User Registration ---- */
    async function handleSingleRegistration(e) {
        e.preventDefault();
        const btn = document.getElementById('singleRegBtn');
        btn.textContent = 'Creating...'; btn.disabled = true;

        const res = await apiRequest('director/create-user', 'POST', {
            full_name: document.getElementById('singleName').value,
            email:     document.getElementById('singleEmail').value,
            role:      document.getElementById('singleRole').value
        });

        btn.textContent = 'Create User'; btn.disabled = false;

        const resultDiv = document.getElementById('singleRegResult');
        if (!resultDiv) return;

        if (res && res.success) {
            resultDiv.innerHTML = `
                <div class="notice success" style="margin-bottom:.75rem;">✓ User created successfully!</div>
                <div class="cred-card">
                    <div class="cred-info">
                        <span class="cred-name">${res.full_name}</span>
                        <span class="cred-id-code">${res.id_code}</span>
                        <span style="font-size:.8rem;color:var(--theme-text-muted);">${res.email}</span>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.5rem;">
                        <span class="cred-role-badge">${res.role}</span>
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <span style="font-size:.8rem;font-weight:600;color:var(--theme-text-muted);">Password:</span>
                            <span class="cred-pwd">${res.password}</span>
                        </div>
                    </div>
                </div>
                <p style="font-size:.78rem;color:var(--theme-text-muted);margin-top:.5rem;">⚠ Share these credentials securely. The password is only shown once.</p>
            `;
            document.getElementById('singleRegForm').reset();
        } else {
            resultDiv.innerHTML = `<div class="notice danger">✗ ${res ? res.message : 'Registration failed.'}</div>`;
        }
    }

    /* ---- Mass CSV Registration ---- */
    let generatedCredentialsCsv = '';

    async function handleMassRegistration(e) {
        e.preventDefault();
        const fileInput = document.getElementById('csvFile');
        const role = document.getElementById('importRole').value;

        if (!fileInput.files.length) { alert('Please select a CSV file.'); return; }

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('role', role);
        formData.append('school_id', localStorage.getItem('school_id') || 1);

        const uploadBtn = document.getElementById('uploadBtn');
        uploadBtn.textContent = 'Processing...'; uploadBtn.disabled = true;

        try {
            const response = await fetch('/api/users/mass-register', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                body: formData
            });
            const res = await response.json();
            uploadBtn.textContent = 'Process CSV Import'; uploadBtn.disabled = false;

            if (res && res.success) {
                const importResultsPanel = document.getElementById('importResults');
                const cardsContainer    = document.getElementById('importResultsCards');

                importResultsPanel.style.display = 'block';

                if (res.results && res.results.length > 0) {
                    generatedCredentialsCsv = res.csv || '';
                    const skippedMsg = res.skipped > 0
                        ? `<div class="notice warning" style="margin-bottom:1rem;">⚠ ${res.skipped} row(s) were skipped (duplicates or invalid data).</div>`
                        : '';
                    cardsContainer.innerHTML = `
                        <div class="notice success" style="margin-bottom:1rem;">✓ ${res.count} user(s) imported successfully.</div>
                        ${skippedMsg}
                        ${res.results.map(r => `
                            <div class="cred-card">
                                <div class="cred-info">
                                    <span class="cred-name">${r.full_name}</span>
                                    <span class="cred-id-code">${r.id_code}</span>
                                </div>
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.5rem;">
                                    <span class="cred-role-badge">${role}</span>
                                    <div style="display:flex;align-items:center;gap:.5rem;">
                                        <span style="font-size:.8rem;font-weight:600;color:var(--theme-text-muted);">Password:</span>
                                        <span class="cred-pwd">${r.password}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                        <p style="font-size:.78rem;color:var(--theme-text-muted);margin-top:.75rem;">⚠ Download credentials CSV to share securely. Passwords are only shown once.</p>
                    `;
                } else {
                    cardsContainer.innerHTML = `<div class="notice warning">No new users were imported. All rows were skipped.</div>`;
                    generatedCredentialsCsv = '';
                }
            } else {
                alert(res.message || 'Failed to process CSV import.');
            }
        } catch (err) {
            uploadBtn.textContent = 'Process CSV Import'; uploadBtn.disabled = false;
            alert('Network error during file upload.');
            console.error(err);
        }
    }

    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'downloadImportCsvBtn') {
            if (!generatedCredentialsCsv) { alert('No credentials CSV available.'); return; }
            const blob = new Blob([generatedCredentialsCsv], { type: 'text/csv;charset=utf-8;' });
            const url  = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'generated_credentials.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });

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
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Section Name</th><th>Grade Level</th><th>Homeroom Teacher</th><th>Students</th><th>Actions</th></tr></thead>
                    <tbody>
                        ${res.sections.map(sec => `
                            <tr>
                                <td><strong>${sec.section_name}</strong></td>
                                <td><span class="section-chip">Grade ${sec.grade_level}</span></td>
                                <td>${sec.homeroom_teacher_name || '<span style="color:var(--theme-text-muted)">Not assigned</span>'}</td>
                                <td><span class="section-chip" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.25);color:#6ee7b7;">${sec.student_count ?? '—'} students</span></td>
                                <td style="display:flex;gap:.35rem;">
                                    <button class="brutal-btn" style="padding:.3rem .7rem;font-size:.8rem;" onclick="openEditSectionModal(${sec.id}, '${sec.section_name.replace(/'/g, "\\'")}', ${sec.grade_level}, '${sec.stream || 'general'}')">Edit</button>
                                    <button class="brutal-btn danger" style="padding:.3rem .7rem;font-size:.8rem;" onclick="handleDeleteSection(${sec.id})">Delete</button>
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
                    <input type="text" id="editSectionName" class="brutal-input" required value="${name}">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;color:var(--theme-text-muted);">Grade Level</label>
                    <select id="editSectionGrade" class="brutal-input" style="height:auto;" required>
                        <option value="9" ${gradeLevel == 9 ? 'selected' : ''}>Grade 9</option>
                        <option value="10" ${gradeLevel == 10 ? 'selected' : ''}>Grade 10</option>
                        <option value="11" ${gradeLevel == 11 ? 'selected' : ''}>Grade 11</option>
                        <option value="12" ${gradeLevel == 12 ? 'selected' : ''}>Grade 12</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;color:var(--theme-text-muted);">Stream</label>
                    <select id="editSectionStream" class="brutal-input" style="height:auto;" required>
                        <option value="general" ${stream === 'general' ? 'selected' : ''}>General</option>
                        <option value="natural_science" ${stream === 'natural_science' ? 'selected' : ''}>Natural Science</option>
                        <option value="social_science" ${stream === 'social_science' ? 'selected' : ''}>Social Science</option>
                    </select>
                </div>
                <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:1rem;">
                    <button type="button" class="brutal-btn" style="background:rgba(255,255,255,.05);border:1px solid var(--glass-border);" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="brutal-btn success">Save Changes</button>
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

    /* ---- Faculty Assignments ---- */
    async function loadDirectorAssignmentsData() {
        const res = await apiRequest('director/assignment-data');
        const container = document.getElementById('directorAssignmentsConfig');
        if (!res || !res.success || !container) return;

        container.innerHTML = `
            <div class="glass-card" style="margin-bottom:1.5rem;">
                <h3 class="card-title">Assign Teacher to Subject & Section</h3>
                <form onsubmit="handleAssignTeacher(event)">
                    <div class="form-grid-4" style="margin-bottom:1rem;">
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Teacher</label>
                            <select id="assignTeacherSelect" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Select Teacher —</option>
                                ${res.teachers.map(t => `<option value="${t.id}">${t.full_name} (${t.specialization || 'General'})</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Subject</label>
                            <select id="assignSubjectSelect" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Select Subject —</option>
                                ${res.subjects.map(s => `<option value="${s.id}">${s.name} (Grade ${s.grade_level})</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Section</label>
                            <select id="assignSectionSelect" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Select Section —</option>
                                ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} — ${sec.section_name}</option>`).join('')}
                            </select>
                        </div>
                        <button type="submit" class="brutal-btn success" style="align-self:flex-end;">Schedule</button>
                    </div>
                </form>
            </div>

            <div class="glass-card" style="margin-bottom:1.5rem;">
                <h3 class="card-title">Set Homeroom Teacher</h3>
                <form onsubmit="handleAssignHomeroom(event)">
                    <div class="form-grid-3" style="margin-bottom:1rem;">
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Section</label>
                            <select id="homeroomSectionSelect" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Select Section —</option>
                                ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} — ${sec.section_name} (${sec.homeroom_teacher_name ? 'Homeroom: ' + sec.homeroom_teacher_name : 'No homeroom'})</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Teacher</label>
                            <select id="homeroomTeacherSelect" class="brutal-input" style="margin-bottom:0;height:auto;">
                                <option value="">— No Homeroom (Unset) —</option>
                                ${res.teachers.map(t => `<option value="${t.id}">${t.full_name}</option>`).join('')}
                            </select>
                        </div>
                        <button type="submit" class="brutal-btn warning" style="align-self:flex-end;">Set Homeroom</button>
                    </div>
                </form>
            </div>

            <h3 style="font-size:1.15rem;font-weight:800;text-transform:uppercase;margin-bottom:1rem;letter-spacing:.04em;">Current Teaching Assignments</h3>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Teacher</th><th>Subject</th><th>Section</th><th>Action</th></tr></thead>
                    <tbody>
                        ${res.assignments.map(a => `
                            <tr>
                                <td>${a.teacher_name}</td>
                                <td>${a.subject_name}</td>
                                <td>Grade ${a.grade_level} — ${a.section_name}</td>
                                <td><button class="brutal-btn danger" style="padding:.3rem .8rem;font-size:.8rem;" onclick="removeTeacherAssignment(${a.assignment_id})">Remove</button></td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    async function handleAssignTeacher(e) {
        e.preventDefault();
        const res = await apiRequest('director/assign-teacher', 'POST', {
            teacher_id: document.getElementById('assignTeacherSelect').value,
            subject_id: document.getElementById('assignSubjectSelect').value,
            section_id: document.getElementById('assignSectionSelect').value
        });
        if (res && res.success) { alert(res.message); loadDirectorAssignmentsData(); }
        else alert(res.message || 'Failed to schedule assignment.');
    }

    async function handleAssignHomeroom(e) {
        e.preventDefault();
        const res = await apiRequest('director/assign-homeroom', 'POST', {
            section_id: document.getElementById('homeroomSectionSelect').value,
            teacher_id: document.getElementById('homeroomTeacherSelect').value
        });
        if (res && res.success) { alert(res.message); loadDirectorAssignmentsData(); loadSectionsList(); }
        else alert(res.message || 'Failed to set homeroom.');
    }

    async function removeTeacherAssignment(assignmentId) {
        if (!confirm('Remove this teaching assignment?')) return;
        const res = await apiRequest('director/remove-assignment', 'POST', { assignment_id: assignmentId });
        if (res && res.success) { alert(res.message); loadDirectorAssignmentsData(); }
        else alert(res.message || 'Failed to remove.');
    }

    /* ---- Student Sectioning ---- */
    async function loadDirectorSectioningData() {
        const res = await apiRequest('director/student-sectioning-data');
        const container = document.getElementById('directorSectioningConfig');
        if (!res || !res.success || !container) return;

        container.innerHTML = `
            <div class="glass-card" style="margin-bottom:1.5rem;">
                <h3 class="card-title">Auto-Sectioning (Random Assignment)</h3>
                <p style="font-size:.88rem;font-weight:600;color:var(--theme-text-muted);margin-bottom:1.25rem;">Randomly distributes all unsectioned students evenly across sections for the selected grade level.</p>
                <form onsubmit="handleRandomSectioning(event)">
                    <div class="form-grid-2" style="margin-bottom:0;">
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Grade Level</label>
                            <select id="randomSectioningGrade" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Choose Grade Level —</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>
                        <button type="submit" class="brutal-btn warning" style="align-self:flex-end;">Execute Random Assignment</button>
                    </div>
                </form>
            </div>

            <div class="glass-card" style="margin-bottom:1.5rem;">
                <h3 class="card-title">Manual Student Transfer</h3>
                <form onsubmit="handleAssignStudentSection(event)">
                    <div class="form-grid-3" style="margin-bottom:0;">
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Student</label>
                            <select id="assignStudentSelect" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Choose Student —</option>
                                ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_code}) [${s.section_name ? 'Section: ' + s.section_name : 'Unassigned'}]</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Target Section</label>
                            <select id="studentSectionSelect" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Unassign / Remove —</option>
                                ${res.sections.map(sec => `<option value="${sec.id}">Grade ${sec.grade_level} — ${sec.section_name}</option>`).join('')}
                            </select>
                        </div>
                        <button type="submit" class="brutal-btn success" style="align-self:flex-end;">Save Transfer</button>
                    </div>
                </form>
            </div>

            <h3 style="font-size:1.15rem;font-weight:800;text-transform:uppercase;margin-bottom:1rem;letter-spacing:.04em;">Student Directory</h3>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Student ID</th><th>Name</th><th>Email</th><th>Section</th></tr></thead>
                    <tbody>
                        ${res.students.map(s => `
                            <tr>
                                <td>${s.student_code}</td>
                                <td>${s.full_name}</td>
                                <td>${s.email || 'N/A'}</td>
                                <td>${s.section_name
                                    ? `<span class="section-chip" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.25);color:#6ee7b7;">Grade ${s.grade_level} — ${s.section_name}</span>`
                                    : `<span class="section-chip" style="background:rgba(244,63,94,.1);border-color:rgba(244,63,94,.25);color:#fda4af;">Unassigned</span>`
                                }</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    async function handleRandomSectioning(e) {
        e.preventDefault();
        const grade_level = document.getElementById('randomSectioningGrade').value;
        const res = await apiRequest('director/random-sectioning', 'POST', { grade_level });
        if (res && res.success) { alert(res.message); loadDirectorSectioningData(); }
        else alert(res.message || 'Failed to distribute.');
    }

    async function handleAssignStudentSection(e) {
        e.preventDefault();
        const res = await apiRequest('director/assign-student-section', 'POST', {
            student_id: document.getElementById('assignStudentSelect').value,
            section_id: document.getElementById('studentSectionSelect').value
        });
        if (res && res.success) { alert(res.message); loadDirectorSectioningData(); }
        else alert(res.message || 'Failed to update section.');
    }

    /* ---- Parents ---- */
    async function loadDirectorParentsData() {
        const res = await apiRequest('director/parents-list');
        const container = document.getElementById('directorParentsConfig');
        if (!res || !res.success || !container) return;

        container.innerHTML = `
            <div class="glass-card" style="margin-bottom:1.5rem;">
                <h3 class="card-title">Link Parent to Student</h3>
                <form onsubmit="handleLinkParent(event)">
                    <div class="form-grid-4" style="margin-bottom:1rem;">
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Parent Name</label>
                            <input type="text" id="parentName" class="brutal-input" style="margin-bottom:0;" placeholder="Full Name" required>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Email</label>
                            <input type="email" id="parentEmail" class="brutal-input" style="margin-bottom:0;" placeholder="parent@email.com" required>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Phone</label>
                            <input type="text" id="parentPhone" class="brutal-input" style="margin-bottom:0;" placeholder="+251..." required>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Child</label>
                            <select id="parentStudentSelect" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Select Child —</option>
                                ${res.students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_id})</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="brutal-btn success">Link & Save Parent</button>
                </form>
            </div>
            <h3 style="font-size:1.15rem;font-weight:800;text-transform:uppercase;margin-bottom:1rem;letter-spacing:.04em;">Registered Parents</h3>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Parent Name</th><th>Email</th><th>Phone</th><th>Status</th></tr></thead>
                    <tbody>
                        ${res.parents.map(p => `
                            <tr>
                                <td>${p.full_name}</td>
                                <td>${p.email}</td>
                                <td>${p.phone || 'N/A'}</td>
                                <td><span class="section-chip" style="background:rgba(6,182,212,.1);border-color:rgba(6,182,212,.25);color:var(--secondary);">Connected</span></td>
                            </tr>`).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    async function handleLinkParent(e) {
        e.preventDefault();
        const res = await apiRequest('director/create-parent', 'POST', {
            full_name:     document.getElementById('parentName').value,
            email:         document.getElementById('parentEmail').value,
            phone:         document.getElementById('parentPhone').value,
            student_id:    document.getElementById('parentStudentSelect').value,
            relation_type: 'Father'
        });
        if (res && res.success) { alert(res.message); loadDirectorParentsData(); }
        else alert(res.message || 'Failed to link parent.');
    }

    /* ---- Curriculum Subjects ---- */
    async function loadDirectorSubjectsData() {
        const res = await apiRequest('director/subjects');
        const container = document.getElementById('directorSubjectsConfig');
        if (!res || !res.success || !container) return;

        container.innerHTML = `
            <div class="glass-card" style="margin-bottom:1.5rem;">
                <h3 class="card-title">Add New Subject</h3>
                <form onsubmit="handleCreateSubject(event)">
                    <div class="form-grid-3" style="margin-bottom:0;">
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Subject Name</label>
                            <input type="text" id="newSubjectName" class="brutal-input" style="margin-bottom:0;" placeholder="e.g. Chemistry" required>
                        </div>
                        <div>
                            <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Grade Level</label>
                            <select id="newSubjectGrade" class="brutal-input" style="margin-bottom:0;height:auto;" required>
                                <option value="">— Select Grade —</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>
                        <button type="submit" class="brutal-btn success" style="align-self:flex-end;">Add Subject</button>
                    </div>
                </form>
            </div>
            <div class="brutal-table-container">
                <table class="brutal-table">
                    <thead><tr><th>Subject Name</th><th>Grade</th><th>Actions</th></tr></thead>
                    <tbody>
                        ${res.subjects.length === 0
                            ? '<tr><td colspan="3" style="text-align:center;color:var(--theme-text-muted)">No subjects added yet.</td></tr>'
                            : res.subjects.map(s => `
                                <tr>
                                    <td><strong>${s.name}</strong></td>
                                    <td><span class="section-chip">Grade ${s.grade_level}</span></td>
                                    <td style="display:flex;gap:.35rem;">
                                        <button class="brutal-btn" style="padding:.3rem .7rem;font-size:.8rem;" onclick="handleEditSubject(${s.id},'${s.name.replace(/'/g,"\\'")}',${s.grade_level})">Edit</button>
                                        <button class="brutal-btn danger" style="padding:.3rem .7rem;font-size:.8rem;" onclick="handleDeleteSubject(${s.id})">Delete</button>
                                    </td>
                                </tr>`).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    async function handleCreateSubject(e) {
        e.preventDefault();
        const res = await apiRequest('director/subjects', 'POST', {
            name:        document.getElementById('newSubjectName').value,
            grade_level: document.getElementById('newSubjectGrade').value
        });
        if (res && res.success) { alert(res.message); loadDirectorSubjectsData(); loadDirectorAssignmentsData(); }
        else alert(res.message || 'Failed to create subject.');
    }

    async function handleEditSubject(id, currentName, currentGrade) {
        const newName = prompt('Enter new subject name:', currentName);
        if (newName === null) return;
        const newGrade = prompt('Enter new grade level (9, 10, 11, 12):', currentGrade);
        if (newGrade === null) return;
        const res = await apiRequest('director/subjects', 'PUT', { subject_id: id, name: newName, grade_level: newGrade });
        if (res && res.success) { alert(res.message); loadDirectorSubjectsData(); loadDirectorAssignmentsData(); }
        else alert(res.message || 'Failed to update.');
    }

    async function handleDeleteSubject(id) {
        if (!confirm('Delete this subject? All assignments, assessments, and grades will be lost!')) return;
        const res = await apiRequest('director/subjects', 'DELETE', { subject_id: id });
        if (res && res.success) { alert(res.message); loadDirectorSubjectsData(); loadDirectorAssignmentsData(); }
        else alert(res.message || 'Failed to delete.');
    }

    /* ---- System Config ---- */
    async function loadDirectorConfigData() {
        const statsRes = await apiRequest('director/stats');
        if (!statsRes || !statsRes.success) return;
        const isFinalActive = statsRes.stats.is_final_active;
        const container = document.getElementById('directorConfigBlock');
        if (!container) return;

        const termsRes = await apiRequest('director/terms');
        let termsHtml = `<p style="color:var(--theme-text-muted)">No active terms found.</p>`;
        if (termsRes && termsRes.success && termsRes.terms && termsRes.terms.length > 0) {
            termsHtml = `
                <div class="brutal-table-container" style="margin-top:1rem;">
                    <table class="brutal-table">
                        <thead><tr><th>Term</th><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            ${termsRes.terms.map(t => `
                                <tr>
                                    <td><strong>${t.term_name}</strong></td>
                                    <td>${t.start_date || '—'}</td>
                                    <td>${t.end_date || '—'}</td>
                                    <td>${t.is_active
                                        ? `<span class="section-chip" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.25);color:#6ee7b7;">Active</span>`
                                        : `<span class="section-chip" style="background:rgba(244,63,94,.1);border-color:rgba(244,63,94,.25);color:#fda4af;">Inactive</span>`
                                    }</td>
                                    <td>${t.is_active
                                        ? `<span style="font-size:.8rem;color:var(--theme-text-muted)">Current</span>`
                                        : `<button class="brutal-btn success" style="padding:.3rem .7rem;font-size:.8rem;" onclick="handleSetTermActive(${t.id})">Activate</button>`
                                    }</td>
                                </tr>`).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        container.innerHTML = `
            <div class="glass-card" style="margin-bottom:1.5rem;${isFinalActive ? 'border-color:rgba(16,185,129,.3);' : ''}">
                <h3 class="card-title">Year-End Assessment Trigger</h3>
                <p style="font-size:.9rem;font-weight:600;color:var(--theme-text-muted);margin-bottom:1.5rem;">
                    When active, all homeroom teachers gain access to computing class averages, rankings, and promotion decisions.
                </p>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <span style="font-weight:700;font-size:.9rem;">Status:</span>
                        <span class="${isFinalActive ? 'section-chip" style="background:rgba(16,185,129,.12);border-color:rgba(16,185,129,.3);color:#6ee7b7;"' : 'section-chip" style="background:rgba(244,63,94,.12);border-color:rgba(244,63,94,.3);color:#fda4af;"'}>${isFinalActive ? 'Open / Active' : 'Closed / Inactive'}</span>
                    </div>
                    <button class="brutal-btn ${isFinalActive ? 'danger' : 'success'}" onclick="toggleFinalMode(${isFinalActive ? 0 : 1})">
                        ${isFinalActive ? 'Close Year-End Portals' : 'Open Year-End Portals'}
                    </button>
                </div>
            </div>

            <div class="glass-card">
                <h3 class="card-title">Academic Term Configuration</h3>
                <p style="font-size:.9rem;font-weight:600;color:var(--theme-text-muted);margin-bottom:.75rem;">
                    Choose between Semester (2 terms) or Trimester (3 terms) system.
                    <span style="color:var(--danger);"> Warning: Reconfiguring terms will wipe existing term data for the active year.</span>
                </p>
                <form onsubmit="handleConfigureTerms(event)" style="display:flex;gap:1rem;align-items:flex-end;margin-bottom:2rem;flex-wrap:wrap;">
                    <div>
                        <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--theme-text-muted);display:block;margin-bottom:.35rem;">Term System</label>
                        <select id="termSystemSelect" class="brutal-input" style="margin-bottom:0;width:280px;height:auto;" required>
                            <option value="2-term">Semester System (2 Terms)</option>
                            <option value="3-term">Trimester System (3 Terms)</option>
                        </select>
                    </div>
                    <button type="submit" class="brutal-btn warning">Reconfigure Terms</button>
                </form>
                <h4 style="font-weight:800;text-transform:uppercase;font-size:.9rem;letter-spacing:.06em;margin-bottom:.5rem;">Active Term Ledger</h4>
                ${termsHtml}
            </div>
        `;
    }

    async function toggleFinalMode(activeState) {
        const res = await apiRequest('director/toggle-final-assessment', 'POST', { active: activeState });
        if (res && res.success) { alert(res.message); loadDirectorConfigData(); loadDirectorOverviewData(); }
        else alert(res.message || 'Failed to update.');
    }

    async function handleConfigureTerms(e) {
        e.preventDefault();
        if (!confirm('Are you sure? This will delete existing terms for the active year!')) return;
        const res = await apiRequest('director/terms', 'POST', { system_type: document.getElementById('termSystemSelect').value });
        if (res && res.success) { alert(res.message); loadDirectorConfigData(); }
        else alert(res.message || 'Failed to configure terms.');
    }

    async function handleSetTermActive(termId) {
        const res = await apiRequest('director/terms', 'PUT', { term_id: termId });
        if (res && res.success) { alert(res.message); loadDirectorConfigData(); }
        else alert(res.message || 'Failed to activate term.');
    }

    /* =====================================================
       MESSENGER (All roles)
       ===================================================== */
    async function loadMessenger(role) {
        const containerId = `${role}ChatPanel`;
        const container = document.getElementById(containerId);
        if (!container) return;

        const res = await apiRequest('communications/list');
        if (!res || !res.success) {
            container.innerHTML = `<div class="notice danger">Failed to load messages.</div>`;
            return;
        }

        container.innerHTML = `
            <div style="display:grid;grid-template-columns:1fr 2fr;gap:2rem;">
                <div>
                    <h4 style="font-size:.9rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;margin-bottom:1rem;">New Message</h4>
                    <form onsubmit="handleSendMessage(event,'${role}')">
                        <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;color:var(--theme-text-muted);">Recipient</label>
                        <select id="chatTargetSelect" class="brutal-input" style="height:auto;" required>
                            <option value="">— Choose Contact —</option>
                            ${res.contacts.map(c => `<option value="${c.id}" data-role="${c.role}">${c.full_name} (${c.role.toUpperCase()})</option>`).join('')}
                        </select>
                        <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;display:block;margin-bottom:.35rem;color:var(--theme-text-muted);">Message</label>
                        <textarea id="chatMessageText" class="brutal-input" style="height:110px;resize:none;" placeholder="Type your message..." required></textarea>
                        <button type="submit" class="brutal-btn success" style="width:100%;">Send Message</button>
                    </form>
                </div>

                <div>
                    <h4 style="font-size:.9rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;margin-bottom:1rem;">Chat History</h4>
                    <div class="chat-log" id="chatHistoryBox">
                        ${res.messages.length === 0
                            ? '<p style="text-align:center;margin-top:4rem;color:var(--theme-text-muted);">No messages yet.</p>'
                            : res.messages.map(m => {
                                const isSelf = m.sender_role === role && m.sender_id == localStorage.getItem('user_id');
                                return `
                                    <div class="chat-bubble ${isSelf ? 'self' : 'other'}">
                                        <div class="chat-sender">${isSelf ? 'You' : m.sender_name + ' (' + m.sender_role.toUpperCase() + ')'}</div>
                                        <div>${m.message}</div>
                                        <div class="chat-time">${new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'})}</div>
                                    </div>
                                `;
                            }).join('')
                        }
                    </div>
                </div>
            </div>
        `;

        const box = document.getElementById('chatHistoryBox');
        if (box) box.scrollTop = box.scrollHeight;
    }

    async function handleSendMessage(e, role) {
        e.preventDefault();
        const sel = document.getElementById('chatTargetSelect');
        const res = await apiRequest('communications/send', 'POST', {
            receiver_role: sel.options[sel.selectedIndex].getAttribute('data-role'),
            receiver_id:   sel.value,
            message:       document.getElementById('chatMessageText').value
        });
        if (res && res.success) { alert(res.message); loadMessenger(role); }
        else alert(res.message || 'Failed to send.');
    }
    </script>
</body>
</html>
