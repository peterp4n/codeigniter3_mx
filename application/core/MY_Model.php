<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_sql_position($file, $line)
	{
		$position = substr($file, strlen($_SERVER['DOCUMENT_ROOT'])).' : '.$line;
		return " /* CI3 : $position */ ";
	}

	public function get_query($param)
	{
		$table = $param['table'];
		$whereis = $param['whereis'];
		$limit = $param['limit'];
		$offset = $param['offset'];
		$query = $this->db->get_where($table, $whereis, $limit, $offset);
		return $query;
	}


	/*
	 * $data = 배열일때만 처리
	 */
	public function insert($table, $data='')
	{
		if (! empty($data)) {
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	 * $data, $where = 배열일때만 처리
	 */
	public function update($table, $data='', $where='')
	{
		if (! empty($data)) {
			if (! empty($where)) {
				$this->db->where($where);
			}
			$this->db->set($data);
			$this->db->update($table);
			return $this->db->affected_rows();
		} else {
			return false;
		}
	}

	public function delete($table, $where='')
	{
		if ($where) {
			$this->db->where($where);
		}
		$result = $this->db->delete($table);

		return $result;
	}
}
