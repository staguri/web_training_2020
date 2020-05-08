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

class DefaultController extends AbstractController
{
    /**
     * @Route("/top", name="top",methods="get")
     */
    public function top(Request $request){
        $api = new Api();
        $db = new Db();
        $session = New Session;
        $session->require_logined_session();
        $now_page = 1;
        $kensuDefault = 5;
        $page = $request->query->get('page');
        //表示件数
        $kensuDef = $request->query->get('kensuDef');
        //全レコード数を取得
        $total = intval($db->column());

        if(!is_null($kensuDef)){
            $kensuDefault = intval($kensuDef);
        }
        if(!is_null($page)){
            $now_page = intval($page);
        }

        $total_page = $api->paging($kensuDefault,$total);
        $offset = $api->offset($kensuDefault,$now_page);
        $from = $api->from($total,$offset);
        $to = $api->to($kensuDefault,$now_page,$total);
        $result = $api->result($kensuDefault,$now_page,$total,$offset);


        $data = array(
            'userName'=> $_SESSION['username'],
            'from' => $from,
            'to' => $to,
            'log' => $result,
            'total_page' => $total_page,
            'now' => $now_page,
            'kensu' => $total,
            'kensuDef' => $kensuDefault
        );
        return $this->render('default/Top.html', $data);
    }


    /**
     * @Route("/ajax/memo", name="ajax_top", methods="get")
     */
    public function updateMemo(Request $request){
        $db = new Db();
        $memo = $request->query->get('memo');
        $id = $request->query->get('id');

        $authentication = $db->updateMemo($id,$memo);
        if(!$authentication){
            $data = array(
                'message' => 'error'
            );
        }else{
            $data = array(
                'message' => 'ok'
            );
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     * @Route("/top/search", name="top_search", methods="get")
     */
    public function search(Request $request){
        $session = New Session;
        $db = New Db();
        $api= New Api();
        $session->require_logined_session();
        //$now_pageは現在のページ
        $now_page = 1;
        //表示件数
        $kensuDefault = intval($request->query->get('kensuDef'));
        $page = $request->query->get('page');
        $api_name =  $request->query->get('api_name');
        $protocol = $request->query->get('protocol');
        $remote_ip = $request->query->get('remote_ip');
        $account_id = $request->query->get('account_id');
        $result_code = $request->query->get('result_code');
        if(!is_null($page)){
            $now_page = intval($page);
        }
        //検索結果件数を取得
        $searchResultAll = intval($db->searchColumn($api_name,$protocol,$remote_ip,$account_id,$result_code));
        //ページ数
        $total_page = $api->paging($kensuDefault,$searchResultAll);
        $offset = $api->offset($kensuDefault,$now_page);
        $from = $api->from($searchResultAll,$offset);
        $to = $api->to($kensuDefault,$now_page,$searchResultAll);
        $result = $api->searchResult($kensuDefault,$now_page,$searchResultAll,$offset,$api_name, $protocol, $remote_ip, $account_id, $result_code);

        $data = array(
            'userName'=> $_SESSION['username'],
            'from' => $from,
            'to' => $to,
            'api_name' =>$api_name,
            'protocol' => $protocol,
            'remote_ip' => $remote_ip,
            'account_id' => $account_id,
            'result_code' => $result_code,
            'searchResult' => $result,
            'kensu' => $searchResultAll,
            'kensuDef' => $kensuDefault,
            'total_page' => $total_page,
            'now' => $now_page
        );
        return $this->render('default/Search.html', $data);
    }

}
