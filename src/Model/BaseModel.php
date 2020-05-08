<?php

namespace App\Model;

use App\Model\Pdomodel;

class BaseModel
{
    function getRow($sql)
    {
        $PDO = new Pdomodel();
        $mysql = $PDO->DBconnection();
        $stmt = $mysql->query($sql);
        $row = $stmt->fetchAll();
        return $row;
    }

    function getColumn($sql)
    {
        $PDO = new Pdomodel();
        $mysql = $PDO->DBconnection();
        $stmt = $mysql->query($sql);
        $column = $stmt->fetchColumn();
        return $column;
    }

    function execute($sql)
    {
        $PDO = new Pdomodel();
        $mysql = $PDO->DBconnection();
        $stmt = $mysql->prepare($sql);
        $execute = $stmt->execute();
        return $execute;
    }
}

