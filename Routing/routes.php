<?php

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

return [
    '' => function () {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return new HTMLRenderer('component/inputSnippet');
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // POSTデータを取得
                $title = $_POST['title'];
                $language = $_POST['language'];
                $expiry = $_POST['expiry'];
                $content = $_POST['content'];

                $input = $title . $content;
                $hash = hash('crc32', $input);

                // DatabaseHelperを使用してスニペットを保存
                $success = \Helpers\DatabaseHelper::saveSnippet($title, $expiry, $language, $content, $hash);

                if ($success) {
                    $host = $_SERVER['HTTP_HOST'];

                    // Snippetへのリンクを作成
                    $snippetLink = "http://$host/Snippet?hash=$hash";

                    // JSONレスポンスを作成
                    return new JSONRenderer(['snippetLink' => $snippetLink]);
                } else {
                    // データベースへの挿入に失敗した場合の処理
                    return new JSONRenderer(['error' => 'Failed to insert snippet into database']);
                }
            } catch (Exception $e) {
                // エラーが発生した場合の処理
                return new JSONRenderer(['error' => 'Internal error: ' . $e->getMessage()]);
            }
        }
    },
    'Snippet' => function () {
        return new HTMLRenderer('component/Snippet');
    },
];
