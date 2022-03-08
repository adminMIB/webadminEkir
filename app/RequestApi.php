<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Session;

class RequestApi extends Model {
	
	public static function request($method, $apiPath, $params){
		$client = new Client();
		$url = config('app.api_url').'/'.$apiPath;

		$response = $client->request($method, $url, [
			'auth'	=> [
				config('app.api_username'),
				config('app.api_password'),
			],
			'body' 		=> json_encode($params),
			'timeout' 	=> config('app.api_timeout'),
		]);
		
		$body = $response->getBody();
		//echo (string) $body;
		//dd();

		$oResult = json_decode((string) $body);
		
		return $oResult;
	}
	
	public static function sendNotification($to, $title, $message, $booking_id){
		$client = new Client();
		$url = config('app.service_notification_url');
		
		/*
		{
		 "to" : "fMiQj2HBRBW3GEoxgvl4AT:APA91bHiMLWAL2wBemYx3sN4GEdFZs_1gWQy_lcnQv2pmMyBpAdK4xrdaZTaxnlVg16s7iPKreuUs0hP0t6i0nPNuHzopfph9yw1KRl0Q1kkEQxf_scIEjjZjv5GNX6ZBTXdf6OkuuxI",
		 "notification" : {
			 "body" : "Pesanan Berhasil Percobaan",
			 "title": "Gosol KDI"
		 },
		 "data" : {
			 "id" : "ID002",
			 "title" : "Title Test",
			 "message" : "Message Test"
		 }
		}
		*/
		$params = new \stdClass;
		$params->device_token = $to;
		$params->title = $title;
		$params->message = $message;
		$params->booking_id = $booking_id;
		
		$response = $client->request("POST", $url, [
			'auth'	=> [
				config('app.api_username'),
				config('app.api_password'),
			],
			'body' 		=> json_encode($params),
			'timeout' 	=> config('app.api_timeout'),
		]);
		
		$body = $response->getBody();
		$oResult = json_decode((string) $body);
		
		return $oResult;
	}

	public static function auth($params=array()){
		$client = new Client();
		$response = $client->request('POST', config('app.api_url').'/auth', [
			'body' => json_encode($params)
		]);
		
		$body = $response->getBody();
		$oResult = json_decode((string) $body);
		
		return isset($oResult->TOKEN) ? $oResult->TOKEN : false;
	}
	
}
