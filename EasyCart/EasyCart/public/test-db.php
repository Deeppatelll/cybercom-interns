<?php

require __DIR__ . '/../app/Core/Database.php';

try {
    $db = Database::connection();
    echo "DB CONNECTED SUCCESSFULLY";
} catch (Throwable $e) {
    echo $e->getMessage();
}
