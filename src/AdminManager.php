<?php

function getAllLinksForAdmin($limit, $offset, $dbConnection)
{
    $host = $_SERVER['HTTP_HOST'];
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $linksForAdmin = [];

    $query = "SELECT links.id AS link_id, links.short_code, links.original_url, users.email FROM links JOIN users ON links.user_id = users.id ORDER BY links.created_at DESC LIMIT :limit OFFSET :offset";
    $result = $dbConnection->prepare($query);
    $result->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $result->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    try {
        $result->execute();
        $arrayLinksFromDB = $result->fetchAll();
        foreach ($arrayLinksFromDB as $value) {
            $linksForAdmin[] = [
                'link_id' => $value['link_id'],
                'email' => $value['email'],
                'short_url' => $scheme . '://' . $host . '/' . $value['short_code'],
                'original_url' => $value['original_url']
            ];
        }
    } catch (PDOException $e) {
        $_SESSION['ErrorMessage'] = ["Сталась помилка, ми це вирішуємо!"];
        return [];
    }
    return $linksForAdmin;
}

function deleteLink($id, $dbConnection)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: /');
        exit;
    }

    $query = "DELETE FROM links WHERE id = :id";
    $result = $dbConnection->prepare($query);
    try {
        $result->execute(['id' => $id]);
        if ($result->rowCount() > 0) {
            $_SESSION['SuccessMessage'] = ["Посилання успішно видалено!"];
        } else {
            $_SESSION['ErrorMessage'] = ["Посилання не знайдено!"];
        }
        header('Location: /admin');
        exit;
    } catch (PDOException $e) {
        $_SESSION['ErrorMessage'] = ["Помилка при видаленні!"];
        header('Location: /admin');
        exit;
    }
}

function getTotalLinksCount($dbConnection)
{
    $query = "SELECT COUNT(*) AS count_links FROM links";
    $result = $dbConnection->query($query);
    return $result->fetchColumn();
}