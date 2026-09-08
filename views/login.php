<?php require_once __DIR__ . '/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4">Вхід в акаунт</h3>

                    <form action="/login-process" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Електронна пошта</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Увійти</button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted">Ще немає акаунту?</span>
                        <a href="/register" class="text-decoration-none">Зареєструватись</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>