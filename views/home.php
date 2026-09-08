<?php require_once __DIR__ . '/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-5">

                    <?php if (isset($_SESSION['user']['id'])): ?>

                        <h2 class="card-title text-center mb-4">Вставте довге посилання</h2>

                        <!-- Виведення помилок та успіхів через сесію -->
                        <?php if (!empty($_SESSION['ErrorMessage'])): ?>
                            <div class="alert alert-danger text-center">
                                <?php foreach ($_SESSION['ErrorMessage'] as $error): ?>
                                    <div><?php echo htmlspecialchars($error); ?></div>
                                <?php endforeach; ?>
                            </div>
                            <?php unset($_SESSION['ErrorMessage']); ?>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['SuccessMessage'])): ?>
                            <div class="alert alert-success text-center">
                                <?php foreach ($_SESSION['SuccessMessage'] as $success): ?>
                                    <div><strong><?php echo htmlspecialchars($success); ?></strong></div>
                                <?php endforeach; ?>
                            </div>
                            <?php unset($_SESSION['SuccessMessage']); ?>
                        <?php endif; ?>

                        <form action="/save" method="POST">
                            <div class="input-group mb-4">
                                <input type="url" name="original_url" class="form-control form-control-lg"
                                       placeholder="https://example.com/very-long-link..." required>
                                <button class="btn btn-primary btn-lg" type="submit">Скоротити</button>
                            </div>
                        </form>

                        <hr class="my-5">

                        <h4 class="text-center mb-4">Мої посилання</h4>

                        <!-- Таблиця з посиланнями -->
                        <?php if (!empty($userLinks)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col">Коротке посилання</th>
                                        <th scope="col">Оригінальне посилання</th>
                                        <th scope="col" class="text-center">Переходи</th>
                                        <!-- Нова колонка для кнопок -->
                                        <th scope="col" class="text-center">Дії</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($userLinks as $link): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($link['short_url']); ?>"
                                                   class="fw-bold text-decoration-none" target="_blank">
                                                    <?php echo htmlspecialchars($link['short_url']); ?>
                                                </a>
                                            </td>
                                            <td class="text-truncate" style="max-width: 300px;">
                                                <a href="<?php echo htmlspecialchars($link['original_url']); ?>"
                                                   class="text-muted" target="_blank">
                                                    <?php echo htmlspecialchars($link['original_url']); ?>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary rounded-pill fs-6">
                                                    <?php echo htmlspecialchars($link['clicks_count']); ?>
                                                </span>
                                            </td>

                                            <!-- Додаємо дві окремі форми для дій -->
                                            <td class="text-center text-nowrap">

                                                <!-- Кнопка Статистики -->
                                                <form action="/stats" method="POST" class="d-inline">
                                                    <input type="hidden" name="link_id" value="<?php echo htmlspecialchars($link['link_id']); ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-info me-1">Статистика</button>
                                                </form>

                                                <!-- Кнопка Видалення -->
                                                <form action="/delete" method="POST" class="d-inline" onsubmit="return confirm('Ви впевнені, що хочете видалити це посилання? Вся статистика переходів також буде знищена.');">
                                                    <input type="hidden" name="link_id" value="<?php echo htmlspecialchars($link['link_id']); ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                                                </form>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- ПАГІНАЦІЯ -->
                            <?php if (isset($maxPage) && $maxPage > 1): ?>
                                <nav aria-label="Пагінація сторінок" class="mt-4">
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php for ($i = 1; $i <= $maxPage; $i++): ?>
                                            <li class="page-item <?php echo (isset($page) && $i === $page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="/?page=<?php echo $i; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                            <!-- КІНЕЦЬ ПАГІНАЦІЇ -->

                        <?php else: ?>
                            <p class="text-center text-muted">Ви ще не створили жодного короткого посилання. Спробуйте
                                прямо зараз!</p>
                        <?php endif; ?>

                    <?php else: ?>

                        <div class="text-center">
                            <h2 class="mb-4">Вітаємо у сервісі скорочення посилань!</h2>
                            <p class="lead mb-5 text-muted">Щоб почати створювати короткі посилання та відслідковувати
                                їх статистику, будь ласка, увійдіть у свій акаунт або створіть новий.</p>

                            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                                <a href="/login" class="btn btn-primary btn-lg px-4">Увійти</a>
                                <a href="/register" class="btn btn-outline-secondary btn-lg px-4">Зареєструватись</a>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>