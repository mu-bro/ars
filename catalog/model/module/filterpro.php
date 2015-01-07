<?php
class ModelModuleFilterPro extends Model {
	private $filterpro_setting;

	public function __construct($registry) {
		parent::__construct($registry);

		$this->filterpro_setting = $this->config->get('filterpro_setting');
		if(VERSION == '1.5.0') {
			$this->filterpro_setting = unserialize($this->config->get('filterpro_setting'));
		}
	}


	public function getAttributesByCategoryId($category_id) {
		$sql = "SELECT DISTINCT pa.text, a.`attribute_id`, ad.`name`, ag.attribute_group_id, agd.name as attribute_group_name FROM `" . DB_PREFIX . "product_attribute` pa" .
			   " LEFT JOIN " . DB_PREFIX . "attribute a ON(pa.attribute_id=a.`attribute_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_description ad ON(a.attribute_id=ad.`attribute_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_group ag ON(ag.attribute_group_id=a.`attribute_group_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON(agd.attribute_group_id=ag.`attribute_group_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "product p ON(p.product_id=pa.`product_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(p.product_id=p2c.product_id) " .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) " .
			   " WHERE  p.status = '1' AND p.date_available <= NOW() AND p2s.store_id =" . (int)$this->config->get('config_store_id');
		if($category_id) {
			$sql .= " AND p2c.category_id = '" . (int)$category_id . "'";
		}

		$sql .= " AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
				" AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
				" AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
				" ORDER BY ag.sort_order, agd.name, a.sort_order, ad.name, pa.text";

		$query = $this->db->query($sql);

		$attributes = array();
		foreach($query->rows as $row) {
			if(!isset($attributes[$row['attribute_group_id']])) {
				$attributes[$row['attribute_group_id']] = array(
					'name' => $row['attribute_group_name'],
					'attribute_values' => array()
				);
			}

			if(!isset($attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']])) {
				$attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']] = array('name' => $row['name'], 'values' => array());
			}
			foreach(explode($this->filterpro_setting['attr_delimeter'], $row['text']) as $text){
				if (!in_array($text, $attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']]['values'])){
					$attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']]['values'][] = $text;
				}
			}
		}

		return $attributes;
	}

	public function getManufacturersByCategoryId($category_id) {
		$sql = "SELECT DISTINCT m.`manufacturer_id`, m.`name` FROM `" . DB_PREFIX . "manufacturer` m" .
			   " LEFT JOIN " . DB_PREFIX . "product p ON(p.manufacturer_id=m.`manufacturer_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(p.product_id=p2c.product_id) " .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) " .
			   " WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id =" . (int)$this->config->get('config_store_id');
		if($category_id) {
			$sql .= " AND p2c.category_id = '" . (int)$category_id . "'";
		}
		$sql .= " ORDER BY m.sort_order, m.name";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getOptionsByCategoryId($category_id) {
		$sql = "SELECT DISTINCT ovd.option_value_id, ovd.*, od.name as 'option_name' FROM `" . DB_PREFIX . "option_value_description` ovd
    LEFT JOIN " . DB_PREFIX . "option_value ov ON(ovd.option_value_id=ov.option_value_id)
    LEFT JOIN " . DB_PREFIX . "option_description od ON(ov.option_id=od.option_id)
    LEFT JOIN " . DB_PREFIX . "product_option_value pov ON(ovd.`option_value_id`=pov.`option_value_id`)
    LEFT JOIN " . DB_PREFIX . "product p ON(pov.product_id = p.product_id)
    LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(p.product_id = p2c.product_id)
	LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id)
	WHERE category_id = '" . (int)$category_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id =" . (int)$this->config->get('config_store_id');

		if($category_id) {
			$sql .= " AND p2c.category_id = '" . (int)$category_id . "'";
		}
		$sql .= " ORDER BY ovd.option_id, ov.sort_order";
		$query = $this->db->query($sql);
		$options = array();
		foreach($query->rows as $row) {
			if(!isset($options[$row['option_id']])) {
				$options[$row['option_id']] = array('option_id' => $row['option_id'],
													'name' => $row['option_name'],
													'option_values' => array());
			}

			$options[$row['option_id']]['option_values'][] = array('option_value_id' => $row['option_value_id'], 'name' => $row['name']);
		}
		return $options;
	}

	public function getPriceLimits($data) {

		$customer_group_id = $this->getCustomerGroup();

		$sql = "SELECT max(coalesce((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1), " .
			   "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1), " .
			   "p.price) ) AS max_price, min(coalesce((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1), " .
			   "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1), " .
			   "p.price) ) AS min_price FROM " . DB_PREFIX . "product p" .
			   " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id=p.product_id)";

		$sql .= " WHERE p.status = '1' AND p.date_available <= NOW( ) AND p2s.store_id = " . (int)$this->config->get('config_store_id');
		if($data['category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
		}
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTotalProducts($data) {

		$customer_group_id = $this->getCustomerGroup();

		$sql = "SELECT count(*) as total FROM " .
			   "(SELECT DISTINCT p.product_id, coalesce((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1), " .
			   "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1), " .
			   " p.price) as realprice " .
			   " FROM " . DB_PREFIX . "product p" .
			   " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id=p.product_id)";
		if($data['attribute_value']) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute p2a ON (p2a.product_id=p.product_id)";
		}

		$sql .= " WHERE 1 ";
		if($data['category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
		}

		$option_filters = array();
		if($data['option_value']) {
			foreach($data['option_value'] as $option_value) {
				$option_filters[] = "option_value_id IN(" . implode(",", $option_value) . ")";
			}
		}

		if($option_filters) {
			if($this->filterpro_setting['option_mode'] == 'and') {
				foreach($option_filters as $i => $option_filter) {
					$sql .= " AND EXISTS (select 1 FROM " . DB_PREFIX . "product_option_value pov" . $i . " WHERE pov" . $i . ".product_id=pov.product_id AND pov" . $i . "." . $option_filter . ") ";
				}
			} else {
				$sql .= " AND (" . implode(" OR ", $option_filters) . ")";
			}
		}

		if($data['manufacturer']) {
			$sql .= " AND p.manufacturer_id IN(" . implode(", ", $data['manufacturer']) . ")";
		}

		if($data['attribute_value']) {
			if($this->filterpro_setting['attribute_mode'] == 'and') {
				$i = 0;
				foreach($data['attribute_value'] as $attribute_id => $values) {
					$sql .= " AND EXISTS
								(select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=p2a.product_id AND p2a" . $i . ".attribute_id = " . (int)$attribute_id . " ";
					if (isset($values['min']) || isset($values['max'])) {
						$sql .= " AND p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$sql .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$sql .= " AND p2a.text <= " . $values['max'];
						}
						$sql .= ")";
					} else {
						$sql .= "AND (p2a" . $i . ".text like '%" . implode("%' OR p2a" . $i . ".text like '%", array_map(array($this->db, 'escape'), $values)) . "%')) ";
					}
					$i++;
				}
			} else {
				foreach($data['attribute_value'] as $attribute_id => $values) {
					if (isset($values['min']) || isset($values['max'])) {
						$attr_filter = "p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$attr_filter .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$attr_filter .= " AND p2a.text <= " . $values['max'];
						}						
						$attribute_filters[] = $attr_filter;
					} else {
						$attribute_filters[] = "p2a.attribute_id = " . (int)$attribute_id . " AND (p2a.text like '%" . implode("%' OR p2a.text like '%", array_map(array($this->db, 'escape'), $values)) . "%')";
					}
				}
				$sql .= " AND (" . implode(" OR ", $attribute_filters) . ")";
			}
		}

		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND p.price >=" . (int)$min_price;
		}
		if($data['max_price'] > 0) {
			$max_price = $this->currency->convert($data['max_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND p.price <=" . ($max_price);
		}

		if(!empty($data['product_id'])) {
			$sql .= " AND p.product_id LIKE '%" . $data['product_id'] . "%'";
		}

		$sql .= " AND p.status = '1' AND p.date_available <= NOW( ) AND p2s.store_id = " . (int)$this->config->get('config_store_id');
		$sql .= ") as innertable WHERE 1 ";
		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice >=" . (int)$min_price;
		}
		if($data['max_price'] > 0) {
			$max_price = $this->currency->convert($data['max_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice <=" . ($max_price);
		}
		
		$query = $this->db->query($sql);
//echo $sql;die;
		return $query->row['total'];
	}

	private function getCustomerGroup() {
		if($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
			return $customer_group_id;
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			return $customer_group_id;
		}
	}

	public function getTotalManufacturers($data) {

		$customer_group_id = $this->getCustomerGroup();

		$sql = "SELECT count(*) as total, manufacturer_id FROM " .
			   "(SELECT DISTINCT p.product_id, m.manufacturer_id, coalesce((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1), " .
			   "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1), " .
			   "p.price) as realprice " .
			   " FROM " . DB_PREFIX . "product p" .
			   " LEFT JOIN " . DB_PREFIX . "manufacturer m ON(m.manufacturer_id=p.manufacturer_id) " .
			   " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id=p.product_id)";
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute p2a ON (p2a.product_id=p.product_id)";
		$sql .= " WHERE 1";

		if($data['category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
		}

		$option_filters = array();
		if($data['option_value']) {
			foreach($data['option_value'] as $option_value) {
				$option_filters[] = "option_value_id IN(" . implode(",", $option_value) . ")";
			}
		}

		if($option_filters) {
			if($this->filterpro_setting['option_mode'] == 'and') {
				foreach($option_filters as $i => $option_filter) {
					$sql .= " AND EXISTS (select 1 FROM " . DB_PREFIX . "product_option_value pov" . $i . " WHERE pov" . $i . ".product_id=pov.product_id AND pov" . $i . "." . $option_filter . ") ";
				}
			} else {
				$sql .= " AND (" . implode(" OR ", $option_filters) . ")";
			}
		}


		if($data['attribute_value']) {
			if($this->filterpro_setting['attribute_mode'] == 'and') {
				$i = 0;
				foreach($data['attribute_value'] as $attribute_id => $values) {
					$sql .= " AND EXISTS
								(select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=p2a.product_id AND p2a" . $i . ".attribute_id = " . (int)$attribute_id . " ";
					if (isset($values['min']) || isset($values['max'])) {
						$sql .= " AND p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$sql .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$sql .= " AND p2a.text <= " . $values['max'];
						}
						$sql .= ")";
					} else {
						$sql .= "AND (p2a" . $i . ".text like '%" . implode("%' OR p2a" . $i . ".text like '%", array_map(array($this->db, 'escape'), $values)) . "%')) ";
					}
					$i++;
				}
			} else {
				foreach($data['attribute_value'] as $attribute_id => $values) {
					if (isset($values['min']) || isset($values['max'])) {
						$attr_filter = "p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$attr_filter .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$attr_filter .= " AND p2a.text <= " . $values['max'];
						}
						$attribute_filters[] = $attr_filter;
					} else {
						$attribute_filters[] = "p2a.attribute_id = " . (int)$attribute_id . " AND (p2a.text like '%" . implode("%' OR p2a.text like '%", array_map(array($this->db, 'escape'), $values)) . "%')";
					}
				}
				$sql .= " AND (" . implode(" OR ", $attribute_filters) . ")";
			}
		}

		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND p.price >=" . (int)$min_price;
		}

		$sql .= " AND p.status = '1' AND p.date_available <= NOW( ) AND p2s.store_id = " . (int)$this->config->get('config_store_id');
		$sql .= ") as innertable WHERE 1 ";
		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice >=" . (int)$min_price;
		}
		if($data['max_price'] > 0) {
			$max_price = $this->currency->convert($data['max_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice <=" . ($max_price);
		}

		if(!empty($data['product_id'])) {
			$sql .= " AND product_id LIKE '%" . $data['product_id'] . "%'";
		}		

		$sql .= " GROUP BY " . "manufacturer_id";

		
		$query = $this->db->query($sql);

		$result = array();
		foreach($query->rows as $row) {
			$result[] = array('id' => $row['manufacturer_id'], 't' => $row['total']);
		}
		return $result;
	}

	public function getTotalAttributes($data) {

		$customer_group_id = $this->getCustomerGroup();

		$sql = "SELECT count(*) as total, attribute_id, text  FROM " .
			   "(SELECT DISTINCT p.product_id, p2a.attribute_id, p2a.text, coalesce((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1), " .
			   "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1), " .
			   "p.price) as realprice " .
			   " FROM " . DB_PREFIX . "product p" .
			   " LEFT JOIN " . DB_PREFIX . "manufacturer m ON(m.manufacturer_id=p.manufacturer_id) " .
			   " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id=p.product_id)";
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute p2a ON (p2a.product_id=p.product_id)";
		$sql .= " WHERE 1";
		if($data['category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
		}

		if($data['manufacturer']) {
			$sql .= " AND p.manufacturer_id IN(" . implode(", ", $data['manufacturer']) . ")";
		}

		$option_filters = array();
		if($data['option_value']) {
			foreach($data['option_value'] as $option_value) {
				$option_filters[] = "option_value_id IN(" . implode(",", $option_value) . ")";
			}
		}

		if($option_filters) {
			if($this->filterpro_setting['option_mode'] == 'and') {
				foreach($option_filters as $i => $option_filter) {
					$sql .= " AND EXISTS (select 1 FROM " . DB_PREFIX . "product_option_value pov" . $i . " WHERE pov" . $i . ".product_id=pov.product_id AND pov" . $i . "." . $option_filter . ") ";
				}
			} else {
				$sql .= " AND (" . implode(" OR ", $option_filters) . ")";
			}
		}


		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND p.price >=" . (int)$min_price;
		}

		$sql .= " AND p.status = '1' AND p.date_available <= NOW( ) AND p2a.language_id='" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = " . (int)$this->config->get('config_store_id');
		$sql .= ") as innertable WHERE 1 ";
		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice >=" . (int)$min_price;
		}
		if($data['max_price'] > 0) {
			$max_price = $this->currency->convert($data['max_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice <=" . ($max_price);
		}

		if(!empty($data['product_id'])) {
			$sql .= " AND product_id LIKE '%" . $data['product_id'] . "%'";
		}		

		$sql .= " GROUP BY " . "attribute_id, text";
		$query = $this->db->query($sql);

		$result = array();
		$attributes = array();
		foreach($query->rows as $row) {
			foreach(explode($this->filterpro_setting['attr_delimeter'], $row['text']) as $text){
				if (!isset($attributes[$row['attribute_id'].".".$text])){
					$attributes[$row['attribute_id'] . "." . $text] = array('id' => $row['attribute_id'], 'text' => $text, 't' => $row['total']);
				} else {
					$attributes[$row['attribute_id'] . "." . $text]['t'] += $row['total'];
				}
			}
		}
		foreach($attributes as $attribute){
			$result[] = array('id' => $attribute['id'], 'text' => $attribute['text'], 't' => $attribute['t']);
		}
		return $result;
	}

	public function getTotalOptions($data) {

		$customer_group_id = $this->getCustomerGroup();

		$sql = "SELECT count(*) as total, option_value_id FROM " .
			   "(SELECT DISTINCT p.product_id, pov.option_value_id, coalesce((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1), " .
			   "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1), " .
			   "p.price) as realprice " .
			   " FROM " . DB_PREFIX . "product p" .
			   " LEFT JOIN " . DB_PREFIX . "manufacturer m ON(m.manufacturer_id=p.manufacturer_id) " .
			   " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id=p.product_id)" .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id=p.product_id)";
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute p2a ON (p2a.product_id=p.product_id)";
		$sql .= " WHERE 1";
		if($data['category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
		}

		if($data['manufacturer']) {
			$sql .= " AND p.manufacturer_id IN(" . implode(", ", $data['manufacturer']) . ")";
		}


		if($data['attribute_value']) {
			if($this->filterpro_setting['attribute_mode'] == 'and') {
				$i = 0;
				foreach($data['attribute_value'] as $attribute_id => $values) {
					$sql .= " AND EXISTS
								(select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=p2a.product_id AND p2a" . $i . ".attribute_id = " . (int)$attribute_id . " ";
					if (isset($values['min']) || isset($values['max'])) {
						$sql .= " AND p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$sql .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$sql .= " AND p2a.text <= " . $values['max'];
						}
						$sql .= ")";
					} else {
						$sql .= "AND (p2a" . $i . ".text like '%" . implode("%' OR p2a" . $i . ".text like '%", array_map(array($this->db, 'escape'), $values)) . "%')) ";
					}
					$i++;
				}
			} else {
				foreach($data['attribute_value'] as $attribute_id => $values) {
					if (isset($values['min']) || isset($values['max'])) {
						$attr_filter = "p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$attr_filter .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$attr_filter .= " AND p2a.text <= " . $values['max'];
						}
						$attribute_filters[] = $attr_filter;
					} else {
						$attribute_filters[] = "p2a.attribute_id = " . (int)$attribute_id . " AND (p2a.text like '%" . implode("%' OR p2a.text like '%", array_map(array($this->db, 'escape'), $values)) . "%')";
					}
				}
				$sql .= " AND (" . implode(" OR ", $attribute_filters) . ")";
			}
		}

		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND p.price >=" . (int)$min_price;
		}

		$sql .= " AND p.status = '1' AND p.date_available <= NOW( ) AND p2s.store_id = " . (int)$this->config->get('config_store_id');
		$sql .= ") as innertable WHERE 1 ";
		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice >=" . (int)$min_price;
		}
		if($data['max_price'] > 0) {
			$max_price = $this->currency->convert($data['max_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice <=" . ($max_price);
		}

		if(!empty($data['product_id'])) {
			$sql .= " AND product_id LIKE '%" . $data['product_id'] . "%'";
		}
		
		$sql .= " GROUP BY " . "option_value_id";
		$query = $this->db->query($sql);

		$result = array();
		foreach($query->rows as $row) {
			$result[] = array('id' => $row['option_value_id'], 't' => $row['total']);
		}

		return $result;
	}

	public function getProducts($data) {
		$customer_group_id = $this->getCustomerGroup();

		$sql = "SELECT product_id FROM(";
		$sql .= "SELECT DISTINCT p.product_id, pd.name, p.model, p.quantity, p.price, p.sort_order, p.date_added ";
		$sql .= ", coalesce((SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1), " .
				"(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1), " .
				"p.price) as realprice ";
		$sql .= "FROM " . DB_PREFIX . "product p" .
				" LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_id=p.product_id)" .
				" LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id=p.product_id)" .
				" LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id=p.product_id)" .
				" LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id=p.product_id)";
		if($data['attribute_value']) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute p2a ON (p2a.product_id=p.product_id)";
		}

		$sql .= " WHERE 1";
		if($data['category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
		}

		$option_filters = array();
		if($data['option_value']) {
			foreach($data['option_value'] as $option_value) {
				$option_filters[] = "option_value_id IN(" . implode(",", $option_value) . ")";
			}
		}

		if($option_filters) {
			if($this->filterpro_setting['option_mode'] == 'and') {
				foreach($option_filters as $i => $option_filter) {
					$sql .= " AND EXISTS (select 1 FROM " . DB_PREFIX . "product_option_value pov" . $i . " WHERE pov" . $i . ".product_id=pov.product_id AND pov" . $i . "." . $option_filter . ") ";
				}
			} else {
				$sql .= " AND (" . implode(" OR ", $option_filters) . ")";
			}
		}

		if($data['manufacturer']) {
			$sql .= " AND p.manufacturer_id IN(" . implode(", ", $data['manufacturer']) . ")";
		}

		if($data['attribute_value']) {
			if($this->filterpro_setting['attribute_mode'] == 'and') {
				$i = 0;
				foreach($data['attribute_value'] as $attribute_id => $values) {
					$sql .= " AND EXISTS
								(select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=p2a.product_id AND p2a" . $i . ".attribute_id = " . (int)$attribute_id . " ";
					if (isset($values['min']) || isset($values['max'])) {
						$sql .= " AND p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$sql .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$sql .= " AND p2a.text <= " . $values['max'];
						}
						$sql .= ")";
					} else {
						$sql .= "AND (p2a" . $i . ".text like '%" . implode("%' OR p2a" . $i . ".text like '%", array_map(array($this->db, 'escape'), $values)) . "%')) ";
					}
					$i++;
				}
			} else {
				foreach($data['attribute_value'] as $attribute_id => $values) {
					if (isset($values['min']) || isset($values['max'])) {
						$attr_filter = "p2a.attribute_id = " . (int)$attribute_id;
						if (isset($values['min']) && $values['min'] >= 0) {
							$attr_filter .= " AND p2a.text >= " . $values['min'];
						}
						if (isset($values['max']) && $values['max'] > 0) {
							$attr_filter .= " AND p2a.text <= " . $values['max'];
						}
						$attribute_filters[] = $attr_filter;
					} else {
						$attribute_filters[] = "p2a.attribute_id = " . (int)$attribute_id . " AND (p2a.text like '%" . implode("%' OR p2a.text like '%", array_map(array($this->db, 'escape'), $values)) . "%')";
					}
				}
				$sql .= " AND (" . implode(" OR ", $attribute_filters) . ")";
			}
		}

		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND p.price >=" . (int)($min_price);
		}

		if(!empty($data['product_id'])) {
			$sql .= " AND p.product_id LIKE '%" . $data['product_id'] . "%'";
		}		

		$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW( ) AND p2s.store_id = " . (int)$this->config->get('config_store_id');

		$sort_data = array(
			'pd.name' => 'name',
			'p.model' => 'model',
			'p.quantity' => 'quantity',
			'p.price' => 'realprice',
			'p.sort_order' => 'sort_order',
			'p.date_added' => 'date_added'
		);

		$sql .= ") as innertable WHERE 1 ";
		if($data['min_price'] >= 0) {
			$min_price = $this->currency->convert((int)$data['min_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= "AND realprice >=" . (int)($min_price);
		}
		if($data['max_price'] > 0) {
			$max_price = $this->currency->convert($data['max_price'], $this->currency->getCode(), $this->config->get('config_currency'));
			$sql .= " AND realprice <=" . ($max_price);
		}

		if(isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$data['sort'] = $sort_data[$data['sort']];
			if($data['sort'] == 'name' || $data['sort'] == 'model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY sort_order";
		}

		if(isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if(isset($data['start']) || isset($data['limit'])) {
			if($data['start'] < 0) {
				$data['start'] = 0;
			}

			if($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		$product_data = array();
		if($query->rows) {
			$this->load->model('catalog/product');
			foreach($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}
		}
		return $product_data;
	}

	public function getTaxRates($tax_class_id) {
		$query = $this->db->query("SELECT tr1.rate, tr1.type FROM " . DB_PREFIX . "tax_rate tr1 LEFT JOIN " . DB_PREFIX . "tax_rule tr2 ON (tr1.tax_rate_id=tr2.tax_rate_id) " .
								  "WHERE tr2.tax_class_id=" . (int)$tax_class_id . " ORDER BY tr2.priority");
		return $query->rows;
	}

}
