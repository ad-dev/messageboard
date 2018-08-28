<?php
namespace App\Models;

class MessageBoardModel extends Model
{
	protected $db;
	protected $table = 'Messages';

	/**
	 * Gets messages list
	 * @param integer $offset
	 * @param integer $limit
	 * @return array
	 */
	public function getMessagesList($offset, $limit)
	{
		return $this->db->getAll(
			"SELECT * FROM `{$this->table}` ORDER BY `messageDate` DESC",
			[],
			$limit,
			$offset
		);
	}
	
	/**
	 * Gets total messages in DB
	 * @return integer
	 */
	public function getMessagesCount()
	{
		return $this->db->getOne("SELECT COUNT(1) FROM `{$this->table}`");
	}
	
	/**
	 * Inserts new message
	 * @param array $data
	 * @return boolean|integer
	 */
	public function insertMessage(array $data)
	{
		
		if (empty($data['fullname'])) {
			return false;
		}

		$names = explode(' ', $data['fullname']);

		if (count($names) < 2) {
			return false;
		}

		return $this->db->insertRow($this->table, [
			'name' => $names[0],
			'lastName' => $names[1],
			'birthdate' => $data['birthdate'],
			'email' => !empty($data['email']) ? $data['email'] : '',
			'message' => $data['message']
		]);
	}
}