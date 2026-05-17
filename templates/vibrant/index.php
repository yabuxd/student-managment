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
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($typography); ?>:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Use dynamic theme path -->
    <link rel="stylesheet" href="/<?php echo htmlspecialchars($themePath); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root {
            --border-dark: <?php echo htmlspecialchars($primaryColor); ?>;
        }
        body {
            font-family: '<?php echo htmlspecialchars($typography); ?>', sans-serif;
        }
        .brutal-btn {
            border: 4px solid var(--border-dark);
            box-shadow: 6px 6px 0 var(--border-dark);
            transition: all 0.2s ease;
            font-weight: 800;
            text-transform: uppercase;
        }
        .brutal-btn:hover {
            transform: translate(2px, 2px);
            box-shadow: 4px 4px 0 var(--border-dark);
        }
        .brutal-btn:active {
            transform: translate(6px, 6px);
            box-shadow: 0 0 0 var(--border-dark);
        }
        .brutal-card {
            border: 4px solid var(--border-dark);
            box-shadow: 8px 8px 0 var(--border-dark);
            transition: transform 0.2s;
        }
        .brutal-card:hover {
            transform: translate(-4px, -4px);
            box-shadow: 12px 12px 0 var(--border-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <nav style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 4px solid var(--border-dark); margin-bottom: 2rem;">
            <a href="#" style="font-size: 1.5rem; font-weight: 900; text-decoration: none; color: inherit; text-transform: uppercase;">⚡ <?php echo htmlspecialchars($brandName); ?></a>
            <div style="display: flex; gap: 1rem;">
                <a href="/login.php" style="font-weight: 700; text-decoration: none; color: inherit; align-self: center;">LOGIN</a>
                <a href="/register.php" class="brutal-btn" style="padding: 0.5rem 1rem; background: #fff; color: #000; text-decoration: none; display: inline-block;">START FREE</a>
            </div>
        </nav>

        <div style="text-align: center; margin-top: 6rem; position: relative;">
            <div style="position: absolute; top: -2rem; left: 10%; background: var(--accent-cyan); padding: 0.5rem 1rem; border: 4px solid var(--border-dark); transform: rotate(-10deg); font-weight: 900; text-transform: uppercase; box-shadow: 4px 4px 0 var(--border-dark);">Radical Education</div>
            
            <h1 style="font-size: 6rem; line-height: 0.9; margin-bottom: 2rem; text-transform: uppercase; letter-spacing: -0.05em; font-weight: 900;">
                <?php echo htmlspecialchars($heroTitle); ?>
            </h1>
            <p style="font-size: 1.5rem; font-weight: 600; max-width: 700px; margin: 0 auto 3rem; line-height: 1.4;">
                <?php echo htmlspecialchars($heroSubtitle); ?>
            </p>
            <div style="display: flex; justify-content: center; gap: 1.5rem;">
                <a href="/login.php" class="brutal-btn" style="padding: 1rem 2rem; background: var(--border-dark); color: #fff; text-decoration: none; display: inline-block; font-size: 1.2rem;">ENTER PORTAL</a>
                <a href="/register.php" class="brutal-btn" style="padding: 1rem 2rem; background: var(--accent-coral); color: #000; text-decoration: none; display: inline-block; font-size: 1.2rem;">GET ENROLLED</a>
            </div>
        </div>

        <div style="margin-top: 8rem; margin-bottom: 6rem; border-top: 4px solid var(--border-dark); border-bottom: 4px solid var(--border-dark); padding: 1rem 0; background: var(--accent-yellow); overflow: hidden; white-space: nowrap;">
            <div style="display: inline-block; animation: marquee 20s linear infinite; font-weight: 900; font-size: 1.5rem;">
                INNOVATIVE LEARNING // RADICAL TRANSPARENCY // ABSOLUTE CONTROL // SEAMLESS EXPERIENCE // INNOVATIVE LEARNING // RADICAL TRANSPARENCY // ABSOLUTE CONTROL // SEAMLESS EXPERIENCE //
            </div>
            <style>
                @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
            </style>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 6rem;">
            <div class="brutal-card" style="background: var(--accent-cyan); padding: 2.5rem;">
                <h3 style="font-size: 2.5rem; margin-bottom: 1rem; font-weight: 900; text-transform: uppercase;">Student Hub</h3>
                <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.4;">Stop guessing. Know your grades, track your assignments, and crush your goals.</p>
            </div>
            <div class="brutal-card" style="background: var(--accent-yellow); padding: 2.5rem;">
                <h3 style="font-size: 2.5rem; margin-bottom: 1rem; font-weight: 900; text-transform: uppercase;">Teacher Core</h3>
                <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.4;">Spend less time doing paperwork and more time doing what you do best: Teaching.</p>
            </div>
            <div class="brutal-card" style="background: var(--accent-coral); padding: 2.5rem;">
                <h3 style="font-size: 2.5rem; margin-bottom: 1rem; font-weight: 900; text-transform: uppercase;">Parent Radar</h3>
                <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.4;">Keep your finger on the pulse of your child's education. No surprises.</p>
            </div>
        </div>
    </div>
</body>
</html>
