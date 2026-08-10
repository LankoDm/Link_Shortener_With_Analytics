<?php

session_start();

require_once __DIR__ . '/../src/Database.php';

$db = connectDB();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($request === '/' || $request === '/index.php') {
    $userLinks = [];

    if (isset($_SESSION['user']['id'])) {
        require_once __DIR__ . '/../src/UrlManager.php';
        $userLinks = generateShortUrlForUser($db);
    }

    require_once __DIR__ . '/../views/home.php';
} elseif ($request === '/save') {
    require_once __DIR__ . '/../src/UrlManager.php';
    saveUrl($db);
} elseif ($request === '/login') {
    require_once __DIR__ . '/../views/login.php';
} elseif ($request === '/register') {
    require_once __DIR__ . '/../views/register.php';
} elseif ($request === '/register-process') {
    require_once __DIR__ . '/../src/Register.php';
    createNewUser($db);
} elseif ($request === '/login-process') {
    require_once __DIR__ . '/../src/Login.php';
    loginUser($db);
} elseif ($request === '/logout') {
    require_once __DIR__ . '/../src/Login.php';
    logout();
} elseif ($request === '/delete') {
    require_once __DIR__ . '/../src/UrlManager.php';
    deleteLinkForUser($_POST['link_id'], $_SESSION['user']['id'], $db);
} else {
    $shortCode = ltrim($request, '/');
    require_once __DIR__ . '/../src/UrlManager.php';
    redirectByShortCode($db, $shortCode);
}