<?php

function getAllLinksForAdmin($dbConnection)
{
    $host = $_SERVER['HTTP_HOST'];
    $linksForAdmin = [];

    $query = "SELECT links.id AS link_id, links.short_code, links.original_url, users.email FROM links JOIN users ON links.user_id = users.id ORDER BY links.created_at DESC";

    try {
        $result = $dbConnection->query($query);
        $arrayLinksFromDB = $result->fetchAll();
        foreach ($arrayLinksFromDB as $value) {
            $linksForAdmin[] = [
                'link_id' => $value['link_id'],
                'email' => $value['email'],
                'short_url' => 'http://' . $host . '/' . $value['short_code'],
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