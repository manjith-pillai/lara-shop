<?php
/**
 * User: Rishabh
 * Date: 10/12/15
 * Time: 12:46 AM
 */

namespace App\Models;
use Illuminate\Support\Facades\DB;


class Device {
    public function addPushKey($push_key, $device_id){
        $id = DB::table('devices')
            ->where('push_key',$push_key)
            ->first();
        if($id == null){
            DB::table('devices')
                ->insert(['push_key' => $push_key, 'device_id' => $device_id]);
            return "Push Key Added";
        }
        else
            return "Already Available";
    }

    public function sendNotification($message){
        $push_keys = DB::table('devices')
            ->select('devices.push_key')
            ->get();
        $keys = array();
        foreach($push_keys as $val){
            array_push($keys, $val->push_key);
        }
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $keys,
            'data' => array("message" =>$message),
        );
        // Google Cloud Messaging GCM API Key
        //define("GOOGLE_API_KEY", "AIzaSyDaiQV7GvBh3N9qeQJSMEyhnZIr8-Q9aC0");        
        define("GOOGLE_API_KEY", "AIzaSyDWwGH4YbEP1AVtDUlM5AC8fSHyXy0deCI");
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);               
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
} 