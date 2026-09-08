<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сервіс скорочення посилань</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-5">
    <div class="container">
        <a class="navbar-brand" href="/"> Урізач URL</a>

        <div class="d-flex">
            <!-- Перевіряємо, чи користувач авторизований -->
            <?php if (isset($_SESSION['user']['id'])): ?>

                <!-- Показуємо кнопку виходу -->
                <a href="/logout" class="btn btn-outline-danger">Вийти</a>

            <?php else: ?>

                <!-- Показуємо кнопки для гостей -->
                <a href="/login" class="btn btn-outline-light me-2">Увійти</a>
                <a href="/register" class="btn btn-primary">Зареєструватись</a>

            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">