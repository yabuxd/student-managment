<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Simple Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir =dirname(__DIR__) . '/src/';
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

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Simple Router
// Expected format: /school%20managment%20System/api/{resource}/{action}
// Adjusting for the workspace folder name in XAMPP
$apiIndex = array_search('api', $uri);

if (!$apiIndex || !isset($uri[$apiIndex + 1])) {
    http_response_code(404);
    echo json_encode(["message" => "Endpoint not found."]);
    exit();
}

$resource = $uri[$apiIndex + 1];
$action = $uri[$apiIndex + 2] ?? null;

switch ($resource) {
    case 'assessments':
        $controller = new AssessmentController($db);
        
        if ($action === 'record' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php_input"), true);
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
            // In a real app, verify the authorization token here
            $directorId = $data['director_id'] ?? null; 
            echo json_encode($controller->createSchool($data, $directorId));
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
