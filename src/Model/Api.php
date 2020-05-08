<?php

namespace App\Model;

use App\Model\Db;

class Api
{
    public function getUserData($pass, $pass2)
    {
        if (password_verify($pass, $pass2)) {
            return true;
        }
        return false;
    }

    public function paging($kensu, $total)
    {
        $totalpages = ceil($total / $kensu);
        return $totalpages;
    }

    public function from($total, $offset)
    {
        if ($total === 0) {
            $from = 0;
        } else {
            $from = $offset + 1;
        }
        return $from;
    }

    public function to($kensuDefault, $now_page, $total)
    {
        if ($kensuDefault * $now_page > $total) {
            $to = $total;
        } else {
            $to = $kensuDefault * $now_page;
        }
        return $to;
    }

    public function offset($kensuDefault, $now_page)
    {
        $offset = $kensuDefault * ($now_page - 1);
        return $offset;
    }

    public function result($kensuDefault, $now_page, $total, $offset)
    {
        $db = new Db();
        $result = array();

        if ($kensuDefault * $now_page > $total) {
            $lastkensu = $total - $offset;
            $results = $db->select($lastkensu, $offset);
        } else {
            $results = $db->select($kensuDefault, $offset);
        }

        foreach ($results as $key => $value) {
            $result[$key]['id'] = $value['id'];
            $result[$key]['api_name'] = $value['api_name'];
            $result[$key]['protocol'] = $value['protocol'];
            $result[$key]['remote_ip'] = $value['remote_ip'];
            $result[$key]['account_id'] = $value['account_id'];
            $result[$key]['result_code'] = $value['result_code'];
            $result[$key]['result_message'] = $value['result_message'];
            $result[$key]['memo'] = $value['memo'];
        }
        return $result;
    }

    public function searchResult($kensuDefault, $now_page, $searchResultAll, $offset, $api_name, $protocol, $remote_ip, $account_id, $result_code)
    {
        $db = new Db();
        $result = array();

        if ($kensuDefault * $now_page > $searchResultAll) {
            $lastkensu = $searchResultAll - $offset;
            $results = $db->searchResult($kensuDefault, $offset, $api_name, $protocol, $remote_ip, $account_id, $result_code);
        } else {
            $results = $db->searchResult($kensuDefault, $offset, $api_name, $protocol, $remote_ip, $account_id, $result_code);
        }

        foreach ($results as $key => $value) {
            $result[$key]['id'] = $value['id'];
            $result[$key]['api_name'] = $value['api_name'];
            $result[$key]['protocol'] = $value['protocol'];
            $result[$key]['remote_ip'] = $value['remote_ip'];
            $result[$key]['account_id'] = $value['account_id'];
            $result[$key]['result_code'] = $value['result_code'];
            $result[$key]['result_message'] = $value['result_message'];
            $result[$key]['memo'] = $value['memo'];
        }
        return $result;
    }
}
