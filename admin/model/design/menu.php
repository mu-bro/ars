<?php
class ModelDesignMenu extends Model {
	public function addMenu($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "menu SET 
		name = '" . $this->db->escape($data['name']) . "', 
		column_limit = '" . (int) $data["column_limit"] . "',
		color = '" . $this->db->escape($data['color']) . "', 
		column_width = '" . (int) $data["column_width"] . "', 
		style_id = '" . $this->db->escape( $data["style_id"] ) . "'");
	
		$menu_id = $this->db->getLastId();
		
		if (isset($data['menu_route'])) {
			foreach ($data['menu_route'] as $menu_route) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_route SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$menu_route['store_id'] . "', menu_type = '" . $this->db->escape($menu_route['menu_type']) . "', menu_type_2 = '" . $this->db->escape($menu_route['menu_type_2']) . "', route = '" . $this->db->escape($menu_route['route']) . "', image = '" . $this->db->escape($menu_route['image']) . "', category = '" . (int) $menu_route['category'] . "', submenu = '" . (int) $menu_route['submenu'] . "', information = '" . (int) $menu_route['information'] . "', sort_order = '" . (int) $menu_route['sort_order'] . "'");
			
				$menu_route_id = $this->db->getLastId();
				
				foreach ($menu_route['menu_route_description'] as $language_id => $menu_route_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "menu_route_description SET menu_id = '" . (int) $menu_id . "', menu_route_id = '" . (int) $menu_route_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($menu_route_description['name']) . "', html = '" . $this->db->escape($menu_route_description['html']) . "'");
				}
				
			}
		}
	}
	
	public function editMenu($menu_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "menu SET name = '" . $this->db->escape($data['name']) . "', 
		column_limit = '" . $this->db->escape($data['column_limit']) . "',
		color = '" . $this->db->escape($data['color']) . "', 
		column_width = '" . (int) $data["column_width"] . "', 
		style_id = '" . $this->db->escape($data['style_id']) . "'  WHERE menu_id = '" . (int)$menu_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_route WHERE menu_id = '" . (int)$menu_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_route_description WHERE menu_id = '" . (int)$menu_id . "'");
		
		if (isset($data['menu_route'])) {
			foreach ($data['menu_route'] as $menu_route) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_route SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$menu_route['store_id'] . "', menu_type = '" . $this->db->escape($menu_route['menu_type']) . "', menu_type_2 = '" . $this->db->escape($menu_route['menu_type_2']) . "', route = '" . $this->db->escape($menu_route['route']) . "', image = '" . $this->db->escape($menu_route['image']) . "', category = '" . (int) $menu_route['category'] . "', submenu = '" . (int) $menu_route['submenu'] . "', information = '" . (int) $menu_route['information'] . "', sort_order = '" . (int) $menu_route['sort_order'] . "'");
			
				$menu_route_id = $this->db->getLastId();
				
				foreach($menu_route['menu_route_description'] as $language_id => $menu_route_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "menu_route_description SET menu_id = '" . (int) $menu_id . "', menu_route_id = '" . (int) $menu_route_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($menu_route_description['name']) . "', html = '" .  $this->db->escape($menu_route_description['html']) . "'");
				}
			
			}
		}
	}
	
	public function deleteMenu($menu_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_route WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_route_description WHERE menu_id = '" . (int)$menu_id . "'");
	}
	
	public function getMenu($menu_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$menu_id . "'");
		
		return $query->row;
	}
		 
	public function getMenus($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "menu";
		
		$sort_data = array('name');
		
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
	
	public function getMenuRoutes($menu_id) {
		$menu_route_data = array();
	
		$menu_route_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_route WHERE menu_id = '" . (int)$menu_id . "' ORDER BY sort_order");
		
		foreach ($menu_route_query->rows as $menu_route) {
			$menu_route_description_data = array();
			 
			$menu_route_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_route_description WHERE menu_route_id = '" . (int)$menu_route['menu_route_id'] . "' AND menu_id = '" . (int)$menu_id . "'");
			
			foreach ($menu_route_description_query->rows as $menu_route_description) {
				$menu_route_description_data[$menu_route_description['language_id']] = array(
					'name' => $menu_route_description['name'],
					'html' => $menu_route_description['html']
				);
			}

			$menu_route_data[] = array(
				"menu_route_id" => $menu_route["menu_route_id"],
				"menu_id" => $menu_route["menu_id"],
				"store_id" => $menu_route["store_id"],
				"menu_type" => $menu_route["menu_type"],
				"menu_type_2" => $menu_route["menu_type_2"],
				"route" => $menu_route["route"],
				"information" => $menu_route["information"],
				"category" => $menu_route["category"],
				"image" => $menu_route["image"],
				"submenu" => $menu_route["submenu"],
				"sort_order" => $menu_route["sort_order"],
				'menu_route_description'   => $menu_route_description_data
			);
		}

		return $menu_route_data;
	}
		
	public function getTotalMenus() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu");
		
		return $query->row['total'];
	}
}
?>