<?php
class mhelper {

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->session = $registry->get('session');
		$this->url = $registry->get('url');
		$this->language = $registry->get('language');
		$this->db = $registry->get('db');

		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
			$this->session->data['cart'] = array();
		}
	}


	public function getPositions() {
		$positions = array(
			"content_header" => $this->language->get('text_content_header'),
			"content_top" => $this->language->get('text_content_top'),
			"column_left" => $this->language->get('text_column_left'),
			"column_right" => $this->language->get('text_column_right'),
			"content_bottom" => $this->language->get('text_content_bottom'),
 			"content_footer" => $this->language->get('text_content_footer')
		);
		return $positions;
	}

	public function createPositionSelect ($currentPosition) {
		$positions = $this->getPositions();
		$html = "";
		foreach ($positions as $position => $name) {
			$selected = ($position == $currentPosition) ? "selected='selected'" : "";
			$html .= "<option value=\"$position\" $selected>$name</option>";
		}
		return $html;
	}

	public function getLayouts() {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout`")->rows;
	}

	public function createLayoutSelect($currentLayout) {
		$layouts = $this->getLayouts();
		$selected = ("0" == $currentLayout) ? "selected='selected'" : "";
		$html = "<option value=\"0\" $selected>All pages</option>";
		foreach ($layouts as $layout) {
			$selected = ($layout['layout_id'] == $currentLayout) ? "selected='selected'" : "";
			$html .= "<option value=\"$layout[layout_id]\" $selected>$layout[name]</option>";
		}
		return $html;		
	}

}
?>