<?php

namespace App\Services;


class VinDecoder
{
    public function getAjaxData($query){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $query);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded'
        ));
        $response = curl_exec($ch);
        curl_close ($ch);
        return $response;
    }
}
