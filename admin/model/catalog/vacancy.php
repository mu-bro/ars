<?php
class ModelCatalogVacancy extends Model {
	public function saveOrUpdate($data, $vacancy_id = false) {
		$action = ($vacancy_id) ? "UPDATE " : "INSERT INTO ";
		
		$sql = $action . DB_PREFIX . "vacancy SET
		name = '" . $this->db->escape($data['name']) . "',
		descr = '" . $this->db->escape($data['descr']) . "',
		short_descr = '" . $this->db->escape($data['short_descr']) . "',
		sort_order = '" . (int)$data['sort_order'] . "'";

		$sql .= ($vacancy_id) ? " WHERE vacancy_id = '" . (int)$vacancy_id . "' " : "";

		$this->db->query($sql);

		$vacancy_id = ($vacancy_id) ? $vacancy_id : $this->db->getLastId();

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'vacancy_id=" . (int)$vacancy_id. "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
			SET query = 'vacancy_id=" . (int)$vacancy_id . "',
			keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('vacancy');
	}

	public function deleteVacancy($vacancy_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "vacancy WHERE vacancy_id = '" . (int)$vacancy_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'vacancy_id=" . (int)$vacancy_id . "'");

		$this->cache->delete('vacancy');
	}	

	public function getVacancy($vacancy_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'vacancy_id=" . (int)$vacancy_id . "') AS keyword FROM " . DB_PREFIX . "vacancy WHERE vacancy_id = '" . (int)$vacancy_id . "'");

		return $query->row;
	}

	public function getVacancys($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "vacancy";

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

	public function getTotalVacancysByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vacancy WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	public function getTotalVacancys() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vacancy");

		return $query->row['total'];
	}	
}
?>