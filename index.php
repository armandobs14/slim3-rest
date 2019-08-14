<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

# === constants
# ==================================================
define("_APP", dirname(__FILE__) . '/app');

# === slim
# ==================================================
require 'vendor/autoload.php';
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With,X-auth-uid,X-auth-token, X-apiKey, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

//$app = new \Slim\Slim(array(
//  'debug' => true
//));

# === config
# ==================================================
require_once _APP . '/config/database.php';

# === routes
# ==================================================
require_once _APP . '/config/routes.yml.php';

# === helpers
# ==================================================
//require_once _APP . '/middlewares/Middleware.php';



$dirs = array(
    'models',
    'controllers',
    'middlewares',
    'helpers'
);

$classes = array();
foreach ($dirs as $dirName) {
    $files = scandir(_APP . '/' . $dirName);
    foreach ($files as $file) {
        $exploded = explode('.', $file);
        if ($exploded[1] == 'php') {
            include _APP . '/' . $dirName . '/' . $file;
        }
    }
}

# === run slim

$app->run();
