<?php
/**
 * HRFlowSn - Main Entry Point
 * Point d'entrée principal de l'application
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
require_once __DIR__ . '/includes/session.php';

// Autoload des contrôleurs
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Router simple
$route = $_GET['route'] ?? 'auth/login';

// Séparer la route en contrôleur et méthode
$parts = explode('/', $route);
$controllerName = ucfirst($parts[0]) . 'Controller';
$method = $parts[1] ?? 'index';

// Vérifier si le contrôleur existe
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    // Si la route est plurielle (ex: employees), essayer le singulier (ex: EmployeeController)
    $singularRoute = rtrim($parts[0], 's');
    $singularControllerName = ucfirst($singularRoute) . 'Controller';
    $singularControllerFile = __DIR__ . '/controllers/' . $singularControllerName . '.php';
    if (file_exists($singularControllerFile)) {
        $controllerName = $singularControllerName;
        $controllerFile = $singularControllerFile;
    }
}

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            // Méthode non trouvée, rediriger vers le dashboard
            header('Location: /HRFlowSn/index.php?route=dashboard');
            exit();
        }
    } else {
        // Classe non trouvée, rediriger vers le dashboard
        header('Location: /HRFlowSn/index.php?route=dashboard');
        exit();
    }
} else {
    // Contrôleur non trouvé, rediriger vers le dashboard
    header('Location: /HRFlowSn/index.php?route=dashboard');
    exit();
}
