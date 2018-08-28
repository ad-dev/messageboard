<?php
use App\Registry;

require __DIR__ . '/bootstrap.php'; // initial app setup


$action = ! empty($_GET['action']) ? $_GET['action'] : '';

$message_save_result = false;

switch ($action) {
	case 'save':
		
		$main_controller = Registry::getInstance()->get('factory')
			->getController('Main');
		$message_save_result = $main_controller->saveMessage();
		if ($message_save_result && !is_array($message_save_result)) {
			header('Location: ./');
		}
		break;
	default:
		break;
}

// displays input form and messages list
echo Registry::getInstance()->get('factory')
	->getController('Main')
	->index($message_save_result);