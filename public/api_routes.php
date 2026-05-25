<?php
// API ROUTING HANDLER

// Ensure necessary variables are available
if (!isset($db) || !isset($subdomain)) {
    http_response_code(500);
    exit("Internal Server Error: Missing dependencies.");
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriArray = explode('/', $uri);
$apiIndex = array_search('api', $uriArray);

if ($apiIndex !== false && isset($uriArray[$apiIndex + 1])) {
    // It's an API request
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // Decode mock Bearer token for user context
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? $headers['Authorization'] ?? $headers['authorization'] ?? '';
    $userContext = null;
    if (preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
        $decoded = base64_decode($matches[1]);
        if ($decoded) {
            $userContext = json_decode($decoded, true);
        }
    }

    // Attempt to resolve school ID from subdomain
    $activeSchoolId = null;
    if (!empty($subdomain)) {
        $stmt = $db->prepare("SELECT id FROM schools WHERE subdomain = :subdomain");
        $stmt->bindParam(':subdomain', $subdomain);
        $stmt->execute();
        $activeSchoolId = $stmt->fetchColumn() ?: null;
    }

    $resource = $uriArray[$apiIndex + 1];
    $action = $uriArray[$apiIndex + 2] ?? null;
    $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

    switch ($resource) {
        case 'auth':
            $controller = new App\Controllers\AuthController($db);
            if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->register($data));
            } elseif ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->login($data, $activeSchoolId));
            }
            break;

        case 'student':
            if (!$userContext || $userContext['role'] !== 'student') {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized access."]);
                break;
            }
            $controller = new App\Controllers\PortalController($db);
            $studentId = $userContext['user_id'];
            if ($action === 'courses') {
                echo json_encode($controller->getStudentCourses($studentId));
            } elseif ($action === 'course-grades') {
                $subjectId = $_GET['subject_id'] ?? $data['subject_id'] ?? null;
                echo json_encode($controller->getStudentCourseGrades($studentId, $subjectId));
            } elseif ($action === 'final-evaluation') {
                echo json_encode($controller->getStudentFinalEvaluation($studentId));
            }
            break;

        case 'teacher':
            if (!$userContext || $userContext['role'] !== 'teacher') {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized access."]);
                break;
            }
            $controller = new App\Controllers\PortalController($db);
            $teacherId = $userContext['user_id'];
            if ($action === 'classes') {
                echo json_encode($controller->getTeacherClasses($teacherId));
            } elseif ($action === 'class-students') {
                $sectionId = $_GET['section_id'] ?? $data['section_id'] ?? null;
                echo json_encode($controller->getTeacherClassStudents($sectionId));
            } elseif ($action === 'assessments') {
                $assignmentId = $_GET['assignment_id'] ?? $data['assignment_id'] ?? null;
                echo json_encode($controller->getAssessments($assignmentId, $activeSchoolId));
            } elseif ($action === 'create-assessment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->createAssessment($data, $activeSchoolId));
            } elseif ($action === 'submit-grades' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->submitGrades($data));
            } elseif ($action === 'homeroom-roster') {
                $sectionId = $_GET['section_id'] ?? $data['section_id'] ?? null;
                echo json_encode($controller->getHomeroomClassRoster($sectionId, $teacherId));
            } elseif ($action === 'submit-evaluations' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->submitHomeroomEvaluations($data, $teacherId));
            }
            break;

        case 'director':
            if (!$userContext || $userContext['role'] !== 'director') {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized access."]);
                break;
            }
            $controller = new App\Controllers\DirectorPortalController($db);
            if ($action === 'stats') {
                echo json_encode($controller->getStats($activeSchoolId));
            } elseif ($action === 'assignment-data') {
                echo json_encode($controller->getAssignmentData($activeSchoolId));
            } elseif ($action === 'assign-teacher' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->assignTeacherSubject($data, $activeSchoolId));
            } elseif ($action === 'remove-assignment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $assignmentId = $data['assignment_id'] ?? null;
                echo json_encode($controller->removeTeacherAssignment($assignmentId));
            } elseif ($action === 'assign-homeroom' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->assignHomeroomTeacher($data));
            } elseif ($action === 'toggle-final-assessment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->toggleFinalAssessmentMode($data, $activeSchoolId));
            } elseif ($action === 'student-sectioning-data') {
                echo json_encode($controller->getStudentSectioningData($activeSchoolId));
            } elseif ($action === 'assign-student-section' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->assignStudentSection($data));
            } elseif ($action === 'random-sectioning' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->randomSectioning($data, $activeSchoolId));
            } elseif ($action === 'parents-list') {
                echo json_encode($controller->getParentsList($activeSchoolId));
            } elseif ($action === 'create-parent' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->createParentAndLink($data, $activeSchoolId));
            } elseif ($action === 'create-user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->createSingleUser($data, $activeSchoolId));
            } elseif ($action === 'subjects') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    echo json_encode($controller->getSubjectsList($activeSchoolId));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo json_encode($controller->addSubject($data, $activeSchoolId));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    echo json_encode($controller->editSubject($data, $activeSchoolId));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                    echo json_encode($controller->deleteSubject($data, $activeSchoolId));
                }
            } elseif ($action === 'terms') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    echo json_encode($controller->getTermsList($activeSchoolId));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo json_encode($controller->configureTermSystem($data, $activeSchoolId));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    echo json_encode($controller->setActiveTerm($data, $activeSchoolId));
                }
            }
            break;

        case 'communications':
            if (!$userContext) {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized access."]);
                break;
            }
            $controller = new App\Controllers\CommunicationController($db);
            $userId = $userContext['user_id'];
            $role = $userContext['role'];
            if ($action === 'list') {
                echo json_encode($controller->getMessages($role, $userId, $activeSchoolId));
            } elseif ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->sendMessage($data, $activeSchoolId, $role, $userId));
            }
            break;

        case 'schools':
            $controller = new App\Controllers\SchoolController($db);
            if ($action === 'plans' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                echo json_encode($controller->getPlans());
            } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $directorId = $data['director_id'] ?? null; 
                echo json_encode($controller->createSchool($data, $directorId));
            } elseif ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                $directorId = $_GET['director_id'] ?? null;
                echo json_encode($controller->getSchools($directorId));
            } elseif ($action === 'save-page' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->saveCustomPage($data));
            } elseif ($action === 'save-settings' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($controller->saveSettings($data));
            }
            break;

        case 'users':
            if ($action === 'mass-register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new App\Controllers\RegistrationController($db);
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
