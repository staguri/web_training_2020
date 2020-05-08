<?php

namespace App\Model;

class Test{
    public function loop($count){
        $total = 0;
        for($i=0; $i<$count; $i++){
            $total = $total + $i;
        }
        return $total;
    }
}
