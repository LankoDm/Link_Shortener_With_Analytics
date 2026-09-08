<?php require_once __DIR__ . '/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4">Реєстрація</h3>

                    <?php if (!empty($_SESSION['ErrorMessage'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['ErrorMessage'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['ErrorMessage']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['SuccessMessage'])): ?>
                        <div class="alert alert-success">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['SuccessMessage'] as $success): ?>
                                    <li><?php echo htmlspecialchars($success); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['SuccessMessage']); ?>
                    <?php endif; ?>

                    <form action="/register-process" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                        <div class="mb-3">
                            <label for="login" class="form-label">Ваш нікнейм</label>
                            <input type="text" class="form-control form-control-lg" id="login" name="login" minlength="3" maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Електронна пошта</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" minlength="6" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Створити акаунт</button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted">Вже є акаунт?</span>
                        <a href="/login" class="text-decoration-none">Увійти</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>