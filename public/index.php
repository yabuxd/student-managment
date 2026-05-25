<?php

// Simple Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

use App\Config\Database;
use App\Controllers\AssessmentController;
use App\Controllers\AuthController;
use App\Controllers\SchoolController;
use App\Controllers\RegistrationController;

$database = new Database();
$db = $database->getConnection();

$host = $_SERVER['HTTP_HOST'];
// remove port if exists
$host = explode(':', $host)[0];
$parts = explode('.', $host);
$subdomain = '';


// Simple subdomain detection:
// e.g. sub.sis.localhost (3 parts) -> subdomain is "sub"
// sis.localhost (2 parts) -> no subdomain
if (isset($_GET['preview_subdomain']) && !empty($_GET['preview_subdomain'])) {
    // Allows the page builder on sis.localhost to load subdomain templates in a same-origin iframe
    $subdomain = $_GET['preview_subdomain'];
} else if (count($parts) >= 3 && $parts[0] !== 'www') {
    $subdomain = $parts[0];
}

// 1. EARLY API ROUTING
// If it's an API request, handle it immediately and stop.
$_rawRequestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (strpos($_rawRequestPath, '/api/') === 0) {
    require __DIR__ . '/api_routes.php';
    exit(); // Safety exit, though api_routes.php should handle this
}

// 2. DYNAMIC SUBDOMAIN RENDERING
if (!empty($subdomain)) {
    $stmt = $db->prepare("
        SELECT s.*, c.template_name, c.theme_path, c.typography, c.hero_title, c.hero_subtitle, c.primary_color, c.logo_url, c.about_text, c.custom_pages 
        FROM schools s 
        LEFT JOIN school_site_content c ON s.id = c.school_id 
        WHERE s.subdomain = :subdomain
    ");
    $stmt->bindParam(':subdomain', $subdomain);
    $stmt->execute();
    $schoolSite = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($schoolSite) {
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // 1. Check for custom HTML override
        $custom_pages = json_decode($schoolSite['custom_pages'] ?? '{}', true);
        if (isset($custom_pages[$request_uri])) {
            header("Content-Type: text/html; charset=UTF-8");
            echo $custom_pages[$request_uri];
            exit();
        }

        // 2. Fallback to PHP templates
        $templateName = $schoolSite['template_name'] ?? 'vibrant';
        
        // Determine which PHP file to load based on the URI
        $fileToLoad = 'index.php'; // default
        if ($request_uri === '/login.php') $fileToLoad = 'login.php';
        elseif ($request_uri === '/register.php') $fileToLoad = 'register.php';
        elseif ($request_uri === '/dashboard.php') $fileToLoad = 'dashboard.php';
        
        $templatePath = dirname(__DIR__) . "/templates/" . preg_replace('/[^a-zA-Z0-9_-]/', '', $templateName) . "/" . $fileToLoad;
        
        if (file_exists($templatePath)) {
            header("Content-Type: text/html; charset=UTF-8");
            require $templatePath;
            exit();
        } else {
            http_response_code(404);
            echo "Template file not found.";
            exit();
        }
    } else {
        http_response_code(404);
        if (file_exists(__DIR__ . '/404-school.php')) {
            $mainDomain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://";
            $mainDomain .= count($parts) >= 3 ? implode('.', array_slice($parts, 1)) : $host;
            require __DIR__ . '/404-school.php';
        } else {
            echo "School site not found.";
        }
        exit();
    }
}

// 3. BASE DOMAIN ROUTING (Landing Page)
// If it's the base domain and not an API call, serve the appropriate page
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($request_uri === '/auth/login') {
    if (file_exists(__DIR__ . '/auth/login.php')) {
        header("Content-Type: text/html; charset=UTF-8");
        require __DIR__ . '/auth/login.php';
    } else {
        echo "Login page missing.";
    }
} elseif ($request_uri === '/auth/signup') {
    if (file_exists(__DIR__ . '/auth/signup.php')) {
        header("Content-Type: text/html; charset=UTF-8");
        require __DIR__ . '/auth/signup.php';
    } else {
        echo "Signup page missing.";
    }
} else {
    if (file_exists(__DIR__ . '/landing.php')) {
        header("Content-Type: text/html; charset=UTF-8");
        require __DIR__ . '/landing.php';
    } else {
        echo "Landing page missing.";
    }
}
