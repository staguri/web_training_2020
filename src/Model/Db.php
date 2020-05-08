<?php

namespace App\Model;

use App\Model\Pdomodel;
use App\Model\BaseModel;

class Db extends BaseModel
{
    public function select($kensu, $page)
    {
        $sql = "select * from log limit $kensu offset $page";
        return $this->getRow($sql);
    }

    public function searchResult($kensu, $offset, $api_name, $protocol, $remote_ip, $account_id, $result_code)
    {
        $sql = sprintf(
            'select * from log where api_name = case when "%s" != "" then "%s" else api_name end
                    and protocol = case when "%s" != "" then "%s" else protocol end
                    and remote_ip = case when "%s" != "" then "%s" else remote_ip end
                    and account_id = case when "%s" != "" then "%s" else account_id end
                    and result_code = case when "%s" != "" then "%s" else result_code end limit %d offset %d',$api_name,$api_name,$protocol,$protocol,$remote_ip,$remote_ip,$account_id,$account_id,$result_code,$result_code,$kensu,$offset);
        return $this->getRow($sql);
    }

    public function searchColumn($api_name, $protocol, $remote_ip, $account_id, $result_code)
    {
        $sql = sprintf(
            'select count(*) from log where api_name = case when "%s" != "" then "%s" else api_name end
                    and protocol = case when "%s" != "" then "%s" else protocol end
                    and remote_ip = case when "%s" != "" then "%s" else remote_ip end
                    and account_id = case when "%s" != "" then "%s" else account_id end
                    and result_code = case when "%s" != "" then "%s" else result_code end',$api_name,$api_name,$protocol,$protocol,$remote_ip,$remote_ip,$account_id,$account_id,$result_code,$result_code);
        return $this->getColumn($sql);
    }

    public function createAcccount($username, $pass)
    {
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = sprintf('insert into userData(name,password) values("%s","%s")', $username, $hash_pass);
        return $this->execute($sql);
    }

    public function getUserData($username)
    {
        $sql = sprintf('select password from userData where name = "%s"', $username);
        return $this->getRow($sql);
    }

    public function column()
    {
        $sql = "select count(*) from log";
        return $this->getColumn($sql);
    }

    public function searchUser($username)
    {
        $sql = sprintf('select * from userData where name = "%s"', $username);
        return $this->getColumn($sql);
    }

    public function updateMemo($id,$memo)
    {
        $sql = sprintf('update log set memo = "%s" where id = %d', $memo,$id);
        return $this->execute($sql);
    }
}
