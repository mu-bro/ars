<?php
class ModelFidoNews extends Model {
	public function getNewsStory($news_id) {
		$query = $this->db->query("SELECT DISTINCT *,DATE_FORMAT(date_added,'%d.%m.%Y') as date_added FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'");
		return $query->row;
	}

	public function getNewsNeighbors($news_id) {
		$news_prev = $this->db->query("SELECT nd.title,n.news_id,DATE_FORMAT(n.date_added,'%d.%m.%Y') as date_added FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE n.status = '1' AND n.news_id < '" . (int)$news_id . "' ORDER BY n.news_id DESC")->row;	
		if(isset($news_prev['title']))
			$return['prev'] = $news_prev;
		$news_next = $this->db->query("SELECT nd.title,n.news_id,DATE_FORMAT(n.date_added,'%d.%m.%Y') as date_added FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE n.status = '1' AND n.news_id > '" . (int)$news_id . "' ORDER BY n.news_id")->row;
		if(isset($news_next['title']))
			$return['next'] = $news_next;	
		return (!empty($return)) ? $return : false; 
	}
	
	public function getNews($page,$limit,$type = 'news') {
		$query = $this->db->query("SELECT *,
				DATE_FORMAT(date_added,'%d.%m.%Y') as date_added
		FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' AND n.type = '$type' ORDER BY n.date_added DESC LIMIT $page,$limit");
		return $query->rows;
	}
	public function getEventsList($limit) {
		$query = $this->db->query("SELECT *,
				DATE_FORMAT(ed.date,'%d.%m.%Y') as date_added
		FROM " . DB_PREFIX . "news n
		LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id)
		LEFT JOIN " . DB_PREFIX . "event_dates ed ON (n.news_id = ed.news_id)
		WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' AND n.type = 'events' AND n.image != '' GROUP BY n.news_id ORDER BY RAND() LIMIT $limit");
		return $query->rows;
	}
	public function getEventsFullList() {
		$query = $this->db->query("SELECT *,
				DATE_FORMAT(ed.date,'%d.%m.%Y') as date_added,
				IF (ed.date > NOW(),1,0) as past
		FROM " . DB_PREFIX . "news n
		LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id)
		LEFT JOIN " . DB_PREFIX . "event_dates ed ON (n.news_id = ed.news_id)
		WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' AND n.type = 'events' AND n.image != '' ORDER BY ed.date");
		return $query->rows;
	}	
	public function getNewsShorts($limit,$type = 'news') {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n
		LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id)
		LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id)
		WHERE
			nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
			n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND
			n.status = '1' AND
			n.type = '". $type ."'
		ORDER BY n.date_added DESC LIMIT " . (int)$limit);
		
		return $query->rows;
	}

	public function getTotalNews($type = 'news') {
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id) WHERE n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' AND n.type = '$type'");
		if ($query->row) {
			return $query->row['total'];
		} else {
			return FALSE;
		}
	}	
}
?>
