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

