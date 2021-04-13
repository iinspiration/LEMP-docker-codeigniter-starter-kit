<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once ('vendor/autoload.php');
require_once ('bot_settings.php');

class Bot extends CI_Controller {

	function __construct() {
        parent::__construct();
		// $this->load->database();
		
    }

	public function index()
	{
			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
			$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);
		
			$content = file_get_contents('php://input');

			$fp = fopen('log/log.txt', 'w');
			fwrite($fp, $content);
			fclose($fp);
	
			//decode json to array
			$events = json_decode($content, true);
			
			//get reply token and message if events is not null
			if (!is_null ($events)) {
				$replyToken = $events['events'][0]['replyToken'];
				$message = $events['events'][0]['message']['text'];
			}
			
			$reply = $message;

			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($reply);

			
			$response = $bot->replyMessage($replyToken,$textMessageBuilder);

			if ($response->isSucceeded()) {
				echo 'Succeeded!';
				return;
			}
			
			echo $response->getHTTPStatus() . ' ' . $response->getRawBody();


	}

}
