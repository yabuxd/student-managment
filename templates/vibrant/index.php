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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brandName); ?> | Portal</title>
    <!-- Use absolute path from document root to serve assets correctly regardless of wildcard subdomain routing -->
    <link rel="stylesheet" href="/templates/<?php echo htmlspecialchars($templateName); ?>/assets/css/style.css">
    <style>
        :root {
            --border-dark: <?php echo htmlspecialchars($primaryColor); ?>;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="#" class="nav-brand">⚡ <?php echo htmlspecialchars($brandName); ?></a>
            <div class="nav-links">
                <a href="/login.php">Login</a>
                <a href="/register.php" class="btn btn-outline" style="padding: 0.5rem 1rem;">Start Free</a>
            </div>
        </nav>

        <div style="text-align: center; margin-top: 6rem; position: relative;">
            <div style="position: absolute; top: -2rem; left: 10%; background: var(--accent-cyan); padding: 0.5rem 1rem; border: 3px solid var(--border-dark); transform: rotate(-10deg); font-weight: 800; text-transform: uppercase;">Radical Education</div>
            
            <h1 style="font-size: 6rem; line-height: 0.9; margin-bottom: 2rem;">
                <?php echo htmlspecialchars($heroTitle); ?>
            </h1>
            <p style="font-size: 1.5rem; font-weight: 600; max-width: 700px; margin: 0 auto 3rem; line-height: 1.4;">
                <?php echo htmlspecialchars($heroSubtitle); ?>
            </p>
            <div style="display: flex; justify-content: center; gap: 1.5rem;">
                <a href="/login.php" class="btn btn-primary" style="width: auto;">Enter Portal</a>
                <a href="/register.php" class="btn btn-outline" style="width: auto; background: var(--accent-coral);">Get Enrolled</a>
            </div>
        </div>

        <div class="marquee-container" style="margin-top: 8rem; margin-bottom: 6rem;">
            <div class="marquee-content">
                INNOVATIVE LEARNING / RADICAL TRANSPARENCY / ABSOLUTE CONTROL / SEAMLESS EXPERIENCE / INNOVATIVE LEARNING / RADICAL TRANSPARENCY / ABSOLUTE CONTROL / SEAMLESS EXPERIENCE /
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="brutal-card" style="background: var(--accent-cyan);">
                <h3 style="font-size: 2rem;">Student Hub</h3>
                <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.4;">Stop guessing. Know your grades, track your assignments, and crush your goals.</p>
            </div>
            <div class="brutal-card" style="background: var(--accent-yellow);">
                <h3 style="font-size: 2rem;">Teacher Core</h3>
                <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.4;">Spend less time doing paperwork and more time doing what you do best: Teaching.</p>
            </div>
            <div class="brutal-card" style="background: var(--accent-coral);">
                <h3 style="font-size: 2rem;">Parent Radar</h3>
                <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.4;">Keep your finger on the pulse of your child's education. No surprises.</p>
            </div>
        </div>
    </div>
</body>
</html>
