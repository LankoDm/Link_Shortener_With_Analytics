<?php

function getScheme()
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
}

function saveUrl($dbConnection)
{
    $originalUrl = $_POST['original_url'];
    if (!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
        $_SESSION['ErrorMessage'] = ["Будь ласка, введіть коректне посилання!"];
        header("Location: /");
        exit;
    }

    do {
        $shortCode = bin2hex(random_bytes(5));
        $sql = "SELECT COUNT(*) FROM links WHERE short_code = :short_code";
        $result = $dbConnection->prepare($sql);
        $result->execute(['short_code' => $shortCode]);
    } while ($result->fetchColumn() > 0);

    $query = "INSERT INTO links (user_id, original_url, short_code) VALUES (:user_id, :original_url, :short_code)";

    $link = $dbConnection->prepare($query);
    try {
        $link->execute(['user_id' => $_SESSION['user']['id'], 'original_url' => $originalUrl, 'short_code' => $shortCode]);
        $_SESSION['SuccessMessage'] = ["Посилання успішно створенно!"];
        header("Location: /");
        exit;
    } catch (PDOException $e) {
        $_SESSION['ErrorMessage'] = ["Сталась помилка, ми це вирішуємо!"];
        header("Location: /");
        exit;
    }
}

function generateShortUrlForUser($limit, $offset, $dbConnection)
{
    $host = $_SERVER['HTTP_HOST'];
    $scheme = getScheme();
    $arrayCustomLinks = [];

    $query = "SELECT links.id, links.original_url, links.short_code, COUNT(clicks.id) as clicks_count FROM links LEFT JOIN clicks ON links.id = clicks.link_id WHERE links.user_id = :user_id GROUP BY links.id ORDER BY links.created_at DESC LIMIT :limit OFFSET :offset";

    $result = $dbConnection->prepare($query);
    $result->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $result->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $result->bindValue(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
    $result->execute();
    $arrayLinksFromDB = $result->fetchAll();

    foreach ($arrayLinksFromDB as $value) {
        $arrayCustomLinks[] = [
            'link_id' => $value['id'],
            'short_url' => $scheme . '://' . $host . '/' . $value['short_code'],
            'original_url' => $value['original_url'],
            'clicks_count' => $value['clicks_count']
        ];
    }

    return $arrayCustomLinks;
}

function getCountLinksForUser($user_id, $dbConnection)
{
    $query = "SELECT COUNT(*) FROM links WHERE user_id = :user_id";
    $result = $dbConnection->prepare($query);
    $result->execute(['user_id' => $user_id]);
    return $result->fetchColumn();
}

function redirectByShortCode($dbConnection, $shortCode)
{
    $query = "SELECT id, original_url FROM links WHERE short_code = :short_code";
    $result = $dbConnection->prepare($query);
    $result->execute(['short_code' => $shortCode]);
    $link = $result->fetch();

    if ($link) {
        $idLinks = $link['id'];
        statsAboutClick($dbConnection, $idLinks);
        header("Location: {$link['original_url']}");
        exit;
    } else {
        $_SESSION['ErrorMessage'] = ["Сторінку не знайдено!"];
        header('Location: /');
        exit;
    }
}

function statsAboutClick($dbConnection, $id)
{
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? "NULL";
    $queryInsert = "INSERT INTO clicks (link_id, ip_address, user_agent) VALUES (:link_id, :ip_address, :user_agent)";

    $clicks = $dbConnection->prepare($queryInsert);
    $clicks->execute(['link_id' => $id, 'ip_address' => $ipAddress, 'user_agent' => $userAgent]);
}

function deleteLinkForUser($link_id, $user_id, $dbConnection)
{
    $query = "DELETE FROM links WHERE id = :id AND user_id = :user_id";
    $result = $dbConnection->prepare($query);
    try {
        $result->execute(['id' => $link_id, 'user_id' => $user_id]);
        if ($result->rowCount() > 0) {
            $_SESSION['SuccessMessage'] = ["Посилання успішно видалено!"];
        } else {
            $_SESSION['ErrorMessage'] = ["Посилання не знайдено або у вас немає прав на його видалення."];
        }
        header('Location: /');
        exit;
    } catch (PDOException $e) {
        $_SESSION['ErrorMessage'] = ["Сталась помилка, ми це вирішуємо!"];
        header('Location: /');
        exit;
    }
}

function getLinkStats($link_id, $user_id, $dbConnection)
{
    $query = "SELECT clicks.ip_address, clicks.user_agent, clicks.clicked_at FROM clicks JOIN links ON clicks.link_id = links.id WHERE clicks.link_id = :link_id AND links.user_id = :user_id ORDER BY clicks.clicked_at DESC";
    $result = $dbConnection->prepare($query);
    try {
        $result->execute(['link_id' => $link_id, 'user_id' => $user_id]);
        $fullStats = $result->fetchAll();
    } catch (PDOException $e) {
        $_SESSION['ErrorMessage'] = ["Сталась помилка, ми це вирішуємо!"];
        return [];
    }
    return $fullStats ?? [];
}
