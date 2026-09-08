<?php

function createNewUser($dbConnection)
{
    $errors = [];

    $login = trim($_POST['login'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($email) || empty($password)) {
        $errors[] = "Всі поля обов'язкові!";
    }

    if (mb_strlen($login) < 3 || mb_strlen($login) > 100) {
        $errors[] = "Нікнейм має бути від 3 до 100 символів.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Введіть коректну електронну пошту.";
    }

    if (mb_strlen($password) < 6) {
        $errors[] = "Пароль має бути не менше 6 символів.";
    }

    if (!empty($errors)) {
        $_SESSION['ErrorMessage'] = $errors;
        header("Location: /register");
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_ARGON2ID);

    $query = "INSERT INTO users (username, password_hash, email)
            VALUES (:login, :password, :email)";

    $user = $dbConnection->prepare($query);
    try {
        $user->execute(['login' => $login, 'password' => $passwordHash, 'email' => $email]);
        $_SESSION['SuccessMessage'] = ["Реєстрація успішна! Тепер ви можете увійти."];
        header("Location: /login");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['ErrorMessage'] = ["Користувач з таким email або логіном вже існує!"];
            header("Location: /register");
            exit;
        }
        $_SESSION['ErrorMessage'] = ["Сталась помилка, спробуйте пізніше."];
        header("Location: /register");
        exit;
    }
}
