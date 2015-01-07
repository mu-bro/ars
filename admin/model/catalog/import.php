<?php
class ModelCatalogImport extends Model {
	public function addImport($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "import SET sort_order = '" . (int)$this->request->post['sort_order'] . "', settings = '" . serialize( $data["setting"] ) . "'");

		$import_id = $this->db->getLastId(); 
			
		foreach ($data['import_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "import_description SET import_id = '" . (int)$import_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}

	public function editImport($import_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "import SET sort_order = '" . (int)$data['sort_order'] . "', settings = '" . serialize(  $data["setting"] ) . "' WHERE import_id = '" . (int)$import_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "import_description WHERE import_id = '" . (int)$import_id . "'");

		foreach ($data['import_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "import_description SET import_id = '" . (int)$import_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}

	public function getTotalImports() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "import");
		return $query->row['total'];
	}

	public function deleteImport($import_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "import WHERE import_id = '" . (int)$import_id . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "import_description WHERE import_id = '" . (int)$import_id . "'");
	}
	
	public function getImports($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "import i LEFT JOIN " . DB_PREFIX . "import_description id ON (i.import_id = id.import_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		$sort_data = array(
			'id.title',
			'i.sort_order'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY id.sort_order";
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
	
	public function getImport($import_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "import WHERE import_id = '" . (int)$import_id . "'");
		return $query->row;
	}
	
	public function getImportDescriptions($import_id) {
		$import_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "import_description WHERE import_id = '" . (int)$import_id . "'");

		foreach ($query->rows as $result) {
			$import_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $import_description_data;
	}
	
	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}
	
