<?php

namespace Modules\Display\Http\Controllers;

use Modules\Display\Entities\Display;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Platform\Core\Http\Controllers\AppBaseController;

session_start();

class DisplayController extends AppBaseController
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

        return view('display::index');
    }

    public function data()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/display',
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

    public function layout()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/layout',
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

    public function setLayout(Request $request)
    {
        $layoutId = $request->layoutId;

        $displayId = $request->displayId;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/display/defaultlayout/' . $displayId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => 'layoutId=' . $layoutId,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $_SESSION["token"],
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return response()->json(["status" => 'ok']);
    }

    public function edit(Request $request)
    {
        // return $request->content["defaultLayout"];

        $licensed = $request->authorizedValue;

        $defaultLayout = $request->defaultLayout;

        $displayName = $request->content["displayName"];

        $displayLicenseKey = $request->content["displayKey"];

        $displayId = $request->displayId;

        // return $licensed . $defaultLayout . $displayName . $displayId;

        $post = 'licensed=' . $licensed . '&defaultLayoutId=' . $defaultLayout . '&display=' . $displayName . '&displayId=' . $displayId . '&wakeOnLanEnabled=0&emailAlert=0&incSchedule=0&license=' . $displayLicenseKey;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/display/' . $displayId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $_SESSION["token"],
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $contents = $response;
        $content = json_decode($contents, true);

        return response()->json(["status" => "ok"]);
    }

    public function previewProfileData(Request $request)
    {
        $displayID = $request->displayID;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/displayprofile?embed=config&displayProfileId=2',
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