<?php

namespace App\Model;


class Session
{
    /**
     * ログイン状態によってリダイレクトを行うsession_startのラッパー関数
     * 初回時または失敗時にはヘッダを送信してexitする
     */
    public function require_unlogined_session()
    {
        // セッション開始
        session_start();
        // ログインしていれば / に遷移
        if (isset($_SESSION['username'])) {
            header('Location: /top');
            exit;
        }else{
            $_SESSION = array();
        }
    }

    public function require_logined_session()
    {
        // セッション開始
        session_start();
        // ログインしていなければ /login.php に遷移
        if (!isset($_SESSION['username'])) {
            header('Location: /account/login');
            exit;
        }
    }

    public function logout()
    {
        //セッション開始
        session_start();
        if (isset($_SESSION["username"])) {
            $errorMessage = "ログアウトしました。";
        } else {
            $errorMessage = "セッションがタイムアウトしました。";
        }
        // セッションの変数のクリア
        $_SESSION = array();

        // セッションクリア
        session_destroy();
        return $errorMessage;
    }

    /**
     * CSRFトークンの生成
     *
     * @return string トークン
     */
    public function generate_token()
    {
        // セッションIDからハッシュを生成
        return hash('sha256', session_id());
    }

    /**
     * CSRFトークンの検証
     *
     * @param string $token
     * @return bool 検証結果
     */
    function validate_token($token)
    {
        // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証

        return $token === $this->generate_token();
    }

    /**
     * htmlspecialcharsのラッパー関数
     *
     * @param string $str
     * @return string
     */
    public function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

}

