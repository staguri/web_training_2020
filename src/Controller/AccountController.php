<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Test;
use App\Model\Api;
use App\Model\Session;
use App\Model\Db;

class AccountController extends AbstractController
{

    /**
     * @Route("/account/create", name="account_create" )
     */
    public function createAccount(Request $request)
    {
        $session = new Session();
        $session->require_unlogined_session();
        $userInfo = $request->query->get('userInfo');
        $token = $session->generate_token();
        $data = array(
            'userInfo' => $userInfo,
            'token' => $token
        );
        return $this->render('account/create.html', $data);
    }

    /**
     * @Route("/account/verification", name="verification" )
     */
    public function verification(Request $request)
    {
        $db = new Db();
        $session = new Session();
        $user = $request->request->get('username');
        $pass = $request->request->get('passwd');
        $token = $request->request->get('token');
        if($session->validate_token($token)){
            return $this->redirectToRoute("account_create");
        }
        $userInfoResult = $db->searchUser($user);
        if ($userInfoResult > 0) {
            return $this->redirectToRoute("account_create", [
                'userInfo' => "このユーザ名は既に使われています"
            ]);
        }
        $db->createAcccount($user, $pass);
        $data = array(
            'user_name' => $user,
            'password' => $pass
        );
        return $this->render('account/verification.html', $data);
    }

    /**
     * @Route("/ajax/account/login", name="ajax_login")
     */
    public function authentication(Request $request)
    {
        $session = New Session();
        $db = new Db();
        $api = new Api();
        $session->require_unlogined_session();
        $user = $request->request->get('name');
        $pass = $request->request->get('pass');
        $token = $request->request->get('token');
        if ($session->validate_token($token)) {
            $userInfo = $db->getUserData($user);
            if(empty($userInfo)) {
                $data = array(
                    'message' => 'そのようなユーザ名は存在しません'
                );
            }else {
                $authentication = $api->getUserData($pass, $userInfo[0]['password']);
                if (!$authentication) {
                    http_response_code(403);
                    $data = array(
                        'message' => 'error'
                    );
                } else {
                    session_regenerate_id(true);
                    $_SESSION['username'] = $user;
                    $data = array(
                        'message' => 'ok',
                    );
                }
            }

        } else {
            $data = array(
                'message' => 'CSRFトークンエラー'
            );
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/account/login", name="login")
     */
    public function login()
    {
        $session = New Session();
        $session->require_unlogined_session();
        $_SESSION = array();
        $token = $session->generate_token();
        $session->h($token);
        return $this->render('account/login.html', [
            'csrf_token' => $token,
        ]);
    }

    /**
     * @Route("/account/logout", name="logout")
     */
    public function logout()
    {
        $session = New Session();
        $message = array(
            'message' => $session->logout()
        );
        return $this->render('account/logout.html', $message);
    }
}
