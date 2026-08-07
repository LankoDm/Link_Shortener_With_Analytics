<?php

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

    $query = "INSERT INTO links (user_id, original_url, short_code) VALUE (:user_id, :original_url, :short_code)";

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

function generateShortUrlForUser($dbConnection)
{
    $host = $_SERVER['HTTP_HOST'];
    $arrayCustomLinks = [];

    $query = "SELECT * FROM links WHERE user_id = :user_id";

    $result = $dbConnection->prepare($query);
    $result->execute(['user_id' => $_SESSION['user']['id']]);
    $arrayLinksFromDB = $result->fetchAll();

    foreach ($arrayLinksFromDB as $value) {
        $arrayCustomLinks[] =
            ['short_url' => 'http://' . $host . '/' . $value['short_code'],
                'original_url' => $value['original_url']];
    }

    return $arrayCustomLinks;
}

function redirectByShortCode($dbConnection, $shortCode)
{
    $query = "SELECT * FROM links WHERE short_code = :short_code";
    $result = $dbConnection->prepare($query);
    $result->execute(['short_code' => $shortCode]);
    $link = $result->fetch();

    if ($link) {
        header("Location: {$link['original_url']}");
        exit;
    } else {
        echo "Такой страницы не существует";
    }
}