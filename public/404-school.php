<?php
// Custom 404 page for missing schools
$mainDomain = $mainDomain ?? '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Not Found | SIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #111111;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            color: #ededed;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .landing-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2%;
            max-width: 1200px;
            width: 90%;
            margin: 0 auto;
            backdrop-filter: blur(10px);
            background-color: rgba(10, 10, 10, 0.8);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .hero-section {
            text-align: center;
            padding: 8rem 1rem;
            max-width: 800px;
            margin: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero-section h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 1rem;
            font-weight: 500;
            color: #e5e5e5;
        }

        .hero-section p {
            font-size: 1.25rem;
            color: #a1a1aa;
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        .btn-white {
            background-color: #ededed;
            color: #0a0a0a;
            padding: 0.875rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s ease;
        }

        .btn-white:hover {
            background-color: #ffffff;
        }

        .btn-outline {
            border: 1px solid #333;
            color: #a1a1aa;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            color: #fff;
            border-color: #555;
        }
    </style>
</head>
<body>
    <nav class="landing-nav">
        <div style="color: #fff; font-weight: 500; font-size: 1.25rem; text-decoration: none;">
            SIS
        </div>
        <div>
            <a href="<?php echo htmlspecialchars($mainDomain); ?>" class="btn-outline">Return Home</a>
        </div>
    </nav>

    <div class="hero-section">
        <h1>School Not Found</h1>
        <p>The school portal you are looking for does not exist or has been moved.<br>Want to create your own modern institution portal?</p>
        <a href="<?php echo htmlspecialchars($mainDomain); ?>" class="btn-white">Create Your School Portal</a>
    </div>
</body>
</html>
