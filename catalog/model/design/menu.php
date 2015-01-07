<?php
class ModelDesignMenu extends Model {
	public function getMenu($menu_id) {
		$query = $this->db->query($qwe = "SELECT m.*,mr.*,mrd.*,m2.color as color_,m2.column_width as column_width_ FROM " . DB_PREFIX . "menu m 				
				LEFT JOIN " . DB_PREFIX . "menu_route mr ON mr.menu_id = m.menu_id
				LEFT JOIN " . DB_PREFIX . "menu m2 ON mr.submenu = m2.menu_id
				LEFT JOIN " . DB_PREFIX . "menu_route_description mrd 
					ON (mr.menu_route_id  = mrd.menu_route_id) 
				WHERE 
					mr.menu_id = '" . (int)$menu_id . "' AND 
					mrd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				ORDER BY mr.sort_order");
		return $query->rows;
	}
	
	public function getMenuInfo($menu_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$menu_id . "' LIMIT 1");
		return $query->row;
	}
	
}
?>