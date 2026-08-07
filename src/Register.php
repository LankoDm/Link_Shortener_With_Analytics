<?php

function createNewUser($dbConnection)
{
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
    $email = $_POST['email'];

    $query = "INSERT INTO users (username, password_hash, email)
            VALUES (:login, :password, :email)";

    $user = $dbConnection->prepare($query);
    try {
        $user->execute(['login' => $login, 'password' => $password, 'email' => $email]);
        $_SESSION['SuccessMessage'] = ["Реєстрація успішна! Тепер ви можете увійти."];
        header("Location: /login");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['ErrorMessage'] = ["Користувач з таким email або логіном вже існує!"];
            header("Location: /register");
            exit;
        }
        die("Помилка бази даних: " . $e->getMessage());
    }
}
