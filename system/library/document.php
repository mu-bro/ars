<?php
class Document {
	private $title;
	private $description;
	private $keywords;	
	private $links = array();		
	private $styles = array();
	private $scripts = array();

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
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}
	
	public function getKeywords() {
		return $this->keywords;
	}
	
	public function addLink($href, $rel) {
		$this->links[md5($href)] = array(
			'href' => $href,
			'rel'  => $rel
		);			
	}
	
	public function getLinks() {
		return $this->links;
	}	
	
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[md5($href)] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}
	
	public function getStyles() {
		return $this->styles;
	}	
	
	public function addScript($script) {
		$this->scripts[md5($script)] = $script;			
	}
	
	public function getScripts() {
		return $this->scripts;
	}

	public function generateBreadCrumbs($newCrumbs = array()) {

		$breadcrumbs = array();

		$url = (CURRENT_AREA == "A") ? ('token=' . $this->session->data['token']) : "";
		$breadcrumbs[] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', $url, 'SSL'),
			'separator' => false
		);

		foreach($newCrumbs as $br) {
			$breadcrumbs[] = array(
				'text'      => $br['text'],
				'href'      => $br['href'],
				'separator' => ' :: '
			);
		}


		return $breadcrumbs;
	}
	public function getSingleFields($fields) {
		$return = array();
		foreach ($fields as $k => $v) {
			if (!isset($v['multy']))
				$return[$k] = $v;
		}
		return $return;
	}
	public function getMultiFields($fields) {
		$return = array();
		foreach ($fields as $k => $v) {
			if (isset($v['multy']))
				$return[$k] = $v;
		}
		return $return;
	}
	public function getFilteredFields($fields) {
		$return = array();
		foreach ($fields as $k => $v) {
			if (isset($v['filter']))
				$return[$k] = $v;
		}
		return $return;
	}
	public function getSortedFields($fields) {
		$return = array();
		foreach ($fields as $k => $v) {
			if (isset($v['sort']))
				$return[$k] = $v;
		}
		return $return;
	}	
	public function createType($field) {
		$default = ($field['default']) ? "DEFAULT '$field[default]'" : (($field['type'] != "I") ? "DEFAULT NULL" : "DEFAULT '0'");
		switch ($field['type']) {
			case 'I':
				$return = "int(11) NOT NULL";
				break;
			case 'S':
				$return = "varchar(255)";
				break;
			case "D":
				return "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'";
				break;
			case "T":
				$return = "text";
				break;				
		}
		return $return . " " .  $default;
	}
	public function createFilterRequest($field,$data) {
		$search = in_array($field['type'],array('S','T')) ? $this->db->escape($data) : (($field['type'] = "I") ? (int) $data : $data);
		$operator = (in_array($field['type'],array('S','T'))) ? "LIKE '$search%'" : "= $search";
		return $operator;
	}
}
?>