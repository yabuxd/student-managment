<?php 
// Ensure this is only loaded via the main router
if (!isset($schoolSite)) {
    http_response_code(403);
    exit("Direct access not allowed.");
}

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : "Tradition Meets Tomorrow";
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "A heritage of academic excellence, preparing the leaders of the next generation through rigorous scholarship and character development.";
$brandName = !empty($schoolSite['name']) ? $schoolSite['name'] : "Institution Portal";
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Playfair Display';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : $heroSubtitle;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Welcome</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        :root {
            --primary: <?php echo !empty($schoolSite['primary_color']) ? htmlspecialchars($schoolSite['primary_color']) : '#007bff'; ?>;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            background-color: var(--bg-color, #fcfcfc);
            color: var(--text-color, #2c3e50);
            line-height: 1.6;
        }
        .academic-nav {
            border-bottom: 1px solid var(--border-color, #e0e0e0);
            background: var(--bg-card, #fff);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-left: 2rem;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: var(--primary);
        }
        .academic-btn {
            background-color: var(--primary);
            color: #fff;
            padding: 0.75rem 2rem;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid var(--primary);
            display: inline-block;
        }
        .academic-btn:hover {
            background-color: transparent;
            color: var(--primary);
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            text-align: center;
            padding: 10rem 5% 8rem;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&q=80&w=2000') center/cover;
            color: #fff;
            border-bottom: 5px solid var(--primary);
        }
        .hero-title {
            font-size: 4.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            line-height: 1.1;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .hero-subtitle {
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto 3rem;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: #e2e8f0;
        }

        /* The Quad - Role Hub */
        .quad-section {
            padding: 6rem 5%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        .section-header h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        .section-header p {
            color: var(--text-muted);
            font-family: 'Inter', sans-serif;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        .role-card {
            background: var(--bg-card, #fff);
            border: 1px solid var(--border-color, #e0e0e0);
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: left;
        }
        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }
        .role-card:hover::before {
            transform: scaleX(1);
        }
        .role-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--bg-color);
            color: var(--primary);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        .role-card:hover .role-icon {
            background: var(--primary);
            color: #fff;
        }
        .role-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .role-desc {
            color: var(--text-muted);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        .role-link {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.85rem;
        }

        /* Stats Section */
        .stats-section {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 4rem 5%;
        }
        .stats-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            text-align: center;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-family: 'Inter', sans-serif;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.85rem;
        }

        /* Footer */
        footer {
            background: var(--bg-color);
            padding: 4rem 5% 2rem;
            text-align: center;
            border-top: 1px solid var(--border-color);
        }
        .footer-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .nav-links { display: none; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <nav class="academic-nav">
        <a href="#" style="text-decoration: none; color: inherit; display: flex; align-items: center; font-size: 1.25rem; font-weight: 700;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right: 0.5rem;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <?php echo htmlspecialchars($brandName); ?>
        </a>
        <div class="nav-links">
            <a href="#portals">Campus Portals</a>
            <a href="#about">About Us</a>
            <a href="/login.php?role=director" class="academic-btn" style="margin-left: 2rem;">Admin Login</a>
        </div>
    </nav>

    <div class="hero-section">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 class="hero-title"><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <a href="#portals" class="academic-btn" style="padding: 1.2rem 3rem; font-size: 1rem;">Access Portals</a>
        </div>
    </div>

    <div class="stats-section" id="about">
        <div class="stats-grid">
            <div>
                <div class="stat-number">100%</div>
                <div class="stat-label">Graduation Rate</div>
            </div>
            <div>
                <div class="stat-number">15:1</div>
                <div class="stat-label">Student/Teacher Ratio</div>
            </div>
            <div>
                <div class="stat-number">50+</div>
                <div class="stat-label">Extracurriculars</div>
            </div>
            <div>
                <div class="stat-number">1920</div>
                <div class="stat-label">Established</div>
            </div>
        </div>
    </div>

    <div class="quad-section" id="portals">
        <div class="section-header">
            <h2>Campus Digital Hub</h2>
            <p>Select your dedicated portal to access schedules, assignments, analytics, and communications.</p>
        </div>
        
        <div class="role-grid">
            <!-- Student Portal -->
            <a href="/login.php?role=student" class="role-card">
                <div class="role-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <h3 class="role-title">Student Portal</h3>
                <p class="role-desc">Access your course materials, submit assignments, and review academic progress.</p>
                <span class="role-link">Login to Student Hub &rarr;</span>
            </a>

            <!-- Teacher Portal -->
            <a href="/login.php?role=teacher" class="role-card">
                <div class="role-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                </div>
                <h3 class="role-title">Faculty Portal</h3>
                <p class="role-desc">Manage grading rubrics, record attendance, and communicate with your classes.</p>
                <span class="role-link">Login to Faculty Core &rarr;</span>
            </a>

            <!-- Parent Portal -->
            <a href="/login.php?role=parent" class="role-card">
                <div class="role-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3 class="role-title">Parent Portal</h3>
                <p class="role-desc">Monitor your child's performance, view reports, and directly message teachers.</p>
                <span class="role-link">Login to Parent Radar &rarr;</span>
            </a>

            <!-- Director Portal -->
            <a href="/login.php?role=director" class="role-card">
                <div class="role-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg>
                </div>
                <h3 class="role-title">Director Portal</h3>
                <p class="role-desc">Oversee institution analytics, manage tenant settings, and handle administration.</p>
                <span class="role-link">Login to Admin Console &rarr;</span>
            </a>
        </div>
    </div>

    <footer>
        <div class="footer-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right: 0.5rem;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <?php echo htmlspecialchars($brandName); ?>
        </div>
        <p style="color: var(--text-muted); font-family: 'Inter', sans-serif; font-size: 0.9rem;">
            &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. Empowering education through technology.
        </p>
    </footer>
</body>
</html>
