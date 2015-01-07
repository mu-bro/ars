<?php
class ModelCatalogRequest extends Model {
	public function DBconfig($entity,$fields) {
		$db_name = explode('/', $entity);$db_name = end($db_name);

		# single fields
		$sql_fileds = array();
		$sql_fileds[]= "`" . $db_name ."_id` int(11) NOT NULL AUTO_INCREMENT";
		foreach ($this->document->getSingleFields($fields) as $k => $v) {
			$sql_fileds[] = "`$k` " . $this->document->createType($v);
		}
		$sql = "CREATE TABLE IF NOT EXISTS `".$db_name."` (
			". implode(', ',$sql_fileds) .",
			PRIMARY KEY (`" . $db_name ."_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		$this->db->query($sql);

		# multi fields
		$multyFields = $this->document->getMultiFields($fields);
		if (!empty($multyFields)) {
			$sql_fileds = array();
			$sql_fileds[]= "`" . $db_name ."_id` int(11) NOT NULL";
			$sql_fileds[]= "`language_id` int(11) NOT NULL";
			foreach ($multyFields as $k => $v) {
				$sql_fileds[] = "`$k` " . $this->document->createType($v);
			}
			$sql = "CREATE TABLE IF NOT EXISTS `".$db_name."_description` (
				". implode(', ',$sql_fileds) .",
				PRIMARY KEY (`" . $db_name ."_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
			$this->db->query($sql);
		}

	}
	public function saveOrUpdate($entity,$fields,$data, $request_id = false) {
		$db_name = explode('/', $entity);$db_name = end($db_name);
		$action = ($request_id) ? "UPDATE " : "INSERT INTO ";

		#single fields
		$sql_fileds = array();
		foreach ($this->document->getSingleFields($fields) as $k => $v) {
			if (isset($data[$k])) {
				$sql_fileds[] = "`$k` = " . $this->db->escape($data[$k]);
			}
		}		
		$sql = $action . "`" . DB_PREFIX . "$db_name` SET " . implode(', ',$sql_fileds);
		$sql .= ($request_id) ? " WHERE $db_name = '" . (int)$request_id . "' " : "";
		$this->db->query($sql);

		$id = ($id) ? $id : $this->db->getLastId();

		#multy fields
		$multyFields = $this->document->getMultiFields($fields);
		if (!empty($multyFields)) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "".$db_name."_description` WHERE `" . $db_name ."_id` = '" . (int)$id . "'");
			foreach ($data[$db_name . '_description'] as $language_id => $value) {
				$sql_fileds = array();
				$sql_fileds[] = "`language_id` = " . $language_id;
				$sql_fileds[] = "`" . $db_name ."_id` = " . $id;
				foreach ($multyFields as $k => $v) {
					if (isset($value[$k])) {
						$sql_fileds[] = "`$k` = " . $this->db->escape($value[$k]);
					}
				}
				$sql = "INSERT INTO ``" . DB_PREFIX . "".$db_name."_description` SET " . implode(', ',$sql_fileds);
				$sql .= ($request_id) ? " WHERE $db_name = '" . (int)$request_id . "' " : "";
				$this->db->query($sql);
			}
		}
	}

	public function deleteVacancy($request_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "requests WHERE request_id = '" . (int)$request_id . "'");
	}	

	public function getVacancy($request_id) {
		$query = $this->db->query("SELECT DISTINCT * WHERE request_id = '" . (int)$request_id . "'");

		return $query->row;
	}

	public function getList($data = array(),$entity,$fields) {
		$db_name = explode('/', $entity);$db_name = end($db_name);
		$sql = "SELECT * FROM `" . DB_PREFIX . "$db_name` eS

		WHERE 1=1";

		$filter = $this->document->getFilteredFields($fields);

		foreach ($filter as $k => $v) {
			if (isset($data['filter_' . $k]) && !empty($data['filter_' . $k])) {
				$prefics = (isset($v['multy'])) ? "eM" : "eS";
				$sql .= " AND `$prefics.$k` " . $this->document->createFilterRequest($v,$data['filter_' . $k]);
			}
		}

		$sortFields = $this->document->getFilteredFields($fields);
		$sort_data = array_keys($this->document->getFilteredFields($fields));	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$prefics = (isset($sortFields[$data['sort']]['multy'])) ? "eM" : "eS";
			$sql .= " ORDER BY $prefics." . $data['sort'];	
		} else {
			$sql .= " ORDER BY eS." . $db_name . "_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}					

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}				
p($sql);
		$query = $this->db->query($sql);


		return $query->rows;
	}

	public function getTotalVacancysByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "requests WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	public function getTotalList($entity) {
		$db_name = explode('/', $entity);$db_name = end($db_name);
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "$db_name`");
		return $query->row['total'];
	}	
}
?>