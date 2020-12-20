<?php

namespace Modules\Schedule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Platform\Core\Http\Controllers\AppBaseController;

session_start();

class ScheduleController extends AppBaseController
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

        return view('schedule::index');
    }

    public function data(Request $request)
    {
        $dateFrom = $request->dateFrom;

        $dateTo = $request->dateTo;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/schedule/data/events?displayGroupIds%5B%5D=-1&from=' . $dateFrom . '&to=' . $dateTo,
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

    public function addSchedule(Request $request)
    {
        $dateFrom = $request->dateFrom;

        $dateTo = $request->dateTo;

        $timeFrom = $request->timeFrom;

        $timeTo = $request->timeTo;

        $eventType = $request->eventType;

        $layout = $request->layout;

        // For each array of Display

        $newString = '';

        $string = '&displayGroupIds%5B%5D=';

        $display = $request->display;

        foreach ($display as $d) {
            $newString = $newString . $string . $d;
        }

        // end of array

        $isPriority = $request->isPriority;

        $displayOrder = $request->displayOrder;

        $syncTimezone = $request->syncTimezone;

        $recurrenceType = $request->repeat;

        $recurrenceDetail = $request->repeatEvery;

        $recurrenceRepeatsOn = $request->repeatDay;

        $recurrenceRangeDate = $request->dateFromUntil;

        $recurrenceRangeTime = $request->timeFromUntil;

        $dayPartId = $request->dayparting;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/schedule',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>
            'fromDt=' . $dateFrom .
                '%20' . $timeFrom .
                '&toDt=' . $dateTo .
                '%20' . $timeTo .
                $newString .
                '&eventTypeId=' . $eventType .
                '&campaignId=' . $layout .
                '&isPriority=' . $isPriority .
                '&displayOrder=' . $displayOrder .
                '&syncTimezone=' . $syncTimezone .
                '&recurrenceType=' . $recurrenceType .
                '&recurrenceDetail=' . $recurrenceDetail .
                '&recurrenceRange=' . $recurrenceRangeDate .
                '%20' . $recurrenceRangeTime .
                '&recurrenceRepeatsOn=' . $recurrenceRepeatsOn .
                '&dayPartId=' . $dayPartId,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $_SESSION["token"],
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $contents = $response;
        $content = json_decode($contents, true);

        return $content;
    }

    public function editSchedule(Request $request)
    {
        $dateFrom = $request->dateFrom;

        $dateTo = $request->dateTo;

        $timeFrom = $request->timeFrom;

        $timeTo = $request->timeTo;

        $eventType = $request->eventType;

        $layout = $request->layout;

        //

        $newString = '';

        $string = '&displayGroupIds%5B%5D=';

        $display = $request->display;

        foreach ($display as $d) {
            $newString = $newString . $string . $d;
        }

        //

        $isPriority = $request->isPriority;

        $displayOrder = $request->displayOrder;

        $syncTimezone = $request->syncTimezone;

        $recurrenceType = $request->repeat;

        $recurrenceDetail = $request->repeatEvery;

        $recurrenceRepeatsOn = $request->repeatDay;

        $recurrenceRangeDate = $request->dateFromUntil;

        $recurrenceRangeTime = $request->timeFromUntil;

        $dayPartId = $request->dayparting;

        $eventId = $request->id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/schedule/' . $eventId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>
            'fromDt=' . $dateFrom .
                '%20' . $timeFrom .
                '&toDt=' . $dateTo .
                '%20' . $timeTo .
                $newString .
                '&eventTypeId=' . $eventType .
                '&campaignId=' . $layout .
                '&isPriority=' . $isPriority .
                '&displayOrder=' . $displayOrder .
                '&syncTimezone=' . $syncTimezone .
                '&recurrenceType=' . $recurrenceType .
                '&recurrenceDetail=' . $recurrenceDetail .
                '&recurrenceRange=' . $recurrenceRangeDate .
                '%20' . $recurrenceRangeTime .
                '&recurrenceRepeatsOn=' . $recurrenceRepeatsOn .
                '&dayPartId=' . $dayPartId,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $_SESSION["token"],
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $contents = $response;
        $content = json_decode($contents, true);

        return $content;
    }

    public function delete(Request $request)
    {
        $eventId = $request->id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.1.5/xibo-cms/web/api/schedule/' . $eventId,
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