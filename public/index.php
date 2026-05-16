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
if (count($parts) >= 3 && $parts[0] !== 'www') {
    $subdomain = $parts[0];
}

// 1. DYNAMIC SUBDOMAIN RENDERING
if (!empty($subdomain)) {
    $stmt = $db->prepare("
        SELECT s.*, c.template_name, c.hero_title, c.hero_subtitle, c.primary_color, c.logo_url, c.about_text 
        FROM schools s 
        LEFT JOIN school_site_content c ON s.id = c.school_id 
        WHERE s.subdomain = :subdomain
    ");
    $stmt->bindParam(':subdomain', $subdomain);
    $stmt->execute();
    $schoolSite = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($schoolSite) {
        // Expose $schoolSite to the template
        $templateName = $schoolSite['template_name'] ?? 'vibrant';
        $templatePath = dirname(__DIR__) . "/templates/" . preg_replace('/[^a-zA-Z0-9_-]/', '', $templateName) . "/index.php";
        
        if (file_exists($templatePath)) {
            header("Content-Type: text/html; charset=UTF-8");
            require $templatePath;
            exit();
        } else {
            http_response_code(404);
            echo "Template not found.";
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

// 2. API ROUTING
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriArray = explode('/', $uri);
$apiIndex = array_search('api', $uriArray);

if ($apiIndex !== false && isset($uriArray[$apiIndex + 1])) {
    // It's an API request
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    $resource = $uriArray[$apiIndex + 1];
    $action = $uriArray[$apiIndex + 2] ?? null;

    switch ($resource) {
        case 'assessments':
            $controller = new AssessmentController($db);
            if ($action === 'record' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
                if (isset($data['assessment_id'], $data['student_id'], $data['score'])) {
                    if ($controller->recordScore($data['assessment_id'], $data['student_id'], $data['score'])) {
                        echo json_encode(["message" => "Score recorded successfully."]);
                    } else {
                        echo json_encode(["message" => "Failed to record score."]);
                    }
                } else {
                    echo json_encode(["message" => "Incomplete data."]);
                }
            } elseif ($action === 'report' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                $studentID = $_GET['student_id'] ?? null;
                $termID = $_GET['term_id'] ?? null;
                if ($studentID && $termID) {
                    echo json_encode($controller->getStudentReport($studentID, $termID));
                } else {
                    echo json_encode(["message" => "Missing parameters."]);
                }
            } elseif ($action === 'performance' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                $sectionID = $_GET['section_id'] ?? null;
                $termID = $_GET['term_id'] ?? null;
                if ($sectionID && $termID) {
                    echo json_encode($controller->getSectionPerformance($sectionID, $termID));
                } else {
                    echo json_encode(["message" => "Missing parameters."]);
                }
            }
            break;

        case 'auth':
            $controller = new AuthController($db);
            $data = json_decode(file_get_contents("php://input"), true);
            if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->register($data));
            } elseif ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->login($data));
            }
            break;

        case 'schools':
            $controller = new SchoolController($db);
            if ($action === 'plans' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                echo json_encode($controller->getPlans());
            } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                $directorId = $data['director_id'] ?? null; 
                echo json_encode($controller->createSchool($data, $directorId));
            } elseif ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                $directorId = $_GET['director_id'] ?? null;
                echo json_encode($controller->getSchools($directorId));
            }
            break;

        case 'users':
            if ($action === 'mass-register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new RegistrationController($db);
                $role = $_POST['role'] ?? 'student';
                $schoolId = $_POST['school_id'] ?? null;
                
                if (isset($_FILES['file']) && $schoolId) {
                    echo json_encode($controller->processCsv($_FILES['file']['tmp_name'], $role, $schoolId));
                } else {
                    echo json_encode(["success" => false, "message" => "Missing file or school ID."]);
                }
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(["message" => "Resource not found."]);
            break;
    }
    exit();
}

// 3. BASE DOMAIN ROUTING (Landing Page)
// If it's the base domain and not an API call, serve the landing page
if (file_exists(__DIR__ . '/landing.php')) {
    header("Content-Type: text/html; charset=UTF-8");
    require __DIR__ . '/landing.php';
} else {
    echo "Landing page missing.";
}
