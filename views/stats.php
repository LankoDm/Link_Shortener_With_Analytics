<?php require_once __DIR__ . '/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-5">

                    <!-- Заголовок з кнопкою повернення -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title m-0">Детальна статистика</h2>
                        <a href="/" class="btn btn-outline-secondary">Повернутись назад</a>
                    </div>

                    <hr class="mb-4">

                    <?php if (!empty($linkStats)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">Дата та час</th>
                                    <th scope="col">IP-адреса</th>
                                    <th scope="col">Пристрій (User-Agent)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($linkStats as $stat): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($stat['clicked_at']); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars($stat['ip_address']); ?>
                                            </span>
                                        </td>
                                        <!-- Обрізаємо довгий User-Agent, але показуємо повністю при наведенні мишки (атрибут title) -->
                                        <td class="text-truncate" style="max-width: 400px;" title="<?php echo htmlspecialchars($stat['user_agent']); ?>">
                                            <?php echo htmlspecialchars($stat['user_agent']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            За цим посиланням ще не було жодного переходу. Поділіться ним з кимось!
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>