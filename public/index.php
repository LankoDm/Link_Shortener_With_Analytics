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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        exit;
    }
    require_once __DIR__ . '/../src/UrlManager.php';
    saveUrl($db);
} elseif ($request === '/login') {
    if(isset($_SESSION['user'])){
        header('Location: /');
        exit;
    }
    require_once __DIR__ . '/../views/login.php';
} elseif ($request === '/register') {
    if(isset($_SESSION['user'])){
        header('Location: /');
        exit;
    }
    require_once __DIR__ . '/../views/register.php';
} elseif ($request === '/register-process') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /register');
        exit;
    }
    require_once __DIR__ . '/../src/Register.php';
    createNewUser($db);
} elseif ($request === '/login-process') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /login');
        exit;
    }
    require_once __DIR__ . '/../src/Login.php';
    loginUser($db);
} elseif ($request === '/logout') {
    require_once __DIR__ . '/../src/Login.php';
    logout();
} elseif ($request === '/delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        exit;
    }
    require_once __DIR__ . '/../src/UrlManager.php';
    deleteLinkForUser($_POST['link_id'], $_SESSION['user']['id'], $db);
} elseif ($request === '/stats') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        exit;
    }
    require_once __DIR__ . '/../src/UrlManager.php';
    $linkStats = getLinkStats($_POST['link_id'], $_SESSION['user']['id'], $db);
    require __DIR__ . '/../views/stats.php';
} elseif ($request === '/admin') {
    if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'){
        require_once __DIR__ . '/../src/AdminManager.php';
        $allLinks = getAllLinksForAdmin($db);
        require_once __DIR__ . '/../views/admin.php';
        getAllLinksForAdmin($db);
    }else{
        header('Location: /');
        exit;
    }
} else {
    $shortCode = ltrim($request, '/');
    require_once __DIR__ . '/../src/UrlManager.php';
    redirectByShortCode($db, $shortCode);
}