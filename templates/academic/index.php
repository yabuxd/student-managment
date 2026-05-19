<?php 
// Ensure this is only loaded via the main router
if (!isset($schoolSite)) {
    http_response_code(403);
    exit("Direct access not allowed.");
}

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : "Tradition meets Technology";
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "Providing a comprehensive digital campus experience for our esteemed faculty, dedicated students, and involved parents.";
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
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
            background-color: #fcfcfc;
            color: #2c3e50;
        }
        .academic-nav {
            border-bottom: 1px solid #e0e0e0;
            background: #fff;
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .academic-btn {
            background-color: var(--bg-card);
            color: #fff;
            padding: 0.75rem 2rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.85rem;
            text-decoration: none;
            transition: background-color 0.3s;
            border: 1px solid var(--primary);
        }
        .academic-btn:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .academic-btn-outline {
            background-color: transparent;
            color: var(--primary);
            padding: 0.75rem 2rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.3s;
            border: 1px solid var(--primary);
        }
        .academic-btn-outline:hover {
            background-color: var(--primary);
            color: #fff;
        }
        .academic-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 3rem 2rem;
            text-align: center;
            transition: transform 0.3s;
            border-top: 4px solid var(--primary);
        }
        .academic-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .hero-section {
            text-align: center;
            padding: 8rem 5% 6rem;
            background: linear-gradient(to bottom, #fcfcfc, #f4f6f8);
            border-bottom: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <nav class="academic-nav">
        <a href="#" style="text-decoration: none; color: inherit; display: flex; align-items: center; font-size: 1.25rem; font-weight: 700;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right: 0.5rem;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <?php echo htmlspecialchars($brandName); ?>
        </a>
        <div style="display: flex; gap: 1.5rem; align-items: center;">
            <a href="login.php" style="text-decoration: none; color: #2c3e50; font-size: 0.9rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Faculty / Student Login</a>
            <a href="register.php" class="academic-btn">Admissions</a>
        </div>
    </nav>

    <div class="hero-section">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="margin-bottom: 2rem; color: var(--primary);"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
            <h1 style="font-size: 3.5rem; margin-bottom: 1.5rem; color: #2c3e50; font-weight: 700; line-height: 1.2;"><?php echo htmlspecialchars($heroTitle); ?></h1>
            <p style="font-size: 1.25rem; color: #546e7a; max-width: 700px; margin: 0 auto 3rem; line-height: 1.6; font-style: italic; font-weight: 400;"><?php echo htmlspecialchars($heroSubtitle); ?></p>
            <div style="display: flex; justify-content: center; gap: 1.5rem;">
                <a href="login.php" class="academic-btn" style="padding: 1rem 2.5rem;">Access Campus Portal</a>
                <a href="register.php" class="academic-btn-outline" style="padding: 1rem 2.5rem;">View Admissions</a>
            </div>
        </div>
    </div>

    <div class="container" style="padding: 6rem 5%; max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
            <div class="academic-card">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5" style="margin-bottom: 1.5rem;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                <h3 style="margin-top: 0; font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: #2c3e50;">Academic Excellence</h3>
                <p style="color: #546e7a; font-size: 1rem; line-height: 1.6; font-family: 'Inter', sans-serif;">Students can review their academic progress, transcripts, and upcoming examinations in a secure environment.</p>
            </div>
            <div class="academic-card">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5" style="margin-bottom: 1.5rem;"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                <h3 style="margin-top: 0; font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: #2c3e50;">Faculty Resources</h3>
                <p style="color: #546e7a; font-size: 1rem; line-height: 1.6; font-family: 'Inter', sans-serif;">Professors and teachers have full access to course management, grading rubrics, and attendance tracking.</p>
            </div>
            <div class="academic-card">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5" style="margin-bottom: 1.5rem;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <h3 style="margin-top: 0; font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: #2c3e50;">Parent Portal</h3>
                <p style="color: #546e7a; font-size: 1rem; line-height: 1.6; font-family: 'Inter', sans-serif;">Stay informed about your child's academic journey with detailed reports and direct faculty communication.</p>
            </div>
        </div>
    </div>
</body>
</html>
