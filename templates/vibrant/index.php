<?php 
// Ensure this is only loaded via the main router
if (!isset($schoolSite)) {
    http_response_code(403);
    exit("Direct access not allowed.");
}

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : $schoolSite['name'];
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "School management software that actually has a personality. Fast, loud, and built for the modern institution.";
$brandName = $schoolSite['name'];
$templateName = $schoolSite['template_name'] ?? 'vibrant';
$primaryColor = !empty($schoolSite['primary_color']) ? $schoolSite['primary_color'] : '#000000';
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
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@500;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        :root {
            --primary: <?php echo htmlspecialchars($primaryColor); ?>;
            --border-dark: #000000;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-color, #f3f4f6);
            color: var(--text-color, #000);
            line-height: 1.4;
            padding-bottom: 5rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Nav Bar - Neo-Brutalist Style */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border: 4px solid var(--border-dark);
            background: var(--bg-card, #fff);
            margin-top: 1.5rem;
            margin-bottom: 4rem;
            box-shadow: 8px 8px 0 var(--border-dark);
        }

        .nav-logo {
            font-size: 1.5rem;
            font-weight: 900;
            text-decoration: none;
            color: var(--text-color, inherit);
            text-transform: uppercase;
            letter-spacing: -0.03em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            font-weight: 800;
            text-decoration: none;
            color: var(--text-color, inherit);
            text-transform: uppercase;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--accent-3, #f43f5e);
        }

        /* Brutalist Button */
        .brutal-btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            border: 4px solid var(--border-dark);
            background-color: var(--primary);
            color: #fff;
            text-decoration: none;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            box-shadow: 6px 6px 0 var(--border-dark);
            transition: all 0.15s cubic-bezier(0, 0, 0, 1);
            cursor: pointer;
        }

        .brutal-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0 var(--border-dark);
        }

        .brutal-btn:active {
            transform: translate(4px, 4px);
            box-shadow: 2px 2px 0 var(--border-dark);
        }

        /* Hero Section */
        .hero {
            position: relative;
            text-align: center;
            padding: 4rem 1.5rem 6rem;
            border: 4px solid var(--border-dark);
            background: var(--bg-card, #fff);
            box-shadow: 12px 12px 0 var(--border-dark);
            margin-bottom: 6rem;
            overflow: hidden;
        }

        .hero-tag {
            display: inline-block;
            background: var(--accent-1, #06b6d4);
            padding: 0.5rem 1rem;
            border: 4px solid var(--border-dark);
            transform: rotate(-3deg);
            font-weight: 900;
            text-transform: uppercase;
            box-shadow: 4px 4px 0 var(--border-dark);
            color: #000;
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
        }

        .hero h1 {
            font-size: clamp(3rem, 8vw, 6.5rem);
            line-height: 0.9;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: -0.04em;
            font-weight: 900;
            color: var(--text-color, #000);
        }

        .hero p {
            font-size: clamp(1.2rem, 3vw, 1.6rem);
            font-weight: 700;
            max-width: 800px;
            margin: 0 auto 3.5rem;
            line-height: 1.3;
            color: var(--text-muted, #374151);
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        /* Continuous Ticker Tape */
        .ticker {
            margin-bottom: 6rem;
            border-top: 4px solid var(--border-dark);
            border-bottom: 4px solid var(--border-dark);
            padding: 1.2rem 0;
            background: var(--accent-2, #eab308);
            overflow: hidden;
            white-space: nowrap;
            color: #000;
            transform: rotate(1deg);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .ticker-wrap {
            display: inline-block;
            animation: marquee 25s linear infinite;
            font-weight: 900;
            font-size: 1.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* 4-Role Hub Grid */
        .role-section-title {
            font-size: 3.5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            margin-bottom: 3.5rem;
            text-align: center;
            text-shadow: 3px 3px 0 var(--accent-1);
        }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2.5rem;
            margin-bottom: 6rem;
        }

        .role-card {
            border: 4px solid var(--border-dark);
            padding: 2.5rem;
            color: #000;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s cubic-bezier(0, 0, 0, 1);
            position: relative;
        }

        .role-card:nth-child(1) { background: var(--accent-1, #22d3ee); box-shadow: 8px 8px 0 var(--border-dark); }
        .role-card:nth-child(2) { background: var(--accent-2, #facc15); box-shadow: 8px 8px 0 var(--border-dark); }
        .role-card:nth-child(3) { background: var(--accent-3, #f43f5e); box-shadow: 8px 8px 0 var(--border-dark); }
        .role-card:nth-child(4) { background: #a855f7; box-shadow: 8px 8px 0 var(--border-dark); }

        .role-card:hover {
            transform: translate(-6px, -6px);
        }
        .role-card:nth-child(1):hover { box-shadow: 14px 14px 0 var(--border-dark); }
        .role-card:nth-child(2):hover { box-shadow: 14px 14px 0 var(--border-dark); }
        .role-card:nth-child(3):hover { box-shadow: 14px 14px 0 var(--border-dark); }
        .role-card:nth-child(4):hover { box-shadow: 14px 14px 0 var(--border-dark); }

        .role-title {
            font-size: 2.3rem;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .role-desc {
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 2.5rem;
            flex-grow: 1;
        }

        .role-action-btn {
            display: inline-block;
            align-self: flex-start;
            padding: 0.6rem 1.2rem;
            border: 3px solid var(--border-dark);
            background: #fff;
            color: #000;
            font-weight: 800;
            text-transform: uppercase;
            box-shadow: 4px 4px 0 var(--border-dark);
            font-size: 0.9rem;
            transition: all 0.1s;
        }

        .role-card:hover .role-action-btn {
            background: var(--border-dark);
            color: #fff;
        }

        /* Footer */
        footer {
            border: 4px solid var(--border-dark);
            background: var(--bg-card, #fff);
            padding: 3rem 1.5rem;
            text-align: center;
            box-shadow: 8px 8px 0 var(--border-dark);
            margin-top: 6rem;
        }

        .footer-logo {
            font-size: 2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
        }

        .footer-text {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero { padding: 3rem 1rem 4rem; }
            .ticker { transform: none; }
            .role-section-title { font-size: 2.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="#" class="nav-logo">⚡ <?php echo htmlspecialchars($brandName); ?></a>
            <div class="nav-links">
                <a href="#about">About</a>
                <a href="#portals">Portals</a>
                <a href="/login.php?role=director">Directors Only</a>
            </div>
            <div>
                <a href="#portals" class="brutal-btn" style="font-size: 0.9rem; padding: 0.5rem 1.2rem;">Get Started</a>
            </div>
        </nav>

        <div class="hero" id="about">
            <div class="hero-tag">Active Platform v3.0</div>
            <h1><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <div class="hero-actions">
                <a href="#portals" class="brutal-btn" style="background: var(--accent-3, #f43f5e); color: #000;">Enter Gateway</a>
                <a href="/register.php" class="brutal-btn" style="background: #fff; color: #000;">Register School</a>
            </div>
        </div>

        <div class="ticker">
            <div class="ticker-wrap">
                INNOVATIVE LEARNING // BRUTAL SIMPLICITY // REAL-TIME ANALYTICS // SEAMLESS EDUCATION SYSTEMS // ROLE-BASED ACCESS CONTROLS // 100% RELIABILITY // 
                INNOVATIVE LEARNING // BRUTAL SIMPLICITY // REAL-TIME ANALYTICS // SEAMLESS EDUCATION SYSTEMS // ROLE-BASED ACCESS CONTROLS // 100% RELIABILITY // 
            </div>
        </div>

        <div id="portals">
            <h2 class="role-section-title">Institutional Portals</h2>
            <div class="role-grid">
                
                <!-- Student Portal -->
                <a href="/login.php?role=student" class="role-card">
                    <div>
                        <h3 class="role-title">Students</h3>
                        <p class="role-desc">No more guessing. View your schedules, monitor grades, hand in assignments, and chat with teachers directly.</p>
                    </div>
                    <span class="role-action-btn">Enter Portal &rarr;</span>
                </a>

                <!-- Faculty Portal -->
                <a href="/login.php?role=teacher" class="role-card">
                    <div>
                        <h3 class="role-title">Faculty</h3>
                        <p class="role-desc">Spend less time handling grade sheets and attendance, and more time delivering pure excellence to your students.</p>
                    </div>
                    <span class="role-action-btn">Enter Portal &rarr;</span>
                </a>

                <!-- Parent Portal -->
                <a href="/login.php?role=parent" class="role-card">
                    <div>
                        <h3 class="role-title">Parents</h3>
                        <p class="role-desc">Gain complete peace of mind. Watch real-time attendance, look over transcripts, and contact faculty instantly.</p>
                    </div>
                    <span class="role-action-btn">Enter Portal &rarr;</span>
                </a>

                <!-- Director Portal -->
                <a href="/login.php?role=director" class="role-card">
                    <div>
                        <h3 class="role-title">Directors</h3>
                        <p class="role-desc">Oversee full metrics. Modify visual themes, handle premium subscriptions, manage billing, and analyze data.</p>
                    </div>
                    <span class="role-action-btn">Enter Portal &rarr;</span>
                </a>

            </div>
        </div>

        <footer>
            <div class="footer-logo">⚡ <?php echo htmlspecialchars($brandName); ?></div>
            <p class="footer-text">
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. ALL RIGHTS RESERVED. RUNNING ON PREMIUM MULTI-TENANCY CORE.
            </p>
        </footer>
    </div>
</body>
</html>
