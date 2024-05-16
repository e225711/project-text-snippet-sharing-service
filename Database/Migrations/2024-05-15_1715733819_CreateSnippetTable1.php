<?php

namespace Database\Migrations;

require_once __DIR__ . '/../SchemaMigration.php';

use Database\SchemaMigration;

class CreateSnippetTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE snippet (
                id INT AUTO_INCREMENT PRIMARY KEY,
                snippet_name CHAR(255) NOT NULL,
                expiry_date DATETIME,
                code_type CHAR(255),
                content TEXT,
                hash CHAR(255)
            );
            ',
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'DROP TABLE snippet;'
        ];
    }
}
