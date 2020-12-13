<?php

namespace Modules\DisplayProfile\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Platform\Core\Http\Controllers\AppBaseController;

session_start();

class DisplayProfileController extends AppBaseController
{
    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/authorize/access_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('client_id' => 'CgEumMDYr0X1PIdry1PhxOiLNUnv90JzDZCErEE0', 'client_secret' => 'he2yvUkvE2pMWBs752WlCCSVKoDdFrOgRM4uFu9MNJYovdI3uy5snGBHJf24jufWGKLTiPelEgpuwqg6wbHF1yYuWOyVwlumvdjmgXSBPJ0r00qdyJEvenmwyFUYQFhkdxBAFSHveeB6VqsOA4u2rrCXOpFAPJppnAApFxZXgXFrg1JOsSjsSAsuFeWP67D4akdB9bIbjfcs9rbcj2CG28AVggElHunaFjk8pGedvYRCOnV9bXLFZhfB9sXbZV', 'grant_type' => 'client_credentials')
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $contents = $response;
        $content = json_decode($contents, true);

        $token = $content["access_token"];

        $_SESSION["token"] = $token;

        return view('displayprofile::index');
    }

    public function data()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/displayprofile',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $_SESSION["token"],
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $contents = $response;
        $content = json_decode($contents, true);

        return $content;
    }
}