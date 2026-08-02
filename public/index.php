<?php

require __DIR__ . '/../src/Database.php';

$db = connectDB();

echo "Ура! Ми успішно підключилися до бази в Docker!";