	public function getCategories($parent_id = 0) {
		$category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		foreach ($query->rows as $result) {
			$category_data[] = array(
				'category_id' => $result['category_id'],
				'name'        => $this->getPath($result['category_id'], $this->config->get('config_language_id')),
				'status'  	  => $result['status'],
				'sort_order'  => $result['sort_order']
			);
		
			$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
		}
		
		return $category_data;
	}
	public function addCategory($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");
	
		$category_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		return $category_id;
	}
	public function getManufacturers() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name");
		return $query->rows;
	}
	public function getManufacturersSerias($manufaturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_series WHERE manufacturer_id = '$manufaturer_id' ORDER BY name");
		return $query->rows;
	}	
	public function addManufacturer($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}
		
		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		return $manufacturer_id;
	}
	public function addSeria($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_series SET 
			manufacturer_id = '" . (int) $data['manufacturer_id'] . "', 
			seria_id = '" . (int)$data['seria_id'] . "' ,
			name = '" . $this->db->escape( $data['name'] ) . "'");
		
		$seria_id = $this->db->getLastId();


		return $seria_id;
	}	
	public function getProductByName( $name ){
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_description WHERE LOWER(name) = '" . $this->db->escape( $name ) . "' LIMIT 1");
		
		if( !empty( $query->row ) ) {
			return $query->row["product_id"];
		}
		
		return false;
	}
	public function getProductBySku( $sku ){
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE LOWER(sku) = '" . $this->db->escape( $sku ) . "' LIMIT 1");
		
		if( !empty( $query->row ) ) {
			return $query->row["product_id"];
		}
		
		return false;
	}
	
	public function addProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', 
		length_class_id = '" . (int)$data['length_class_id'] . "', 
		status = '" . (int)$data['status'] . "',
		tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', 
		sort_order = '" . (int)$data['sort_order'] . "', 
		
		date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		foreach ($data['product_description'] as $language_id => $value) {
			$sql_add = '';
			if (isset($value['description']))
				$sql_add = " , description = '" . $this->db->escape($value['description']) . "'";
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
			product_id = '" . (int)$product_id . "', 
			language_id = '" . (int)$language_id . "', 
			name = '" . $this->db->escape($value['name']) . "', 
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', 
			meta_description = '" . $this->db->escape($value['meta_description']) . "'
			" . $sql_add);
		}
		
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
				
					$product_option_id = $this->db->getLastId();
				
					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						} 
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}
		
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		return $product_id;

	}
	
	public function editProduct($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET 
				model = '" . $this->db->escape($data['model']) . "',
				sku = '" . $this->db->escape($data['sku']) . "',
				upc = '" . $this->db->escape($data['upc']) . "',
				location = '" . $this->db->escape($data['location']) . "',
				quantity = '" . (int)$data['quantity'] . "',
				manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
				price = '" . (float)$data['price'] . "',
				image = '" . $this->db->escape($data["image"]) . "',
				stock_status_id = '" . $data["stock_status_id"] . "',
				date_modified = NOW()
			WHERE product_id = '" . (int)$product_id . "'");
		

		foreach ($data['product_description'] as $language_id => $value) {
			
			if (!isset($value['description'])) {
				$description = $this->db->query("SELECT description,language_id FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$language_id . "' AND product_id = '" . (int)$product_id . "'")->row["description"];
				$value['description'] = $description;
			}
					
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$language_id . "' AND product_id = '" . (int)$product_id . "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
			product_id = '" . (int)$product_id . "', 
			language_id = '" . (int)$language_id . "', 
			name = '" . $this->db->escape($value['name']) . "', 
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', 
			meta_description = '" . $this->db->escape($value['meta_description']) . "', 
			description = '" . $this->db->escape($value['description']) . "'"
			);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
	}
	public function getStockStatuses() {
			$stock_status_data = $this->cache->get('stock_status.' . (int)$this->config->get('config_language_id'));
			if (!$stock_status_data) {
				$query = $this->db->query("SELECT stock_status_id, name FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
	
				$stock_status_data = $query->rows;
			
				$this->cache->set('stock_status.' . (int)$this->config->get('config_language_id'), $stock_status_data);
			}
			return $stock_status_data;
	}
	
	public function getAttributeGroups() {
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute_group ag LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$query = $this->db->query($sql);
		$attribute_groups = array();
		foreach( $query->rows as $attr_group_values ){
			
			$attribute_groups[ $attr_group_values["name"] ] = array(
				"attribute_group_id" => $attr_group_values["attribute_group_id"],
				"attributes" => array()
				
			);
			
			$filter = array(
				"filter_attribute_group_id" => $attr_group_values["attribute_group_id"]
			);
			$attributes = $this->getAttributes($filter);
			foreach( $attributes as $attr_value ) {
				$attribute_groups[ $attr_group_values["name"] ]["attributes"][$attr_value["name"]] = $attr_value["attribute_id"];
			}
		}
		
		return $attribute_groups;
	}
	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function addAttributeGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET sort_order = '" . (int)$data['sort_order'] . "'");
		
		$attribute_group_id = $this->db->getLastId();
		
		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		return $attribute_group_id;
	}
	
	public function addAttribute($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$attribute_id = $this->db->getLastId();
		
		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		return $attribute_id;
	}
	
	public function clearProductAttributes($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
	}
	
	public function addProductAttributes( $product_id, $data ){
		if (isset( $data )) {
			foreach ($data as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
	}
	public function getProductForExport( $start, $limit = 1000 ){
		$query = $this->db->query("
			SELECT
				p.product_id,
				pd.name product_name,
				pd.meta_description product_meta_description,
				pd.meta_keyword product_meta_keyword,
				pd.description product_description,
				p.model product_model,
				p.sku product_sku,
				p.image product_image,
				p.quantity product_quantity,
				p.price product_price,
				m.name manufacturer_name
			FROM
				" . DB_PREFIX . "product p LEFT JOIN
				" . DB_PREFIX . "product_description pd ON pd.product_id = p.product_id LEFT JOIN
				" . DB_PREFIX . "manufacturer m ON m.manufacturer_id = p.manufacturer_id
			WHERE
				pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			LIMIT " . $start . ", " . $limit . ";");
		if( empty( $query->rows ) ) return false;
		
		$products = array();
		foreach( $query->rows as $key => $product_info ){
			$product_id = $product_info["product_id"];
			
			$product_tags = array();
			foreach( $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id . "'")->rows as $tag ){
				$product_tags[] = $tag["tag"];
			}
			
			$product_info["product_tag"] = implode( ", ", $product_tags );
			
			$product_images = array();
			foreach( $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'")->rows as $image ){
				$product_images[] = $image["image"];
			}
			
			$product_info["product_additional_images"] = implode( ", ", $product_images );
			
			$category_info = array();
			$parent_on = ' c.category_id = ptc.category_id ';
			$category_id = ' ptc.category_id ';
			while( $category = $this->db->query("
				SELECT
					cd.name category_name,
					cd.meta_description category_meta_description,
					cd.meta_keyword category_meta_keyword,
					cd.description category_description,
					c.parent_id
				FROM
					" . DB_PREFIX . "product_to_category ptc LEFT JOIN
					" . DB_PREFIX . "category_description cd ON cd.category_id = " . $category_id . " LEFT JOIN
					" . DB_PREFIX . "category c ON " . $parent_on . "
				WHERE
					ptc.product_id = '" . (int)$product_id . "' AND
					cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
				GROUP BY
					ptc.product_id
				")->row ){
				
				if( empty( $category_info ) ) {
					$category_info = $category;
				} else {
					$category_info["category_name"] = $category["category_name"] . ">" . $category_info["category_name"];
				}
				
				if( empty( $category["parent_id"] ) ) {
					unset( $category_info["parent_id"] );
					break;
				}
				$category_id = (int) $category["parent_id"];
				$parent_on = " c.category_id = '" . (int) $category["parent_id"] . "'";
			}

			$product_info = array_merge($product_info, $category_info);

			$product_attributes = array();
			foreach( $this->db->query("
				SELECT
					*
				FROM
					" . DB_PREFIX . "product_attribute pa LEFT JOIN
					" . DB_PREFIX . "attribute_description ad ON ad.attribute_id = pa.attribute_id
				WHERE
					pa.product_id = '" . (int)$product_id . "' AND
					pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
					ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
				GROUP BY
					pa.attribute_id
				")->rows as $attribute ){
				$product_attributes[ $attribute["name"] ] = $attribute["text"];
			}
			
			$product_info = array_merge($product_info, $product_attributes);
			
			$products[] = $product_info;
		}
		
		return $products;
	}
}
?>