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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Welcome</title>
    <!-- Google Fonts Typography -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', serif;
        }
    </style>
</head>
<body>
    <nav>
        <a href="#" class="nav-brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <?php echo htmlspecialchars($brandName); ?>
        </a>
        <div class="nav-links">
            <a href="login.php">Faculty / Student Login</a>
            <a href="register.php" class="btn btn-primary" style="color: #fff; padding: 0.5rem 1rem;">Admissions</a>
        </div>
    </nav>

    <div class="container" style="text-align: center; padding: 6rem 5% 4rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1.5rem; color: var(--primary);"><?php echo htmlspecialchars($heroTitle); ?></h1>
        <p style="font-size: 1.2rem; color: var(--text-muted); max-width: 700px; margin: 0 auto 3rem;"><?php echo htmlspecialchars($heroSubtitle); ?></p>
        <a href="login.php" class="btn btn-primary" style="width: auto; padding: 1rem 2.5rem;">Access Campus Portal</a>
    </div>

    <div class="container">
        <div class="dashboard-grid">
            <div class="card" style="text-align: center;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" style="margin-bottom: 1rem;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                <h3 style="margin-top: 0;">Academic Excellence</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Students can review their academic progress, transcripts, and upcoming examinations in a secure environment.</p>
            </div>
            <div class="card" style="text-align: center;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" style="margin-bottom: 1rem;"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                <h3 style="margin-top: 0;">Faculty Resources</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Professors and teachers have full access to course management, grading rubrics, and attendance tracking.</p>
            </div>
            <div class="card" style="text-align: center;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" style="margin-bottom: 1rem;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <h3 style="margin-top: 0;">Parent Portal</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Stay informed about your child's academic journey with detailed reports and direct faculty communication.</p>
            </div>
        </div>
    </div>
</body>
</html>
