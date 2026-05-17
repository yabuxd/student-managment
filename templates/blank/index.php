<?php 
if (!isset($schoolSite)) { http_response_code(403); exit("Direct access not allowed."); }

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : "Inspiring the Next Generation";
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "A complete ecosystem for modern education, bringing students, teachers, and parents together.";
$brandName = !empty($schoolSite['name']) ? $schoolSite['name'] : "Global Academy";
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Outfit';
$metaDescription = !empty($schoolSite['meta_description']) ? $schoolSite['meta_description'] : $heroSubtitle;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Welcome</title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            overflow-x: hidden;
        }
        .enterprise-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 5%;
            position: fixed;
            top: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 1000;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .enterprise-btn {
            background: #fff;
            color: #000;
            padding: 0.75rem 1.5rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: transform 0.3s, box-shadow 0.3s;
            display: inline-block;
        }
        .enterprise-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(255,255,255,0.2);
        }
        .hero-banner {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }
        .hero-banner-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            opacity: 0.4;
            z-index: -1;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; width: 100%; height: 50%;
            background: linear-gradient(to top, #000, transparent);
            z-index: -1;
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
            padding: 5rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }
        .bento-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1.5rem;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            transition: background 0.3s;
        }
        .bento-item:hover {
            background: rgba(255,255,255,0.05);
        }
        .bento-item.large { grid-column: span 8; }
        .bento-item.small { grid-column: span 4; }
        .bento-image-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0.3;
            transition: opacity 0.5s, transform 0.5s;
            z-index: -1;
        }
        .bento-item:hover .bento-image-bg {
            opacity: 0.5;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <nav class="enterprise-nav">
        <a href="#" style="color: #fff; text-decoration: none; font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 32px; height: 32px; background: var(--primary); border-radius: 8px;"></div>
            <?php echo htmlspecialchars($brandName); ?>
        </a>
        <div style="display: flex; gap: 1.5rem; align-items: center;">
            <a href="login.php" style="color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; font-size: 0.95rem;">Portal Login</a>
            <a href="register.php" class="enterprise-btn">Join Us</a>
        </div>
    </nav>

    <div class="hero-banner">
        <div class="hero-banner-bg"></div>
        <div style="max-width: 900px; padding: 0 2rem; position: relative; z-index: 1;">
            <div style="display: inline-block; padding: 0.5rem 1rem; border: 1px solid rgba(255,255,255,0.2); border-radius: 100px; background: rgba(255,255,255,0.1); margin-bottom: 2rem; font-size: 0.85rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);">Discover the future</div>
            <h1 style="font-size: 5rem; font-weight: 800; line-height: 1.1; margin: 0 0 1.5rem 0; letter-spacing: -0.03em;"><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p style="font-size: 1.25rem; color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto 3rem; line-height: 1.6;"><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <div style="display: flex; justify-content: center; gap: 1rem;">
                <a href="login.php" class="enterprise-btn" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Access Dashboard</a>
            </div>
        </div>
    </div>

    <div class="bento-grid">
        <div class="bento-item large" style="min-height: 400px; display: flex; flex-direction: column; justify-content: flex-end;">
            <div class="bento-image-bg" style="background-image: url('https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop');"></div>
            <div style="background: linear-gradient(to top, #000, transparent); position: absolute; bottom: 0; left: 0; width: 100%; height: 70%; z-index: -1;"></div>
            <h3 style="font-size: 2.5rem; margin: 0 0 1rem; font-weight: 700;">Student Centered</h3>
            <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem; max-width: 500px; margin: 0;">Empowering students with seamless access to resources, transparent grading, and interactive learning tools.</p>
        </div>
        <div class="bento-item small" style="min-height: 400px; display: flex; flex-direction: column; justify-content: flex-end;">
            <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: auto;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
            </div>
            <h3 style="font-size: 1.75rem; margin: 0 0 0.75rem; font-weight: 700;">Faculty Core</h3>
            <p style="color: rgba(255,255,255,0.7); font-size: 1rem; margin: 0;">Comprehensive tools for teachers to manage curriculum, grade efficiently, and communicate effortlessly.</p>
        </div>
        <div class="bento-item small" style="min-height: 300px;">
            <h3 style="font-size: 1.75rem; margin: 0 0 0.75rem; font-weight: 700;">Parent Connectivity</h3>
            <p style="color: rgba(255,255,255,0.7); font-size: 1rem; margin: 0;">Real-time insights into attendance, grades, and school updates right at your fingertips.</p>
            <a href="register.php" style="display: inline-block; margin-top: 1.5rem; color: var(--primary); text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">Join the Community <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
        <div class="bento-item large" style="min-height: 300px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; border: none; background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.01));">
            <h2 style="font-size: 3rem; margin: 0 0 1.5rem; font-weight: 800; letter-spacing: -0.02em;">Ready to transform?</h2>
            <a href="register.php" class="enterprise-btn" style="padding: 1.25rem 3rem; font-size: 1.15rem;">Start your journey</a>
        </div>
    </div>
</body>
</html>
