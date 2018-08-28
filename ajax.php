<?php
use App\Registry;
use Code\Response;
use App\MessageValidator;


require __DIR__ . '/bootstrap.php'; // initial app setup

$action = ! empty($_GET['action']) ? $_GET['action'] : '';

$message_save_result = false;

switch ($action) {
	case 'save':
		
		$main_controller = Registry::getInstance()->get('factory')
			->getController('Main');
		$message_save_result = $main_controller->saveMessage();
		
		break;
	default:
		break;
}

$response = new Response();

if ($message_save_result !== true && is_array($message_save_result)) {

	$response->add('errors', $message_save_result);
}

// gets messages list
$messages_list_html = Registry::getInstance()->get('factory')
	->getController('Main')
	->getMessagesListHtml();

$response->add('messages', $messages_list_html);

echo $response->getJSON(); 
