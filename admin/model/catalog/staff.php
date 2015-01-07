<?php
class ModelCatalogStaff extends Model {
	public function saveOrUpdate($data, $staff_id = false) {
		$action = ($staff_id) ? "UPDATE " : "INSERT INTO ";
		
		$sql = $action . DB_PREFIX . "staff SET
		name = '" . $this->db->escape($data['name']) . "',
		descr = '" . $this->db->escape($data['descr']) . "',
		short_descr = '" . $this->db->escape($data['short_descr']) . "',
		sort_order = '" . (int)$data['sort_order'] . "'";

		$sql .= ($staff_id) ? " WHERE staff_id = '" . (int)$staff_id . "' " : "";

		$this->db->query($sql);

		$staff_id = ($staff_id) ? $staff_id : $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "staff SET
			image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "'
			WHERE staff_id = '" . (int)$staff_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'staff_id=" . (int)$staff_id. "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
			SET query = 'staff_id=" . (int)$staff_id . "',
			keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('staff');
	}

	public function deleteStaff($staff_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "staff WHERE staff_id = '" . (int)$staff_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'staff_id=" . (int)$staff_id . "'");

		$this->cache->delete('staff');
	}	

	public function getStaff($staff_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'staff_id=" . (int)$staff_id . "') AS keyword FROM " . DB_PREFIX . "staff WHERE staff_id = '" . (int)$staff_id . "'");

		return $query->row;
	}

	public function getStaffs($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "staff";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalStaffsByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "staff WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	public function getTotalStaffs() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "staff");

		return $query->row['total'];
	}	
}
?>