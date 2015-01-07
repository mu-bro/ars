<?php
class ModelFidoNews extends Model {
	public function addNews($data,$type = 'news') {
		$this->db->query("INSERT INTO " . DB_PREFIX . "news SET status = '" . (int)$data['status'] . "', 
		date_available = '" . $this->db->escape($data['date_available']) . "',
		type = '" . $this->db->escape($type) . "',
		date_added = now() ");
		$news_id = $this->db->getLastId();
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET
			news_id = '" . (int)$news_id . "',
			language_id = '" . (int)$language_id . "',
			title = '" . $this->db->escape($value['title']) . "',
			meta_description = '" . $this->db->escape($value['meta_description']) . "',
			short_descr = '" . $this->db->escape($value['short_descr']) . "',
			description = '" . $this->db->escape($value['description']) . "'");
		}

		if ($type != 'events') {
			if (isset($data['date_available'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "news SET
				date_available = '" . $this->db->escape($data['date_available']) . "',
				date_added = '" . $this->db->escape($data['date_added']) . "'
				WHERE news_id = '" . (int)$news_id . "'");
			}
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "event_dates WHERE news_id='" . (int)$news_id. "'");
			foreach ($data['date_added'] as $v) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "event_dates SET news_id = '" . (int)$news_id . "', date = '". $v ."'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '0'");

		$this->cache->delete('news');
	}

	public function editNews($news_id, $data, $type = 'news') {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET
			status = '" . (int)$data['status'] . "',
			type = '" . $this->db->escape($type) . "'
		WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		if ($type != 'events') {
			if (isset($data['date_available'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "news SET
				date_available = '" . $this->db->escape($data['date_available']) . "',
				date_added = '" . $this->db->escape($data['date_added']) . "'
				WHERE news_id = '" . (int)$news_id . "'");
			}
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "event_dates WHERE news_id='" . (int)$news_id. "'");
			foreach ($data['date_added'] as $v) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "event_dates SET news_id = '" . (int)$news_id . "', date = '". $v ."'");
			}
		}
		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET
			news_id = '" . (int)$news_id . "',
			language_id = '" . (int)$language_id . "',
			short_descr = '" . $this->db->escape($value['short_descr']) . "',
			title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id. "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '0'");
		
		$this->cache->delete('news');
	}
	public function getEventDates($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event_dates WHERE news_id = '" . (int)$news_id . "' ORDER BY date")->rows;
		$return = array();
		foreach ($query as $v) {
			$return[] = $v['date'];
		}
		return $return;
	}
	public function deleteNews($news_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		$this->cache->delete('news');
	}	

	public function getNewsStory($news_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "') AS keyword FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		return $query->row;
	}

	public function getNewsDescriptions($news_id) {
		$news_description_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		foreach ($query->rows as $result) {
			$news_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'meta_description' => $result['meta_description'],
				'short_descr'      => $result['short_descr'],
				'description'      => $result['description']
			);
		}
		return $news_description_data;
	}

	public function getNewsStores($news_id) {
		$newspage_store_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		foreach ($query->rows as $result) {
			$newspage_store_data[] = $result['store_id'];
		}
		return $newspage_store_data;
	}

	public function getNews($type = 'news') {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.type = '$type' ORDER BY n.date_added");
		return $query->rows;
	}

	public function getTotalNews($type = 'news') {
		$this->checkNews();
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news WHERE type = '$type'");
		return $query->row['total'];
	}	

	public function checkNews() {
		$create_news = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news` (`news_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `date_available` DATE,`image` varchar(255) collate utf8_bin default NULL, `image_size` int(1) NOT NULL default '0', `date_added` datetime default NULL, PRIMARY KEY  (`news_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_news);
		$create_news_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_description` (`news_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` varchar(64) collate utf8_bin NOT NULL default '', `meta_description` varchar(255) collate utf8_bin NOT NULL, `description` text collate utf8_bin NOT NULL, PRIMARY KEY  (`news_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_news_descriptions);
		$create_news_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_to_store` (`news_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY  (`news_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_news_to_store);
	}
}
?>