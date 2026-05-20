<?php 
// Ensure this is only loaded via the main router
if (!isset($schoolSite)) {
    http_response_code(403);
    exit("Direct access not allowed.");
}

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : $schoolSite['name'];
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "A seamless, transparent, and connected educational experience powered by intelligent design and intuitive workflows.";
$brandName = $schoolSite['name'];
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : $heroSubtitle;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Portal</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@200;300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        :root {
            --primary: <?php echo !empty($schoolSite['primary_color']) ? htmlspecialchars($schoolSite['primary_color']) : '#0071e3'; ?>;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color, #fbfbfd);
            color: var(--text-color, #1d1d1f);
            line-height: 1.5;
            letter-spacing: -0.011em;
            -webkit-font-smoothing: antialiased;
        }

        /* Nav Bar - Apple Style */
        nav {
            position: sticky;
            top: 0;
            background: rgba(var(--bg-color-rgb, 251, 251, 253), 0.8);
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
            z-index: 9999;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 1.5rem;
        }

        .nav-container {
            width: 100%;
            max-width: 1024px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-color, #1d1d1f);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.02em;
            opacity: 0.9;
            transition: opacity 0.2s;
        }

        .nav-logo:hover {
            opacity: 1;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--text-color, #1d1d1f);
            font-size: 0.8rem;
            font-weight: 400;
            opacity: 0.7;
            transition: opacity 0.2s, color 0.2s;
        }

        .nav-menu a:hover {
            opacity: 1;
            color: var(--primary);
        }

        /* Minimal Button */
        .btn-minimal {
            background-color: var(--primary);
            color: #fff !important;
            border-radius: 980px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 400;
            display: inline-block;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-minimal:hover {
            opacity: 0.9;
        }

        .btn-minimal:active {
            transform: scale(0.98);
        }

        /* Main Container */
        .container {
            max-width: 980px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Hero Section */
        .hero {
            padding: 9rem 0 6rem;
            text-align: center;
        }

        .hero-tag {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 700;
            letter-spacing: -0.035em;
            line-height: 1.08;
            color: var(--text-color, #1d1d1f);
            margin-bottom: 1.5rem;
        }

        .hero p {
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            font-weight: 400;
            line-height: 1.38;
            letter-spacing: 0.011em;
            color: var(--text-muted, #86868b);
            max-width: 680px;
            margin: 0 auto 3rem;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            align-items: center;
        }

        .hero-actions a {
            text-decoration: none;
            font-size: 1.05rem;
            font-weight: 400;
            transition: color 0.2s, opacity 0.2s;
        }

        .hero-actions .btn-primary-link {
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .hero-actions .btn-primary-link:hover {
            text-decoration: underline;
        }

        .hero-actions .btn-secondary-link {
            color: var(--text-color);
            opacity: 0.8;
        }

        .hero-actions .btn-secondary-link:hover {
            opacity: 1;
            color: var(--primary);
        }

        /* Directory Section */
        .directory-section {
            padding: 4rem 0 8rem;
            border-top: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 600;
            letter-spacing: -0.03em;
            text-align: center;
            margin-bottom: 4rem;
        }

        .dir-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .dir-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2.2rem 1rem;
            border-bottom: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
            text-decoration: none;
            color: inherit;
            transition: background-color 0.2s, transform 0.2s;
            border-radius: 12px;
        }

        .dir-row:hover {
            background-color: var(--bg-card, rgba(0, 0, 0, 0.015));
            transform: scale(1.005);
        }

        .dir-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .dir-number {
            font-size: 1rem;
            font-weight: 300;
            color: var(--text-muted);
            opacity: 0.5;
            width: 24px;
        }

        .dir-role-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .dir-role {
            font-size: 1.35rem;
            font-weight: 500;
            letter-spacing: -0.02em;
            color: var(--text-color);
        }

        .dir-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        .dir-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            font-size: 0.95rem;
            font-weight: 400;
        }

        .dir-right svg {
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dir-row:hover .dir-right svg {
            transform: translateX(4px);
        }

        /* Stats Grid - Ultra clean */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
            margin: 4rem 0;
            padding-bottom: 6rem;
        }

        .stat-card {
            text-align: center;
        }

        .stat-val {
            font-size: 3.5rem;
            font-weight: 200;
            letter-spacing: -0.05em;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .stat-lbl {
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
        }

        /* Footer */
        footer {
            padding: 5rem 0 3rem;
            border-top: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .footer-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 400;
            text-align: center;
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            .dir-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1.5rem 0.5rem;
            }
            .dir-left {
                gap: 1rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .hero h1 {
                font-size: 2.4rem;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="#" class="nav-logo">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <?php echo htmlspecialchars($brandName); ?>
            </a>
            <div class="nav-menu">
                <a href="#about">Philosophy</a>
                <a href="#portals">Access Hub</a>
                <a href="/login.php?role=director" class="btn-minimal">Director Portal</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero">
            <div class="hero-tag">A New Epoch of Scholarship</div>
            <h1><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <div class="hero-actions">
                <a href="#portals" class="btn-primary-link">
                    Select Your Pathway 
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="vertical-align: middle;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="#about" class="btn-secondary-link">Learn more</a>
            </div>
        </div>

        <div class="stats-grid" id="about">
            <div class="stat-card">
                <div class="stat-val">100%</div>
                <div class="stat-lbl">Digital Integration</div>
            </div>
            <div class="stat-card">
                <div class="stat-val">0s</div>
                <div class="stat-lbl">Latency in Communications</div>
            </div>
            <div class="stat-card">
                <div class="stat-val">24/7</div>
                <div class="stat-lbl">Academic Continuity</div>
            </div>
        </div>

        <div class="directory-section" id="portals">
            <h2 class="section-title">Institutional Directories</h2>
            <div class="dir-list">
                
                <!-- Student Portal -->
                <a href="/login.php?role=student" class="dir-row">
                    <div class="dir-left">
                        <div class="dir-number">01</div>
                        <div class="dir-role-info">
                            <span class="dir-role">Student Portal</span>
                            <span class="dir-desc">Access grades, custom learning resources, schedules, and active assignments.</span>
                        </div>
                    </div>
                    <div class="dir-right">
                        <span>Access Gateway</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Faculty Portal -->
                <a href="/login.php?role=teacher" class="dir-row">
                    <div class="dir-left">
                        <div class="dir-number">02</div>
                        <div class="dir-role-info">
                            <span class="dir-role">Faculty Portal</span>
                            <span class="dir-desc">Manage grades, track active attendance, schedule classes, and upload resources.</span>
                        </div>
                    </div>
                    <div class="dir-right">
                        <span>Access Gateway</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Parent Portal -->
                <a href="/login.php?role=parent" class="dir-row">
                    <div class="dir-left">
                        <div class="dir-number">03</div>
                        <div class="dir-role-info">
                            <span class="dir-role">Parent Portal</span>
                            <span class="dir-desc">View real-time performance analytics, monitor attendance, and chat with teachers.</span>
                        </div>
                    </div>
                    <div class="dir-right">
                        <span>Access Gateway</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Admin Portal -->
                <a href="/login.php?role=director" class="dir-row">
                    <div class="dir-left">
                        <div class="dir-number">04</div>
                        <div class="dir-role-info">
                            <span class="dir-role">Director Console</span>
                            <span class="dir-desc">Manage subscription plans, check school-wide analytical reports, and alter themes.</span>
                        </div>
                    </div>
                    <div class="dir-right">
                        <span>Access Gateway</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </a>

            </div>
        </div>

        <footer>
            <div class="nav-logo" style="margin-bottom: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <?php echo htmlspecialchars($brandName); ?>
            </div>
            <div class="footer-text">
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. All rights reserved. Designed to facilitate distraction-free learning.
            </div>
        </footer>
    </div>
</body>
</html>
