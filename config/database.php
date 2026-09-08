<?php

return [
    'host' => getenv('DB_HOST') ?: 'db',
    'dbname' => getenv('DB_NAME') ?: 'shortener_db',
    'user' => getenv('DB_USER') ?: 'dev_user',
    'password' => getenv('DB_PASSWORD') ?: 'dev_password'
];