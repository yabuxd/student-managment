<?php 
// Ensure this is only loaded via the main router
if (!isset($schoolSite)) {
    http_response_code(403);
    exit("Direct access not allowed.");
}

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : $schoolSite['name'];
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "The futuristic paradigm of educational management. Fluid, elegant, and built for modern academic communities.";
$brandName = $schoolSite['name'];
$templateName = $schoolSite['template_name'] ?? 'aurora';
$primaryColor = !empty($schoolSite['primary_color']) ? $schoolSite['primary_color'] : '#6366f1';
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Plus Jakarta Sans';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : $heroSubtitle;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Hub</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <!-- Premium Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/templates/aurora/assets/css/style.css">
    
    <style>
        :root {
            --primary: <?php echo htmlspecialchars($primaryColor); ?>;
            --font-custom: '<?php echo htmlspecialchars($typography); ?>', 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-custom);
            background-color: var(--bg-color, #070a13);
            color: var(--text-color, #f8fafc);
            line-height: 1.5;
            padding-bottom: 5rem;
            position: relative;
        }

        /* Nav Bar - Glassmorphism Style */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(16, 22, 38, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            margin-top: 1.5rem;
            margin-bottom: 4rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .nav-logo {
            font-size: 1.35rem;
            font-weight: 900;
            text-decoration: none;
            color: inherit;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, var(--accent-1, #6366f1), var(--accent-2, #06b6d4));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            font-weight: 600;
            text-decoration: none;
            color: var(--text-color, #f8fafc);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--accent-1, #6366f1);
            opacity: 1;
            text-shadow: 0 0 10px rgba(99, 102, 241, 0.4);
        }

        /* Modern Gradient Button */
        .brutal-btn {
            display: inline-block;
            padding: 0.85rem 2rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent-1, #6366f1), var(--accent-2, #06b6d4));
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .brutal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.5), 0 0 15px rgba(6, 182, 212, 0.3);
        }

        .brutal-btn:active {
            transform: translateY(1px);
        }

        /* Hero Section */
        .hero {
            position: relative;
            text-align: center;
            padding: 5rem 2rem 6.5rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(16, 22, 38, 0.6);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.4);
            margin-bottom: 6rem;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -20%; left: 30%;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-tag {
            display: inline-block;
            background: rgba(6, 182, 212, 0.1);
            color: var(--accent-2, #06b6d4);
            padding: 0.4rem 1.1rem;
            border: 1px solid rgba(6, 182, 212, 0.25);
            border-radius: 99px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            margin-bottom: 2rem;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.1);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: -1.5px;
            font-weight: 900;
            background: linear-gradient(180deg, #ffffff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: clamp(1.1rem, 2.5vw, 1.35rem);
            font-weight: 500;
            max-width: 750px;
            margin: 0 auto 3rem;
            line-height: 1.5;
            color: var(--text-muted, #94a3b8);
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        /* Modern Dynamic Ticker Tape */
        .ticker {
            margin-bottom: 6rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.25rem 0;
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.08) 0%, rgba(6, 182, 212, 0.08) 50%, rgba(99, 102, 241, 0.08) 100%);
            backdrop-filter: blur(8px);
            overflow: hidden;
            white-space: nowrap;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .ticker-wrap {
            display: inline-block;
            animation: marquee 28s linear infinite;
            font-weight: 800;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-color, #f8fafc);
            opacity: 0.9;
        }

        .ticker-wrap span {
            color: var(--accent-2, #06b6d4);
            margin: 0 1.5rem;
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.3);
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Role Hub Grid */
        .role-section-title {
            font-size: 2.2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin-bottom: 3rem;
            text-align: center;
            background: linear-gradient(135deg, #ffffff 0%, var(--accent-1, #6366f1) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2rem;
            margin-bottom: 6rem;
        }

        .role-card {
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(16, 22, 38, 0.55);
            padding: 2.5rem;
            color: var(--text-color, #f8fafc);
            text-decoration: none;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            backdrop-filter: blur(12px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 4px;
            background: var(--accent-1, #6366f1);
            opacity: 0.75;
            transition: all 0.3s ease;
        }

        .role-card:nth-child(1)::before { background: var(--accent-1, #6366f1); }
        .role-card:nth-child(2)::before { background: var(--accent-2, #06b6d4); }
        .role-card:nth-child(3)::before { background: var(--accent-3, #d946ef); }
        .role-card:nth-child(4)::before { background: #8b5cf6; }

        .role-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4), 0 0 20px rgba(99, 102, 241, 0.1);
            border-color: rgba(255,255,255,0.12);
        }

        .role-title {
            font-size: 1.6rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }
        
        .role-card:nth-child(1) .role-title { color: var(--accent-1, #6366f1); }
        .role-card:nth-child(2) .role-title { color: var(--accent-2, #06b6d4); }
        .role-card:nth-child(3) .role-title { color: var(--accent-3, #d946ef); }
        .role-card:nth-child(4) .role-title { color: #a78bfa; }

        .role-desc {
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.5;
            margin-bottom: 2rem;
            color: var(--text-muted, #94a3b8);
            flex-grow: 1;
        }

        .role-action-btn {
            display: inline-flex;
            align-self: flex-start;
            padding: 0.5rem 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
            color: inherit;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
        }

        .role-card:hover .role-action-btn {
            background: var(--text-color, #ffffff);
            color: #070a13;
            border-color: var(--text-color, #ffffff);
        }

        /* Footer */
        footer {
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(16, 22, 38, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin-top: 6rem;
        }

        .footer-logo {
            font-size: 1.5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--accent-1, #6366f1), var(--accent-2, #06b6d4));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-text {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-muted, #94a3b8);
            letter-spacing: 0.05em;
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero { padding: 3rem 1.5rem 4rem; }
            .ticker { transform: none; }
            .role-section-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="#" class="nav-logo">✦ <?php echo htmlspecialchars($brandName); ?></a>
            <div class="nav-links">
                <a href="#about">About</a>
                <a href="#portals">Gateway</a>
                <a href="/login.php?role=director">Administration</a>
            </div>
            <div>
                <a href="#portals" class="brutal-btn" style="font-size: 0.8rem; padding: 0.6rem 1.25rem;">Get Started</a>
            </div>
        </nav>

        <div class="hero" id="about">
            <div class="hero-tag">Home for wisdom</div>
            <h1><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <div class="hero-actions">
                <a href="#portals" class="brutal-btn" style="background: linear-gradient(135deg, var(--accent-3, #d946ef), var(--accent-1, #6366f1)); box-shadow: 0 4px 14px rgba(217, 70, 239, 0.35);">Enter Gateway</a>
                <a href="/register.php" class="brutal-btn" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); box-shadow: none;">Register School</a>
            </div>
        </div>

        <div class="ticker">
            <div class="ticker-wrap">
                INTELLIGENT PEDAGOGY <span>✦</span> FLUID GLASS LAYOUTS <span>✦</span> DYNAMIC SUBDOMAINS <span>✦</span> DECENTRALIZED DATA CONTROL <span>✦</span> MULTI-TENANT ARCHITECTURE <span>✦</span> 
                INTELLIGENT PEDAGOGY <span>✦</span> FLUID GLASS LAYOUTS <span>✦</span> DYNAMIC SUBDOMAINS <span>✦</span> DECENTRALIZED DATA CONTROL <span>✦</span> MULTI-TENANT ARCHITECTURE <span>✦</span> 
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
            <div class="footer-logo">✦ <?php echo htmlspecialchars($brandName); ?></div>
            <p class="footer-text">
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. ALL RIGHTS RESERVED. RUNNING ON PREMIUM MULTI-TENANCY CORE.
            </p>
        </footer>
    </div>
</body>
</html>
