<?php

namespace App\Model;

use \PDO;
use \PDOException;

class Pdomodel
{
    public function DBConnection()
    {
        // ドライバ呼び出しを使用して MySQL データベースに接続します
        try {
            $connect = new PDO('mysql:dbname=web_training_staguri;host=localhost', 'root', 'web-Training0401');
            return $connect;
        } catch (PDOException $e) {
            echo "接続失敗: " . $e->getMessage() . "\n";
            return false;
        }
    }
}
