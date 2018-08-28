<?php
namespace App\Controllers;

use App\Registry as Registry;
use App\MessageValidator;
use App\MessagePaging;
use Code\Age;

class MainController extends Controller
{
	/**
	 * Forms messages list (html)
	 * @return string
	 */
	public function getMessagesListHtml()
	{
		$messages_total = Registry::getInstance()->get('factory')
		->getModel('MessageBoard')->getMessagesCount();
		
		$messagePaging = new MessagePaging(MessagePaging::ITEMS_PER_PAGE, $messages_total);

		$messages_html = '';
		$messages_list = $this->getMessagesList($messagePaging);
		
		$messages_view = Registry::getInstance()->get('factory')->getView('messagesContainer');
		
		if (!empty($messages_list) && is_array($messages_list)) {
			$message_view = Registry::getInstance()->get('factory')->getView('message');
			
			foreach ($messages_list as $message) {
				$fullname = $message['name'] . ' ' . $message['lastName'];
				if (!empty($message['email'])) {
					$fullname = '<a href="mailto:' . $message['email'] . '">' . $fullname . '</a>';
				}
				$message_view->setTags([
					'fullname' => $fullname,
					'age' => Age::get($message['birthdate']),
					'message_date' => date('Y-m-d H:i', strtotime($message['messageDate'])),
					'message' => $message['message'],
				]);
				$messages_html .= $message_view->render();
			}
			$messages_view->addTag('messages_list', $messages_html);
			$messages_view->addTag('paging', $messagePaging->getPaginationString());
			return $messages_view->render();
		}
		return Registry::getInstance()->get('factory')->getView('noMessages')->render();
	}

	/**
	 * Index page: form + messages list
	 * @param array|bool $validation_errors
	 * @return string
	 */
	public function index($validation_errors = false)
	{
		// get index view (form and messages list page)
		$index_view = Registry::getInstance()->get('factory')->getView('Index');

		$index_view->setTags([
			'fullname' => ! empty($this->_post['fullname']) ? $this->_post['fullname'] : '',
			'birthdate' => ! empty($this->_post['birthdate']) ? $this->_post['birthdate'] : '',
			'email' => ! empty($this->_post['email']) ? $this->_post['email'] : '',
			'message' => ! empty($this->_post['message']) ? $this->_post['message'] : ''
 		]);
		
		if (!empty($validation_errors) && is_array($validation_errors)) {
			foreach ($validation_errors as $key => $value) {
				$index_view->addTag('error_class_' . $key, 'err');
			}
		}

		$messages_html = $this->getMessagesListHtml();

		if (!empty($messages_html)) {
			$index_view->addTag('list', $messages_html);
		}
		return $index_view->render();
	}
	
	/**
	 * Saves new message
	 * @return bool|array
	 */
	public function saveMessage()
	{
		$post = $this->getHttpPostData();

		$validator = new MessageValidator();
		$validator->addUserInput($post);
		
		if ($validator->validate()) {
			return Registry::getInstance()->get('factory')
			->getModel('MessageBoard')
			->insertMessage($post);
		} else {
			return $validator->getFailedInputs();
		}
	}

	/**
	 * Gets messages list of according to paging
	 * @return array
	 */
	public function getMessagesList(MessagePaging $messagePaging)
	{
		$get = $this->getHttpGetData();

		$page = !empty($get[$messagePaging->getPageHandler()])
		? $get[$messagePaging->getPageHandler()]
			: $messagePaging->getFirstPageNo();
		
		if ($page > $messagePaging->getPagesTotal()) {
			$page = $messagePaging->getFirstPageNo();
		}
		
		$messagePaging->setCurrentPage($page);
		return Registry::getInstance()->get('factory')
			->getModel('MessageBoard')
			->getMessagesList($messagePaging->getOffset(), $messagePaging->getItemsPerPage());
	}
}