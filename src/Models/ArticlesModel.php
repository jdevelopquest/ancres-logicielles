<?php

namespace App\Models;

class ArticlesModel
{
    public function getArticles(): array
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=ancres_logicielles;charset=utf8mb4;port=3306';
        $user = 'alambic';
        $password = 'fdb_fe2b_5145_84bd_fb4g_fb78_rbg7_8t74';
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $stmt = $pdo->prepare('SELECT posts.title, posts.content FROM posts WHERE posts.type = \'article\'');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}