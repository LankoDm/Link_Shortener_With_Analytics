<?php require_once __DIR__ . '/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-danger"> <!-- Червона рамка, щоб пам'ятати, що це адмінка -->
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Панель Адміністратора</h4>
                </div>
                <div class="card-body p-4">

                    <h5 class="mb-4">Всі посилання в системі</h5>

                    <!-- БЛОК ВИВЕДЕННЯ ПОВІДОМЛЕНЬ -->
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
                    <!-- КІНЕЦЬ БЛОКУ ПОВІДОМЛЕНЬ -->

                    <?php if (!empty($allLinks)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-sm">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">Власник (Email)</th>
                                    <th scope="col">Коротке посилання</th>
                                    <th scope="col">Оригінал</th>
                                    <!-- Нова колонка -->
                                    <th scope="col" class="text-center">Дії</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($allLinks as $link): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($link['email']); ?></strong></td>
                                        <td>
                                            <a href="<?php echo htmlspecialchars($link['short_url']); ?>" target="_blank">
                                                <?php echo htmlspecialchars($link['short_url']); ?>
                                            </a>
                                        </td>
                                        <td class="text-truncate" style="max-width: 250px;">
                                            <a href="<?php echo htmlspecialchars($link['original_url']); ?>" class="text-muted" target="_blank">
                                                <?php echo htmlspecialchars($link['original_url']); ?>
                                            </a>
                                        </td>
                                        <!-- Кнопка видалення для адміна -->
                                        <td class="text-center">
                                            <form action="/admin-delete" method="POST" class="d-inline" onsubmit="return confirm('Ви впевнені, що хочете видалити посилання цього користувача?');">
                                                <!-- Прихований параметр з ID посилання -->
                                                <input type="hidden" name="link_id" value="<?php echo htmlspecialchars($link['link_id']); ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Видалити</button>
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
                                        <!-- Якщо це поточна сторінка, додаємо клас active для підсвічування -->
                                        <li class="page-item <?php echo (isset($page) && $i === $page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="/admin?page=<?php echo $i; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                        <!-- КІНЕЦЬ ПАГІНАЦІЇ -->

                    <?php else: ?>
                        <p class="text-center text-muted">В системі ще немає посилань.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>