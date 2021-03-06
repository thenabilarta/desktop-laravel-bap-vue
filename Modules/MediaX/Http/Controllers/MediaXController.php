<?php

namespace Modules\MediaX\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use CURLFile;

session_start();

class MediaXController extends AppBaseController
{
    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.2/xibo-cms/web/api/authorize/access_token',
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

        return view('mediax::index');
    }

    public function data()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.2/xibo-cms/web/api/library?length=100',
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

    public function addmedia(Request $request)
    {
        $file = $request->file('file');

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $file_path = $file->getRealPath();

        $fileName = request('fileName');

        $curl_file =  new CURLFile($file_path, $file_ext, $file_name);
        $post_data = array(
            'files' => $curl_file,
            'name' => $fileName
        );

        $target_url = "http://192.168.1.2/xibo-cms/web/api/library";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content_Type: application/json',
            'Authorization: Bearer ' . $_SESSION["token"],
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $response = curl_exec($ch);
        curl_close($ch);

        $contents = $response;
        $content = json_decode($contents, true);

        if (isset($content["files"][0]["error"])) {
            return response()->json(["status" => "failed"]);
        }

        return response()->json(["status" => "ok"]);
    }

    public function edit(Request $request)
    {
        $newfilename = $request->listName;

        $media_id = $request->media_id;

        $duration = $request->duration;

        $tags = $request->tags;

        $retired = $request->retired;

        if ($retired === "") {
            $retired = "0";
        }

        $post = 'name=' . $newfilename . '&duration=' . $duration . '&retired=' . $retired . '&tags=' . $tags . '&updateInLayouts=0';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.2/xibo-cms/web/api/library/' . $media_id,
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

        if (isset($content["error"])) {
            return response()->json(["status" => "failed"]);
        }

        return response()->json(["status" => "ok"]);
    }

    public function delete($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.2/xibo-cms/web/api/library/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
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
