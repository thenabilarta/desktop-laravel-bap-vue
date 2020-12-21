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
        $dateFromPostField = null;

        $dateToPostField = null;

        $arrayOfDisplayPostField = null;

        $eventTypePostField = null;

        $campaignIdPostField = null;

        $isPriorityPostField = null;

        $displayOrderPostField = null;

        $snycTimezonePostField = null;

        $recurrenceTypePostField = null;

        $recurrenceDetailPostField = null;

        $recurrenceRangePostField = null;

        $recurrenceRepeatsOnPostField = null;

        $dayPartIdPostField = null;


        //////////////////////////////////


        $dateFrom = $request->dateFrom;

        $timeFrom = $request->timeFrom;

        if ($dateFrom && $timeFrom) {
            $dateFromPostField = 'fromDt=' . $dateFrom . '%20' . $timeFrom;
        }


        $dateTo = $request->dateTo;

        $timeTo = $request->timeTo;

        if ($dateTo && $timeTo) {
            $dateToPostField = '&toDt=' . $dateTo . '%20' . $timeTo;
        }


        $eventType = $request->eventType;

        if ($eventType) {
            $eventTypePostField = '&eventTypeId=' . $eventType;
        }


        $layout = $request->layout;

        if ($layout) {
            $campaignIdPostField = '&campaignId=' . $layout;
        }


        $display = $request->display;

        $arrayOfDisplayPostField = '';

        $string = '&displayGroupIds%5B%5D=';

        foreach ($display as $d) {
            $arrayOfDisplayPostField = $arrayOfDisplayPostField . $string . $d;
        }


        $isPriority = $request->isPriority;

        if ($isPriority) {
            $isPriorityPostField = '&isPriority=' . $isPriority;
        }


        $displayOrder = $request->displayOrder;

        if ($displayOrder) {
            $displayOrderPostField = '&displayOrder=' . $displayOrder;
        }


        $syncTimezone = $request->syncTimezone;

        if ($syncTimezone) {
            $snycTimezonePostField = '&syncTimezone=' . $syncTimezone;
        }


        $recurrenceType = $request->repeat;

        if ($recurrenceType) {
            $recurrenceTypePostField = '&recurrenceType=' . $recurrenceType;
        }


        $recurrenceDetail = $request->repeatEvery;

        if ($recurrenceDetail) {
            $recurrenceDetailPostField = '&recurrenceDetail=' . $recurrenceDetail;
        }


        $recurrenceRepeatsOn = $request->repeatDay;

        if ($recurrenceRepeatsOn) {
            $recurrenceRepeatsOnPostField = '&recurrenceRepeatsOn=' . $recurrenceRepeatsOn;
        }


        $recurrenceRangeDate = $request->dateFromUntil;

        $recurrenceRangeTime = $request->timeFromUntil;

        if ($recurrenceRangeDate && $recurrenceRangeTime) {
            $recurrenceRangePostField = '&recurrenceRange=' . $recurrenceRangeDate . '%20' . $recurrenceRangeTime;
        }


        $dayPartId = $request->dayparting;

        if ($dayPartId) {
            $dayPartIdPostField = '&dayPartId=' . $dayPartId;
        }


        /////////////////////////////////////


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
            $dateFromPostField . $dateToPostField . $arrayOfDisplayPostField . $eventTypePostField . $campaignIdPostField . $isPriorityPostField . $displayOrderPostField . $snycTimezonePostField . $recurrenceTypePostField . $recurrenceDetailPostField . $recurrenceRangePostField . $recurrenceRepeatsOnPostField . $dayPartIdPostField,
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
        $dateFromPostField = null;

        $dateToPostField = null;

        $arrayOfDisplayPostField = null;

        $eventTypePostField = null;

        $campaignIdPostField = null;

        $isPriorityPostField = null;

        $displayOrderPostField = null;

        $snycTimezonePostField = null;

        $recurrenceTypePostField = null;

        $recurrenceDetailPostField = null;

        $recurrenceRangePostField = null;

        $recurrenceRepeatsOnPostField = null;

        $dayPartIdPostField = null;


        //////////////////////////////////


        $dateFrom = $request->dateFrom;

        $timeFrom = $request->timeFrom;

        if ($dateFrom && $timeFrom) {
            $dateFromPostField = 'fromDt=' . $dateFrom . '%20' . $timeFrom;
        }


        $dateTo = $request->dateTo;

        $timeTo = $request->timeTo;

        if ($dateTo && $timeTo) {
            $dateToPostField = '&toDt=' . $dateTo . '%20' . $timeTo;
        }


        $eventType = $request->eventType;

        if ($eventType) {
            $eventTypePostField = '&eventTypeId=' . $eventType;
        }


        $layout = $request->layout;

        if ($layout) {
            $campaignIdPostField = '&campaignId=' . $layout;
        }


        $display = $request->display;

        $arrayOfDisplayPostField = '';

        $string = '&displayGroupIds%5B%5D=';

        foreach ($display as $d) {
            $arrayOfDisplayPostField = $arrayOfDisplayPostField . $string . $d;
        }


        $isPriority = $request->isPriority;

        if ($isPriority) {
            $isPriorityPostField = '&isPriority=' . $isPriority;
        }


        $displayOrder = $request->displayOrder;

        if ($displayOrder) {
            $displayOrderPostField = '&displayOrder=' . $displayOrder;
        }


        $syncTimezone = $request->syncTimezone;

        if ($syncTimezone) {
            $snycTimezonePostField = '&syncTimezone=' . $syncTimezone;
        }


        $recurrenceType = $request->repeat;

        if ($recurrenceType) {
            $recurrenceTypePostField = '&recurrenceType=' . $recurrenceType;
        }


        $recurrenceDetail = $request->repeatEvery;

        if ($recurrenceDetail) {
            $recurrenceDetailPostField = '&recurrenceDetail=' . $recurrenceDetail;
        }


        $recurrenceRepeatsOn = $request->repeatDay;

        if ($recurrenceRepeatsOn) {
            $recurrenceRepeatsOnPostField = '&recurrenceRepeatsOn=' . $recurrenceRepeatsOn;
        }


        $recurrenceRangeDate = $request->dateFromUntil;

        $recurrenceRangeTime = $request->timeFromUntil;

        if ($recurrenceRangeDate && $recurrenceRangeTime) {
            $recurrenceRangePostField = '&recurrenceRange=' . $recurrenceRangeDate . '%20' . $recurrenceRangeTime;
        }


        $dayPartId = $request->dayparting;

        if ($dayPartId) {
            $dayPartIdPostField = '&dayPartId=' . $dayPartId;
        }

        $eventId = $request->id;


        /////////////////////////////////////


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
            $dateFromPostField . $dateToPostField . $arrayOfDisplayPostField . $eventTypePostField . $campaignIdPostField . $isPriorityPostField . $displayOrderPostField . $snycTimezonePostField . $recurrenceTypePostField . $recurrenceDetailPostField . $recurrenceRangePostField . $recurrenceRepeatsOnPostField . $dayPartIdPostField,
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