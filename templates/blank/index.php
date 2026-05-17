<?php 
// Ensure this is only loaded via the main router
if (!isset($schoolSite)) {
    http_response_code(403);
    exit("Direct access not allowed.");
}

$heroTitle = !empty($schoolSite['hero_title']) ? $schoolSite['hero_title'] : "Welcome to your new school portal!";
$heroSubtitle = !empty($schoolSite['hero_subtitle']) ? $schoolSite['hero_subtitle'] : "This is a blank template. You can customize it as you see fit.";
$brandName = !empty($schoolSite['name']) ? $schoolSite['name'] : "Blank School";
$themePath = !empty($schoolSite['theme_path']) ? $schoolSite['theme_path'] : 'assets/css/themes/theme1.css';
$typography = !empty($schoolSite['typography']) ? $schoolSite['typography'] : 'Inter';
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
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
        }
    </style>
</head>
<body>
    <div style="padding: 4rem; text-align: center;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem; color: var(--primary);"><?php echo htmlspecialchars($heroTitle); ?></h1>
        <p style="font-size: 1.25rem; color: var(--text-muted);"><?php echo htmlspecialchars($heroSubtitle); ?></p>
        
        <div style="margin-top: 3rem;">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-outline">Register</a>
        </div>
    </div>
</body>
</html>
