<?php 
// Ensure this is only loaded via the main router
if (!isset($schoolSite)) {
    http_response_code(403);
    exit("Direct access not allowed.");
}

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : "Next-Gen Education Platform";
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "A unified, intelligent operating system seamlessly connecting students, teachers, and parents.";
$brandName = !empty($schoolSite['name']) ? $schoolSite['name'] : "Enterprise Portal";
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : $heroSubtitle;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Dashboard</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        :root {
            --primary: <?php echo !empty($schoolSite['primary_color']) ? htmlspecialchars($schoolSite['primary_color']) : '#3b82f6'; ?>;
            --glass-bg: rgba(var(--bg-card-rgb, 255, 255, 255), 0.7);
            --glass-border: rgba(var(--border-color-rgb, 226, 232, 240), 0.5);
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-color, #f8fafc);
            color: var(--text-color, #0f172a);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Floating Glass Nav */
        .glass-nav {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1200px;
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 100px;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .nav-logo {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .nav-links a:hover {
            color: var(--primary);
        }
        .pill-btn {
            background: var(--primary);
            color: #fff !important;
            padding: 0.6rem 1.5rem;
            border-radius: 100px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        }
        .pill-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            opacity: 0.9;
        }

        /* Modern Hero */
        .hero {
            padding: 12rem 5% 6rem;
            text-align: center;
            background: radial-gradient(circle at top, var(--bg-card) 0%, var(--bg-color) 100%);
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }
        .badge {
            background: rgba(128, 128, 128, 0.1);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }
        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 5rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 3rem;
            position: relative;
            z-index: 1;
        }

        /* Bento Grid */
        .bento-section {
            padding: 4rem 5% 8rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: minmax(250px, auto);
            gap: 1.5rem;
        }
        
        .bento-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            z-index: 1;
        }
        .bento-item:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        
        /* Subtle glow effect on hover */
        .bento-item::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, var(--primary) 0%, transparent 60%);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        .bento-item:hover::after {
            opacity: 0.05;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(128,128,128,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            color: var(--primary);
        }
        
        /* Grid placements */
        .student-hub { grid-column: span 2; grid-row: span 2; }
        .teacher-core { grid-column: span 2; grid-row: span 1; }
        .parent-radar { grid-column: span 1; grid-row: span 1; }
        .director-console { grid-column: span 1; grid-row: span 1; }

        .bento-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }
        .student-hub .bento-title {
            font-size: 2.5rem;
        }
        .bento-desc {
            color: var(--text-muted);
            font-size: 1rem;
            flex-grow: 1;
        }
        .bento-link {
            margin-top: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .bento-link svg {
            transition: transform 0.3s ease;
        }
        .bento-item:hover .bento-link svg {
            transform: translateX(5px);
        }

        @media (max-width: 992px) {
            .bento-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .student-hub, .teacher-core, .parent-radar, .director-console {
                grid-column: span 2;
                grid-row: auto;
            }
        }
        @media (max-width: 768px) {
            .nav-links { display: none; }
        }
    </style>
</head>
<body>
    <nav class="glass-nav">
        <a href="#" class="nav-logo">
            <div style="width: 24px; height: 24px; border-radius: 6px; background: var(--primary); display: flex; align-items: center; justify-content: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <?php echo htmlspecialchars($brandName); ?>
        </a>
        <div class="nav-links">
            <a href="#system">System Modules</a>
            <a href="/login.php?role=student">Student Login</a>
            <a href="/login.php?role=teacher">Faculty Login</a>
        </div>
        <div>
            <a href="#system" class="pill-btn">Access Portals</a>
        </div>
    </nav>

    <div class="hero">
        <div class="badge">System Online v2.4.0</div>
        <h1><?php echo htmlspecialchars($heroTitle); ?></h1>
        <p><?php echo htmlspecialchars($heroSubtitle); ?></p>
    </div>

    <div class="bento-section" id="system">
        <div class="bento-grid">
            
            <!-- Student Hub (Large) -->
            <a href="/login.php?role=student" class="bento-item student-hub">
                <div class="icon-wrapper" style="width: 64px; height: 64px; border-radius: 16px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <h3 class="bento-title">Student Hub</h3>
                <p class="bento-desc" style="font-size: 1.15rem;">The central nervous system for students. Access assignments, track real-time GPA analytics, view interactive schedules, and communicate seamlessly with instructors in a distraction-free environment.</p>
                <div class="bento-link">Enter Student Dashboard <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>

            <!-- Teacher Core (Wide) -->
            <a href="/login.php?role=teacher" class="bento-item teacher-core">
                <div class="icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                </div>
                <h3 class="bento-title">Teacher Core</h3>
                <p class="bento-desc">Advanced rubric grading, one-click attendance tracking, and comprehensive classroom management tools designed to save you hours every week.</p>
                <div class="bento-link">Enter Teacher Core <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>

            <!-- Parent Radar (Square) -->
            <a href="/login.php?role=parent" class="bento-item parent-radar">
                <div class="icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3 class="bento-title">Parent Radar</h3>
                <p class="bento-desc">Real-time alerts, performance monitoring, and instant faculty messaging.</p>
                <div class="bento-link">Enter Radar <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>

            <!-- Director Console (Square) -->
            <a href="/login.php?role=director" class="bento-item director-console">
                <div class="icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg>
                </div>
                <h3 class="bento-title">Admin Console</h3>
                <p class="bento-desc">High-level analytics, billing management, and global system configuration.</p>
                <div class="bento-link">Enter Console <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            </a>

        </div>
    </div>

    <footer style="text-align: center; padding: 4rem 5%; border-top: 1px solid var(--border-color); color: var(--text-muted);">
        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. Operating System enabled.</p>
    </footer>
</body>
</html>
