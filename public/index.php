<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Csrf.php';

$db = connectDB();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($request === '/' || $request === '/index.php') {
    $userLinks = [];

    if (isset($_SESSION['user']['id'])) {
        require_once __DIR__ . '/../src/UrlManager.php';
        $limit = 5;
        $maxPage = ceil(getCountLinksForUser($_SESSION['user']['id'], $db) / $limit);

        if ($page < 1 || ($maxPage > 0 && $page > $maxPage)) {
            $page = 1;
        }

        $offset = $limit * ($page - 1);
        $userLinks = generateShortUrlForUser($limit, $offset, $db);
    }

    require_once __DIR__ . '/../views/home.php';
} elseif ($request === '/save') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        exit;
    }
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    validateCsrfToken();
    require_once __DIR__ . '/../src/UrlManager.php';
    saveUrl($db);
} elseif ($request === '/login') {
    if (isset($_SESSION['user'])) {
        header('Location: /');
        exit;
    }
    require_once __DIR__ . '/../views/login.php';
} elseif ($request === '/register') {
    if (isset($_SESSION['user'])) {
        header('Location: /');
        exit;
    }
    require_once __DIR__ . '/../views/register.php';
} elseif ($request === '/register-process') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /register');
        exit;
    }
    validateCsrfToken();
    require_once __DIR__ . '/../src/Register.php';
    createNewUser($db);
} elseif ($request === '/login-process') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /login');
        exit;
    }
    validateCsrfToken();
    require_once __DIR__ . '/../src/Login.php';
    loginUser($db);
} elseif ($request === '/logout') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        exit;
    }
    validateCsrfToken();
    require_once __DIR__ . '/../src/Login.php';
    logout();
} elseif ($request === '/delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        exit;
    }
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    validateCsrfToken();
    require_once __DIR__ . '/../src/UrlManager.php';
    deleteLinkForUser($_POST['link_id'], $_SESSION['user']['id'], $db);
} elseif ($request === '/stats') {
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    $linkId = isset($_GET['link_id']) ? (int)$_GET['link_id'] : 0;
    require_once __DIR__ . '/../src/UrlManager.php';
    $linkStats = getLinkStats($linkId, $_SESSION['user']['id'], $db);
    require __DIR__ . '/../views/stats.php';
} elseif ($request === '/admin') {
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
        require_once __DIR__ . '/../src/AdminManager.php';
        $limit = 5;
        $maxPage = ceil(getTotalLinksCount($db) / $limit);

        if ($page < 1 || ($maxPage > 0 && $page > $maxPage)) {
            $page = 1;
        }

        $offset = $limit * ($page - 1);

        $allLinks = getAllLinksForAdmin($limit, $offset, $db);

        require_once __DIR__ . '/../views/admin.php';
    } else {
        header('Location: /');
        exit;
    }
} elseif ($request === '/admin-delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /');
        exit;
    }
    validateCsrfToken();
    require_once __DIR__ . '/../src/AdminManager.php';
    deleteLink($_POST['link_id'], $db);
} else {
    $shortCode = ltrim($request, '/');
    require_once __DIR__ . '/../src/UrlManager.php';
    redirectByShortCode($db, $shortCode);
}