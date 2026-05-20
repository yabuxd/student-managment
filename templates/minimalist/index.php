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
    <title><?php echo htmlspecialchars($brandName); ?> | Welcome</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root {
            --primary: <?php echo !empty($schoolSite['primary_color']) ? htmlspecialchars($schoolSite['primary_color']) : '#0071e3'; ?>;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background: var(--bg-color, #fbfbfd);
            color: var(--text-color, #1d1d1f);
        }
        .minimal-btn {
            background: var(--primary);
            color: #fff;
            border-radius: 980px;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 400;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            border: 1px solid transparent;
        }
        .minimal-btn:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }
        .minimal-btn-outline {
            background: transparent;
            color: var(--text-color, #1d1d1f);
            border: 1px solid var(--border-color, #d2d2d7);
            border-radius: 980px;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 400;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .minimal-btn-outline:hover {
            background: var(--bg-card, #f5f5f7);
        }
        .minimal-glass {
            background: var(--bg-card, rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color, rgba(255, 255, 255, 0.5));
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.04);
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .minimal-glass:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--accent-1, #42a1f5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="container" style="max-width: 1100px; margin: 0 auto; padding: 0 1.5rem;">
        <nav style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; margin-bottom: 2rem;">
            <a href="#" class="nav-brand" style="text-decoration: none; color: inherit; font-size: 1.2rem; font-weight: 600; letter-spacing: -0.02em;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.5rem; color: var(--primary);"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <?php echo htmlspecialchars($brandName); ?>
            </a>
            <div class="nav-links" style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="login.php" style="text-decoration: none; color: inherit; font-size: 0.9rem; font-weight: 500;">Login</a>
                <a href="register.php" class="minimal-btn">Get Started</a>
            </div>
        </nav>

        <div style="text-align: center; margin-top: 8rem; max-width: 800px; margin-left: auto; margin-right: auto; position: relative;">
            <h1 style="font-size: 4.5rem; line-height: 1.1; margin-bottom: 1.5rem; font-weight: 700; letter-spacing: -0.04em; color: var(--text-color, #1d1d1f);"><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p style="color: var(--text-muted, #86868b); font-size: 1.5rem; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.4; font-weight: 400; letter-spacing: -0.015em;"><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <div style="display: flex; justify-content: center; gap: 1rem;">
                <a href="login.php" class="minimal-btn" style="padding: 1rem 2rem; font-size: 1.1rem;">Access Portal</a>
                <a href="register.php" class="minimal-btn-outline" style="padding: 1rem 2rem; font-size: 1.1rem;">Explore Features</a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 10rem; margin-bottom: 8rem;">
            <div class="minimal-glass text-center" style="text-align: center;">
                <div class="stat-icon" style="font-size: 2.5rem; margin: 0 auto 1.5rem;">🎓</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600; letter-spacing: -0.02em; color: var(--text-color, #1d1d1f);">For Students</h3>
                <p style="color: var(--text-muted, #86868b); line-height: 1.6; font-size: 1.05rem;">Access grades, assignments, and class schedules instantly in a distraction-free environment.</p>
            </div>
            <div class="minimal-glass text-center" style="text-align: center;">
                <div class="stat-icon" style="font-size: 2.5rem; margin: 0 auto 1.5rem;">👨‍🏫</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600; letter-spacing: -0.02em; color: var(--text-color, #1d1d1f);">For Teachers</h3>
                <p style="color: var(--text-muted, #86868b); line-height: 1.6; font-size: 1.05rem;">Manage classes, upload resources, and track attendance easily with powerful administrative tools.</p>
            </div>
            <div class="minimal-glass text-center" style="text-align: center;">
                <div class="stat-icon" style="font-size: 2.5rem; margin: 0 auto 1.5rem;">👨‍👩‍👧</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600; letter-spacing: -0.02em; color: var(--text-color, #1d1d1f);">For Parents</h3>
                <p style="color: var(--text-muted, #86868b); line-height: 1.6; font-size: 1.05rem;">Stay deeply involved with real-time updates and analytics on your child's academic progress.</p>
            </div>
        </div>
    </div>
</body>
</html>
