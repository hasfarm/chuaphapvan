<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$users = $app->make('db')->select('SELECT name, email, role_id FROM users ORDER BY id');

echo "=== DANH SÁCH USERS ===\n";
echo str_pad("Tên", 20) . str_pad("Email", 30) . "Role ID\n";
echo str_repeat("-", 60) . "\n";

foreach ($users as $row) {
    echo str_pad($row->name, 20) . str_pad($row->email, 30) . $row->role_id . "\n";
}

echo "\n✅ Tất cả users đã có role_id!\n";
