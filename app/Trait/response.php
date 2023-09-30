<?php

namespace App\Trait;

trait response
{

    public function responseApi($isSuccess =false, $errors =[], $data=null,$pagination=null)
    {

        return response()->json(['isSuccess' =>$isSuccess,'errors' => $errors, 'data' => $data,'links'=>$pagination]);
    }
}
