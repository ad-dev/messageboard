<?php

namespace Code;

use \PDO;
/**
 *
 * This class interacts with DB
 *
 * @author ad
 *
 */
class Database
{
	
	private $db_host = '';
	private $db_user = '';
	private $db_pass = '';
	private $db_name = '';
	/**
	 *
	 * Internal DB object variable
	 * @var object
	 */
	private static $db = null;

	public function __construct($db_host, $db_user, $db_pass, $db_name)
	{
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_name = $db_name;
		$this->connectDB();
	}

	/**
	 * Forms Data Source Name (DSN)
	 * @param string $driver
	 * @param string $host
	 * @param string $db_name
	 * @return string
	 */
	private function getDSN($driver, $host, $db_name)
	{
		return $driver . ':' . implode(';', array('host='.$host, 'dbname='.$db_name));
	}
	
	/**
	 *
	 * Connect to DB
	 */
	private function connectDB()
	{
		self::$db = new PDO($this->getDSN('mysql', $this->db_host, $this->db_name), $this->db_user, $this->db_pass);
		return self::$db;
	}

	/**
	 * This method initiates or returns previously initiated database connection
	 * @return object|\PDO
	 */
	
	private function getDB()
	{
		if (self::$db instanceof PDO) {
			return self::$db;
		}
		return $this->connectDB();
	}
	
	/**
	 * Applies offset and limit to query (adds LIMIT $limit, $offset)
	 * @param string $query
	 * @param int $limit
	 * @param int $offset
	 * @return string
	 */
	private function applyLimitAndOffset($query, $limit, $offset)
	{
		if (isset($limit)) {
			$limitStr = ' LIMIT ' . ($offset ? (int)$offset : '0') . ', ' . (int)$limit;
			$query .= $limitStr;
		}
		return $query;
	}

	/**
	 *
	 * Gets value of first record's first field
	 * @param string $query
	 * @param array $params
	 */
	function getOne($query, $params = null)
	{
		$params = $this->parseBindParams($query, $params);
		$stmt = $this->getDB()->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute($params);
		
		$row = $stmt->fetch();
		
		if (isset($row) && is_array($row)) {
			return reset($row);
		} else {
			return false;
		}
		
	}
	/**
	 * Escapes string
	 * @param string $string
	 * @param boolean $noQuotes
	 * @return string
	 */
	public function escapeString($string, $noQuotes = false)
	{
		$escapedString = $this->getDB()->quote($string);
		if ($noQuotes) {
			$escapedString = substr($escapedString, 1, -1);
		}
		return $escapedString;
	}
	/**
	 * Gets last insert ID
	 * @return integer
	 */
	public function getLastInsertId()
	{
		return $this->getDB()->lastInsertId();
	}

	/**
	 *
	 * Gets all records from DB
	 *
	 * @param string $query
	 * @param array $params
	 * @param string $indexColumn
	 * @param bool $groupByIndexes
	 * @param bool $singleQuotes
	 */
	public function getAll($query, $params = null,$limit = 0, $offset = 0)
	{
		$result = array();
		$i = 0;
		$query = $this->applyLimitAndOffset($query, $limit, $offset);
		$params = $this->parseBindParams($query, $params);
		$stmt = $this->getDB()->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute($params);
		$data = $stmt->fetchAll();
		if ($data) {
			foreach ($data as $rs) {
				$keys = array_keys($rs);
				foreach ($keys as $key) {
					$result[$i][$key] = $rs[$key];
				}
				$i++;
			}
		}
		$this->lastQueryRowsCount = $stmt->rowCount();
		$this->executedQuery = $stmt->queryString;
		return $result;
	}
	
	public function getLastQueryRecordsCount() {
		
		return $this->lastQueryRowsCount;
	}
	/**
	 *
	 * Executes DB query
	 * @param string $query
	 * @param array $params
	 * @param bool $singleQuotes
	 */
	public function query($query, $params = null, $singleQuotes = true)
	{
		if (isset($params)) {
		}
		$params = $this->parseBindParams($query, $params);
		$stmt = $this->getDB()->prepare($query);
		$stmt->execute($params);
		return $stmt->rowCount();
	}
	
	protected function parseSQLFields($fields)
	{
		$fieldsList = array();
		if (!empty($fields) && is_array($fields)) {
			foreach ($fields as $key => $value) {
				$fieldsList[] = '`' . $key . '` = ' . $this->escapeString($value);
			}
		}
		return $fieldsList;
	}

	private function parseBindParams(& $query, $params)
	{
		if (isset($params) && is_array($params)) {
			foreach ($params as $p0 => $param) {
				if (is_array($param)) {
					$arrayElementsToBind = array();
					$additionalBinds = array();
					foreach ($param as $pName => $p) {
						$bindName = $p0 . '_' . $pName;
						$arrayElementsToBind[] = $bindName;
						$additionalBinds[$bindName] = $p;
					}
					unset($params[$p0]);
					$query =
					str_replace($p0, implode(',', $arrayElementsToBind),
						$query);
					$params = array_merge($params, $additionalBinds);
				}
			}
		}
		return $params;
	}

	public function insertRow($table, $row)
	{
		if (empty($row)) {
			return false;
		}
		$sql = 'INSERT IGNORE INTO `'
			. $this->escapeString($table, true)
			. '` SET ' . implode(',' , $this->parseSQLFields($row));
			if ($this->query($sql)) {
				return $this->getLastInsertId();
			}
			return false;
	}
}