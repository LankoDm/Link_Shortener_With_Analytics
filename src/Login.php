<?php

function loginUser($dbConnection)
{
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION['ErrorMessage'] = ["Всі поля обов'язкові!"];
        header("Location: /login");
        exit;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = :email";

    $user = $dbConnection->prepare($query);
    $user->execute(['email' => $email]);
    $dataUser = $user->fetch();

    if ($dataUser && password_verify($password, $dataUser['password_hash'])) {
        $_SESSION['user']['id'] = $dataUser['id'];
        header('Location: /');
        exit;
    } else {
        $_SESSION['ErrorMessage'] = ["Неправильний логін або пароль"];
        header("Location: /login");
        exit;
    }
}

function logout()
{
    session_destroy();
    header('Location: /');
    exit;
}