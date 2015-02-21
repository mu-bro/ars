<?php

static $config = NULL;
static $log = NULL;

class ControllerCatalogImport extends Controller { 
	private $error = array();
	private $currentLineNumber = 0;
	private $beginning = 0;
	private $attributes_all = array();

	public function index() {
		$this->load->language('catalog/import');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('catalog/import');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/import');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/import');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_import->addImport($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/import');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/import');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_import->editImport($this->request->get['import_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('catalog/import');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/import');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $import_id) {
				$this->model_catalog_import->deleteImport($import_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'i.sort_order';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('catalog/import/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/import/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['export'] = $this->url->link('catalog/import/export', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['imports'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$import_total = $this->model_catalog_import->getTotalImports();
	
		$results = $this->model_catalog_import->getImports($data);
  
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/import/update', 'token=' . $this->session->data['token'] . '&import_id=' . $result['import_id'] . $url, 'SSL')
			);
			
			$action[] = array(
				'text' => $this->language->get('text_import'),
				'href' => $this->url->link('catalog/import/importing', 'token=' . $this->session->data['token'] . '&import_id=' . $result['import_id'] . $url, 'SSL')
			);

			$this->data['imports'][] = array(
				'import_id' => $result['import_id'],
				'title'          => $result['title'],
				'sort_order'     => $result['sort_order'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['import_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_title'] = $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $import_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/import_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['import_version'] = $this->language->get('import_version');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = array();
		}
		
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model("catalog/category");
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		$this->load->model('catalog/manufacturer');
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		$this->load->model('catalog/attribute');
		$attributs = $this->model_catalog_attribute->getAttributes();
		
		$this->data["statuses"] = $this->model_catalog_import->getStockStatuses();
		
		$attr_data = array();
		
		foreach( $attributs as $attribut ){
			$attr_data[ $attribut["attribute_group"] ][] = $attribut;
		}

		asort( $attr_data );
		
		$this->data["attributs"] = $attr_data;

		$this->data['options'] = $this->model_catalog_import->getOptions();

		if (!isset($this->request->get['import_id'])) {
			$this->data['action'] = $this->url->link('catalog/import/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/import/update', 'token=' . $this->session->data['token'] . '&import_id=' . $this->request->get['import_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['import_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$import_info = $this->model_catalog_import->getImport($this->request->get['import_id']);
			
			if( !empty( $import_info["settings"] ) ){
				$import_info["setting"] = unserialize( $import_info["settings"] );
			} else {
				$import_info["setting"] = array();
			}
		}

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['import_description'])) {
			$this->data['import_description'] = $this->request->post['import_description'];
		} elseif (isset($this->request->get['import_id'])) {
			$this->data['import_description'] = $this->model_catalog_import->getImportDescriptions($this->request->get['import_id']);
		} else {
			$this->data['import_description'] = array();
		}
		
		if (isset($this->request->post['setting'])) {
			$this->data['setting'] = $this->request->post['setting'];
		} elseif (!empty($import_info['setting'])) {
			$this->data['setting'] = $import_info['setting'];
		} else {
			$this->data['setting'] = array();
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($import_info)) {
			$this->data['sort_order'] = $import_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

		$this->template = 'catalog/import_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getFormFile() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->link('catalog/import/importing', 'token=' . $this->session->data['token'] . '&import_id=' . $this->request->get['import_id'] . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'catalog/import_form_file.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function export(){
		$time_start = time();
		
		$filename = "export-catalog-" . date("Y-m-d H-i") . ".csv";
	
		ini_set("memory_limit", '32M');
	
		header("Content-Type: text/plain;charset=windows-1251");
		header("Content-Disposition: attachment;filename=" . $filename);
// 		set_error_handler('error_handler_for_export',E_ALL);
// 		require_once "pear/Spreadsheet/Excel/Writer.php";
// 		$workbook = new Spreadsheet_Excel_Writer();
// 		$workbook->setTempDir(DIR_CACHE);
// 		$workbook->setVersion(8);
// 		$priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
// 		$boxFormat =& $workbook->addFormat(array('Size' => 10,'vAlign' => 'vequal_space' ));
// 		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
// 		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@", 'Align' => "left" ));
	
// 		$workbook->send( $filename );
		
// 		$worksheet =& $workbook->addWorksheet( "products" );
// 		$worksheet->setInputEncoding('UTF-8');
		
// 		$this->workbook = $workbook;
// 		$this->worksheet = $worksheet;
		
		$this->delimiter_field = ';';
		$this->delimiter_text = '"';
		
// 		$this->export_line_id = 0;
// 		$this->export_field_id = 0;
		
		$header_info = array(
			"product_name",
			"product_meta_description",
			"product_meta_keyword",
			"product_description",
			"product_tag",
			"product_image",
			"product_additional_images",
			"product_model",
			"product_sku",
			"product_quantity",
			"product_price",
			"category_name",
			"category_meta_description",
			"category_meta_keyword",
			"category_description",
			"manufacturer_name"
		);
		
		$this->load->model("catalog/import");
		$attributes_all = $this->model_catalog_import->getAttributeGroups();
		
		foreach( $attributes_all as $attr_group ){
			foreach( $attr_group["attributes"] as $attr_name => $attr_value ){
				$header_info[] = $attr_name;
			}
		}

		echo iconv( "UTF-8", "cp1251", $this->setCsvLine( $header_info ) );
		
		$start = 0;
		$limit = 2000;
		
		while( $products_info = $this->model_catalog_import->getProductForExport( $start, $limit ) ){
		
			foreach( $products_info as $product_info ) {
				$product_export_line = array();
				foreach( $header_info as $field ){
					if( !empty( $product_info[ $field ] ) ) {
						$product_export_line[ $field ] = $product_info[ $field ];
					} else {
						$product_export_line[ $field ] = '';
					}
				}
			
				echo iconv( "UTF-8", "cp1251", $this->setCsvLine( $product_export_line ) );
			}
			
			$start += $limit;
		}

// 		$workbook->close();
		exit();
	}
	
	public function setXlsLine( $header_info ){
		$j = $this->export_field_id;
		$i = $this->export_line_id;
		foreach( $header_info as $column ){
			$this->worksheet->setColumn( $i ,$j,strlen( $column )+2);
			$this->worksheet->write( $i, $j++, $column );
		}
		$this->export_field_id = 0;
		$this->export_line_id ++;
	}
	
	public function setCsvLine( $line ){
		$string = "";
		// for each array element, which represents a line in the csv file...
		$writeDelimiter = FALSE;
		foreach($line as $dataElement){
			// Replaces a double quote with two double quotes
			$dataElement=str_replace("\"", "\"\"", $dataElement);
			// Adds a delimiter before each field (except the first)
			if($writeDelimiter) $string .= $this->delimiter_field;
			// Encloses each field with $enclosure and adds it to the string 
			$string .= $this->delimiter_text . $dataElement . $this->delimiter_text;
			// Delimiters are used every time except the first.
			$writeDelimiter = TRUE;
		}
		// Append new line
		$string .= "\n";
		return $string;
	}
	
	public function importing(){
		$this->load->language('catalog/import');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/import');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
		
			if( !empty( $this->request->files["import_file"] ) && !empty( $this->request->get["import_id"] ) ){

				$import_settings = $this->model_catalog_import->getImport( (int) $this->request->get["import_id"] );
			
				if( !empty( $import_settings["settings"] ) ){
					$import_settings["setting"] = unserialize( $import_settings["settings"] );
				} else {
					$import_settings["setting"] = array();
				}

				$time_start = time();

				$filename = $this->request->files["import_file"]["tmp_name"];
				if( is_array( $import_settings["setting"] ) )
					$this->importFile( $filename, $import_settings["setting"] );
				
				$time_stop = time();
				
				$this->session->data['success'] = "Импорт закончен. Добавлено продуктов: " . $this->import_product_added . ", обновлено: " . $this->import_product_updated . ", пропущено: " . $this->import_product_error . " строк(и). Время выполнения: " . ( $time_stop - $time_start ) . "с.";
				
				$this->redirect($this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			
			}
			
			$this->redirect($this->url->link('catalog/import', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			
		}

		$this->getFormFile();
	}

	public function deleteProducts() {

		$this->db->query("UPDATE " . DB_PREFIX . "product SET status = 0");


//		$this->db->query("DELETE FROM " . DB_PREFIX . "product");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store");
//		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_profile`");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "review");
//
//		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'product_id=%'");

		$this->cache->delete('product');
	}
	
	private function importFile( $filename, $import_settings ){
		
		header('Content-Type: text/html; charset=UTF8;');
		
		$formats = array(
			1 => "csv",
			2 => "xls"
		);
		$charsets = array(
			1 => "UTF-8",
			2 => "Windows-1251"
		);
		$delimiter_fields = array(
			1 => ",",
			2 => ";",
			3 => ":",
			4 => "\t",
			5 => " ",
			6 => !empty( $import_settings[1]["delimiter_field_other"] ) ? $import_settings[1]["delimiter_field_other"] : '',
		);
		$delimiter_texts = array(
			1 => '"',
			2 => "'"
		);
		$update_products = array(
			1 => 'name',
			2 => 'sku'
		);

		$this->load->model("catalog/product");
		$this->load->model("localisation/language");
		
		$this->format = !empty( $import_settings[1]["format"] ) && in_array( $import_settings[1]["format"], array_keys($formats) ) ? $formats[ $import_settings[1]["format"] ] : exit("Ошибка формата файла!");
		$this->charset = !empty( $import_settings[1]["charset"] ) && in_array( $import_settings[1]["charset"], array_keys($charsets) ) ? $charsets[ $import_settings[1]["charset"] ] : exit("Ошибка кодировки файла!");
		$this->delimiter_field = !empty( $import_settings[1]["delimiter_field"] ) && in_array( $import_settings[1]["delimiter_field"], array_keys($delimiter_fields) ) ? $delimiter_fields[ $import_settings[1]["delimiter_field"] ] : exit("Ошибка разделителя полей!");
		$this->delimiter_text = !empty( $import_settings[1]["delimiter_text"] ) && in_array( $import_settings[1]["delimiter_text"], array_keys($delimiter_texts) ) ? $delimiter_texts[ $import_settings[1]["delimiter_text"] ] : exit("Ошибка разделителя текста!");
		$this->header_line = !empty( $import_settings[1]["header_line"] ) ? ( (int) $import_settings[1]["header_line"] ): "0";
		$this->start_line = !empty( $import_settings[1]["start_line"] ) ? ( (int) $import_settings[1]["start_line"] ): "1";
		$this->stop_line = !empty( $import_settings[1]["stop_line"] ) ? ( (int) $import_settings[1]["stop_line"] ): "~";

		$this->images_dir = DIR_IMAGE . "data/" . $import_settings[1]["images_dir"];

		$this->category_delimiter = !empty( $import_settings[3]["category_delimiter"] ) ? htmlspecialchars_decode( $import_settings[3]["category_delimiter"] ) : ">";

		$this->product_update = !empty( $import_settings[1]["update_product"] ) && in_array( $import_settings[1]["update_product"], array_keys($update_products) ) ? $update_products[ $import_settings[1]["update_product"] ] : exit('Ошибка.');

		// Функция для подготовки читалки файла
		$this->{"preAction" . $this->format . "File"}( $filename );

		if( !$header_info = $this->getHeaderline( $filename ) ) exit("Строка с номером " . $this->header_line . " не обнаружена!");

		if( count( $header_info ) <= 1 ) exit("Количество полей должно быть больше одного. Проверьте файл и настройки импорта");

		$ids = array();

		set_time_limit(0);

		// Get Languages
		$this->languages = $this->model_localisation_language->getLanguages();
		
		// Get Categorys
		$category_ids = array();
		foreach( $this->model_catalog_import->getCategories(0) as $category ){
			$category_ids[ $category["name"] ] = $category["category_id"];
		}
		$this->category_ids = $category_ids;
		
		// Get Manufacturers
		$manufacturer_ids = array();
		foreach( $this->model_catalog_import->getManufacturers() as $manufacturers ){
			$manufacturer_ids[ $manufacturers["name"] ] = $manufacturers["manufacturer_id"];
		}
		$this->manufacturer_ids = $manufacturer_ids;

		$this->import_product_added = 0;
		$this->import_product_updated = 0;
		$this->import_product_error = 0;
		
		$category_array = array();
		$last_type_operation = '';

		$this->attributes_all = $this->model_catalog_import->getAttributeGroups();
		
		$attribute_create = false;
		$attribute_fields = array();
		
		if( !empty( $import_settings[5] ) ) {
			$attribute_create = true;
			if( !empty( $import_settings[5]["start_find"] ) ){
				$start = false;
				foreach( $header_info as $field_one ){
					if( "{" . $field_one . "}" == $import_settings[5]["start_find"] ) {
						$start = true;
					}
					if( $start ) {
						$attribute_fields[ "{" . $field_one . "}" ] = $field_one;
					}
					if( !empty( $import_settings[5]["stop_find"] ) && "{" . $field_one . "}" == $import_settings[5]["stop_find"] ) {
						$start = false;
					}
				}
			}
			
			$attributes = array();
			if( !empty( $import_settings[5]["attributes"] ) ){
				foreach( $import_settings[5]["attributes"] as $attr_value ){
					$attributes[ $attr_value["field"] ] = $attr_value;
					$attribute_fields[ $attr_value["field"] ] = $attr_value["name"];
				}
				$import_settings[5]["attributes"] = $attributes;
			}
			
			if( !empty( $attribute_fields ) ) $attribute_create = true;
		} else {
			$import_settings[5] = array();
		}

		$this->deleteProducts();

		while( ( $row_array = $this->getLine() ) !== FALSE ){
		
			if( count( $row_array ) == count( $header_info ) ) {
				$data = array();
				foreach( $row_array as $key=>$value ){
					$data[ "{" . $header_info[ $key ] . "}" ] = $value;
				}

				// Расположение категорий в файле
				if( !empty( $import_settings[3]["category_column"] ) ) {
					// Категории располагаются на отдельных строках. Продукты находятся под строкой категории.
					// Проверка на строку с названием категории.
					if( !empty( $data[ $import_settings[3]["category_column"] ] ) ) {
						$is_category = true;
						foreach( $data as $key => $value ) {
							if( !empty( $value )  && $key != $import_settings[3]["category_column"] ) $is_category = false;
						}
						if( $is_category ) {
							// Это категория, обрабатываем её
							
							if( empty( $category_array ) || $import_settings[3]["category_nesting"] == 1) {
								// Это первое нахождение категории, или требуется всего одна категория.
								$category_array = array( 
									0 => $data[ $import_settings[3]["category_column"] ]
								);
							} elseif( $last_type_operation == 'category_added' ) {
								// Последнее действие было добавление категории в список.
								$category_array[] = $data[ $import_settings[3]["category_column"] ];
								if( count ( $category_array ) > $import_settings[3]["category_nesting"] ) {
									unset( $category_array[0] );
									$category_array = array_values( $category_array );
								}
							} elseif( $last_type_operation == 'product_import' ) {
								$category_array[1] = $data[ $import_settings[3]["category_column"] ];
								
							}
							
							$last_type_operation = 'category_added';

							// переход на следущую строку.
							continue;
						}
					}
					
					$data["{generated_category}"] = implode( $this->category_delimiter , $category_array );
					
				}

				// Get/Create category from product
				$category_id = $this->import_category( $data, $import_settings[3] );
				
				// Get/Create manufacturer form product
				$manufacturer_id = $this->import_manufacturer( $data, $import_settings[4] );

				if(isset($import_settings[6])) {
					$product_option = $this->collectProductOption($data, $import_settings[6]);
				} else {
					$product_option = array();
				}

				// Update/Create product & attribut
				$product_id = $this->import_product( $data, $import_settings[2], $product_option, $manufacturer_id, $category_id , $import_settings);

				$this->model_catalog_import->clearProductAttributes( $product_id );
				if( $attribute_create ) $this->import_attributes( $product_id, $data, $import_settings[5], $attribute_fields );

				$last_type_operation = 'product_import';

			} else {
				// Error line
				$this->import_product_error ++;
			}
			
		}
		
		$this->cache->delete('category');
		$this->cache->delete('product');
		$this->cache->delete('manufacturer');
	}

	private function preActionCsvFile( $filename ){
		require_once 'pear/Spreadsheet/Csv/Csv_reader.php';
		
		$reader = new Csv_reader( $filename, $this->delimiter_field, $this->delimiter_text, $this->charset );

		$this->reader = $reader;
		
		// BEGIN KOST`IL
		// Считываем первую строчку
		$this->reader->nextRow(0);
		// Сбрасываем на начало :)
		$this->reader->RewindRow(0);
		// END KOST`IL
		
		$this->filePagesCount = count( $this->reader->sheets );
		
		$this->header_line --;
		$this->start_line --;
		$this->stop_line = is_numeric( $this->stop_line ) ? $this->stop_line - 1 : $this->stop_line;
		
		return true;
	}
	
	private function preActionXlsFile( $filename ){
		
		// Указываем обработчик другой. Функция эта находится внизу этого файла ... :)
		set_error_handler('error_handler_for_export',E_ALL);
		
		require_once 'pear/Spreadsheet/Excel/Reader.php';
		
		$reader=new Spreadsheet_Excel_Reader();
		$reader->setUTFEncoder('iconv');
		$reader->setOutputEncoding('UTF-8');
		$reader->setReadLineType(true);
		$reader->read($filename);
		$this->reader = $reader;
		
		// BEGIN KOST`IL
		// Считываем первую строчку
		$this->reader->nextRow(0);
		// Сбрасываем на начало :)
		$this->reader->RewindRow(0);
		// END KOST`IL
		$this->filePagesCount = count( $this->reader->sheets );

		$this->beginning = 1;

	}
	
	private function getLine(){
		// Указатель на следущую строчку
		$this->currentLineNumber ++;
		
		// Перепрыгиваем на строчку начала, если мы выше чем надо
		$this->currentLineNumber = ( $this->currentLineNumber < $this->start_line ) ? $this->start_line : $this->currentLineNumber;
		
		$current_row = true;
		while( $current_row ) {
			$rowData = $this->reader->nextRow(0);
			if( empty( $rowData ) && !empty( $this->reader->sheets[0]["cells"] ) ) {
				$current_row = false;
			} elseif( empty( $rowData ) && empty( $this->reader->sheets[0]["cells"] ) ) {
				return false;
			}
			if( key( $rowData ) >= $this->currentLineNumber ) {
				$current_row = false;
			}
		}
		
		// Последняя строка
		if( $this->reader->sheets[0]["numRows"] < $this->currentLineNumber ) return false;
		
		// Конечная строка
		if( is_numeric( $this->stop_line ) && $this->stop_line < $this->currentLineNumber ) return false;
		
		$data = array();
		
		for( $i = $this->beginning; $i < $this->reader->sheets[0]["numCols"] + $this->beginning; $i ++ ) {
			$data[$i - $this->beginning] = !empty( $this->reader->sheets[0]["cells"][ $this->currentLineNumber ][$i] ) ? $this->reader->sheets[0]["cells"][ $this->currentLineNumber ][$i] : NULL;
		}
		
		return $data;
	}
	
	private function getHeaderLine(){
		$this->currentLineNumber = $this->header_line;
		
		// Генерируем и возвращаем заголовок, если пользователь указал нулевую строчку.
		if( $this->currentLineNumber == ( $this->beginning - 1 ) ) {
			$header_info = array();
			for( $i = 0; $i < $this->reader->sheets[0]["numCols"]; $i ++ ){
				$header_info[ $i ] = "row_" . ($i + 1);
			}
			return $header_info;
		}

		$current_row = true;
		while( $current_row ) {
			$rowData = $this->reader->nextRow(0);
			if( empty( $rowData ) ) return false;
			if( key( $rowData ) >= $this->currentLineNumber ) {
				$current_row = false;
			}
		}
		
		// Считываем строку с Заголовком
		if( !empty( $this->reader->sheets[0]["cells"][ $this->currentLineNumber ] ) ) {
			$header_info = array();
			for( $i = $this->beginning; $i < $this->reader->sheets[0]["numCols"] + $this->beginning; $i ++ ) {
				$header_info[$i - $this->beginning] = !empty( $this->reader->sheets[0]["cells"][ $this->currentLineNumber ][$i] ) ? $this->reader->sheets[0]["cells"][ $this->currentLineNumber ][$i] : "row_" . ( $i + 1 - $this->beginning ) ;
			}
			return $header_info;
		} else return false;
	}
	
	function import_group_attributes( $group_name, $rowData ){
	
		if( empty( $this->attributes_all[ $group_name ] ) ) {
			$group_info = array(
				"attribute_group_description" => array(),
				"sort_order" => 0
			);
			foreach( $this->languages as $language ){
				$group_info["attribute_group_description"][ $language["language_id"] ] = array(
					"name" => $group_name,
				);
			}
			$attribute_group_id = $this->model_catalog_import->addAttributeGroup( $group_info );
			$this->attributes_all[ $group_name ] = array(
				"attribute_group_id" => $attribute_group_id,
				"attributes" => array()
			);
		} else {
			$attribute_group_id = $this->attributes_all[ $group_name ]["attribute_group_id"];
		}
		
		return $attribute_group_id;
	}
	
	function import_attributes( $product_id, $rowData, $attribut_settings, $attribute_fields ) {
		
		$product_attributes = array();
		
		if( !empty( $attribut_settings["group"] ) ) {
			$attribut_settings["group"] = str_replace( array_keys($rowData), $rowData, $attribut_settings["group"] );
			$attribute_group_id = $this->import_group_attributes($attribut_settings["group"], $rowData );
		} else {
			return false;
		}

		foreach( $attribute_fields as $field_key => $field_value ) {
		
			$field_text = str_replace( array_keys($rowData), $rowData, $field_key );

			if( empty( $field_text ) || !in_array( $field_key, array_keys ( $rowData ) ) ) continue;
			
			if( !empty( $attribut_settings["attributes"][ $field_key ] ) ) {
				$this_attribute_group_name = $attribut_settings["attributes"][ $field_key ]["group"];
				$this_attribute_group_id = $this->import_group_attributes( $this_attribute_group_name, $rowData );
			} else {
				$this_attribute_group_id = $attribute_group_id;
				$this_attribute_group_name = $attribut_settings["group"];
			}
			
			if( empty( $this->attributes_all[ $this_attribute_group_name ]["attributes"][ $field_value ] ) ) {
				$attribut_info = array(
					"attribute_description" => array(),
					"attribute_group_id" => $this_attribute_group_id,
					"sort_order" => 0
				);
				foreach( $this->languages as $language ){
					$attribut_info["attribute_description"][ $language["language_id"] ] = array(
						"name" => $field_value,
					);
				}
				
				$attribute_id = $this->model_catalog_import->addAttribute( $attribut_info );
				$this->attributes_all[ $this_attribute_group_name ]["attributes"][ $field_value ] = $attribute_id;
			} else {
				$attribute_id = $this->attributes_all[ $this_attribute_group_name ]["attributes"][ $field_value ];
			}

			$product_attribute = array("attribute_id" => $attribute_id, "product_attribute_description" => array());
			foreach ($this->languages as $language) {
				$product_attribute["product_attribute_description"][$language["language_id"]] = array("text" => $field_text);
			}
			$product_attributes[] = $product_attribute;
		}
		$this->model_catalog_import->addProductAttributes( $product_id, $product_attributes );
	}
	
	private function import_product( $rowData, $product_settings, $product_option, $manufacturer_id = 0, $category_id = array()
, $import_settings = array() ){
		$_product_description_fields = array();
		foreach( $this->languages as $language ){

			$product_description_name = !empty( $product_settings["product_description"][ $language["language_id"] ]["name"] ) ?
					str_replace( array_keys( $rowData ), $rowData, $product_settings["product_description"][ $language["language_id"] ]["name"] ) : "";

			$product_description_meta_description = !empty( $product_settings["product_description"][ $language["language_id"] ]["meta_description"] ) ?
					str_replace( array_keys( $rowData ), $rowData, $product_settings["product_description"][ $language["language_id"] ]["meta_description"] ) : "";

			$product_description_meta_keyword = !empty( $product_settings["product_description"][ $language["language_id"] ]["meta_keyword"] ) ?
					str_replace( array_keys( $rowData ), $rowData, $product_settings["product_description"][ $language["language_id"] ]["meta_keyword"] ) : "";

			$product_description_description = !empty( $product_settings["product_description"][ $language["language_id"] ]["description"] ) ?
					str_replace( array_keys( $rowData ), $rowData, $product_settings["product_description"][ $language["language_id"] ]["description"] ) : "";

			$product_description_tag = !empty( $product_settings["product_tag"][ $language["language_id"] ] ) ?
				str_replace( array_keys( $rowData ), $rowData, $product_settings["product_tag"][ $language["language_id"] ] ) : "";

			$_product_description_fields[ $language["language_id"] ] = array(
				"name" => $product_description_name,
				"meta_description" => $product_description_meta_description,
				"meta_keyword" => $product_description_meta_keyword,
				"description" => $product_description_description,
				"tag" => $product_description_tag
			);


			$this->seoName = $product_description_name;


			// Пропускаем продукт если у него пустое значение поля.

			if( empty($product_description_tag) && !empty( $product_settings["product_required"]["product_tag"] )) {
				$this->import_product_error ++;
				return false;
			}

			if( empty( $product_description_name ) && !empty( $product_settings["product_description_required"]["name"] )) {
				$this->import_product_error ++;
				return false;
			}

			if( empty( $product_description_meta_description ) && !empty( $product_settings["product_description_required"]["meta_description"] )) {
				$this->import_product_error ++;
				return false;
			}

			if( empty( $product_description_meta_keyword ) && !empty( $product_settings["product_description_required"]["meta_keyword"] )) {
				$this->import_product_error ++;
				return false;
			}

			if( empty( $product_description_description ) && !empty( $product_settings["product_description_required"]["description"] )) {
				$this->import_product_error ++;
				return false;
			}

			if( empty( $product_settings["price"] ) && $product_settings["price"] == 0 ) {
				$this->import_product_error ++;
				return false;
			}

		}

		//markup
		$price = !empty( $product_settings["price"] ) ?
				str_replace( array(
					" ", "'", '"', ","
				), array(
					"", "", "", '.'
				), str_replace( array_keys( $rowData ), $rowData, $product_settings["price"] ) ) : 0;

		if( !empty( $product_settings["markup"] ) ){

			foreach( $product_settings["markup"] as $markup ){
				if( $price > $markup["ot"] && $price < $markup["do"] ) {

					$markup["percent"] = trim( $markup["percent"] );
					if( is_numeric( $markup["percent"] ) ) {
						$price = $price + ( ( $price / 100 ) * $markup["percent"] );
					}

					$markup["add"] = trim( $markup["add"] );
					if( is_numeric( $markup["add"] ) ) {
						$price = $price + $markup["add"];
					}

					$markup["rounding"] = trim( $markup["rounding"] );
					if( is_numeric( $markup["rounding"] ) ) {
						$price = round( $price, $markup["rounding"] );
					}

					break;
				}
			}
		}

		$product_image = array();
		$additional_images = explode( trim( $product_settings["additional_images_delimeter"] ), str_replace( array_keys( $rowData ), $rowData, trim( $product_settings["additional_images"] ) ) );
		$add_img_sort_order = 1;
		foreach( $additional_images as $add_image_key => $add_image_value ) {
			$additional_images[ $add_image_key ] = str_replace("//", "/", $this->images_dir . "/" . trim($add_image_value) );
			if( file_exists( $additional_images[ $add_image_key ] ) && is_file( $additional_images[ $add_image_key ] ) ) {
				$product_image[] = array(
					"image" => str_replace( DIR_IMAGE, "", $additional_images[ $add_image_key ] ),
					"sort_order" => $add_img_sort_order ++
				);
			}
		}

		$model = !empty( $product_settings["model"] ) ?
			str_replace( array_keys( $rowData ), $rowData, $product_settings["model"] ) : "";

		$image = !empty( $product_settings["image"] ) && file_exists( DIR_IMAGE . str_replace( array_keys( $rowData ), $rowData, $product_settings["image"] ) ) ?
			str_replace( array_keys( $rowData ), $rowData, $product_settings["image"] ) : "";

		if (empty( $image )) {
			$manufacturer_setting_name = "data/battery/" . $import_settings[4]['name'];
			$image = !empty($manufacturer_setting_name) && file_exists(DIR_IMAGE . str_replace(array_keys($rowData), $rowData, $manufacturer_setting_name) . '.jpg') ? (str_replace(array_keys($rowData), $rowData, $manufacturer_setting_name). '.jpg') : "";
		}


		if( empty( $image ) && !empty( $product_image ) ) {
			$image_one = array_shift( $product_image );
			$image = $image_one["image"];
		}

		$sku = !empty( $product_settings["sku"] ) ?
			str_replace( array_keys( $rowData ), $rowData, $product_settings["sku"] ) : $model;

		$upc = !empty( $product_settings["upc"] ) ?
			str_replace( array_keys( $rowData ), $rowData, $product_settings["upc"] ) : "";

		$location = !empty( $product_settings["location"] ) ?
			str_replace( array_keys( $rowData ), $rowData, $product_settings["location"] ) : "";

		$quantity = (int) !empty( $product_settings["quantity"] ) ?
			str_replace( array_keys( $rowData ), $rowData, $product_settings["quantity"] ) : "";

		if( empty( $price ) && !empty( $product_settings["product_required"]["price"] ) ) {
			$this->import_product_error ++;
			return false;
		}

		if( empty( $model ) && !empty( $product_settings["product_required"]["model"] ) ) {
			$this->import_product_error ++;
			return false;
		}

		if( empty( $image ) && !empty( $product_settings["product_required"]["image"] ) ) {
			$this->import_product_error ++;
			return false;
		}

		if( empty( $product_image ) && !empty( $product_settings["product_required"]["additional_images"] ) ) {
			$this->import_product_error ++;
			return false;
		}

		if( empty( $sku ) && !empty( $product_settings["product_required"]["sku"] ) ) {
			$this->import_product_error ++;
			return false;
		}
		
		if( empty( $upc ) && !empty( $product_settings["product_required"]["upc"] ) ) {
			$this->import_product_error ++;
			return false;
		}
		
		if( empty( $location ) && !empty( $product_settings["product_required"]["location"] ) ) {
			$this->import_product_error ++;
			return false;
		}
		
		if( empty( $quantity ) && !empty( $product_settings["product_required"]["quantity"] ) ) {
			$this->import_product_error ++;
			return false;
		}
		
		// stock status
		$stock_status_id = !empty( $product_settings["stock_status_id"] ) ? (int) $product_settings["stock_status_id"] : 5;

		$product_info = array(
			"product_description" => $_product_description_fields,
			"model" => $model,
			"sku" => $sku,
			"upc" => $upc,
			"location" => $location,
			"price" => $price,
			"tax_class_id" => 0,
			"quantity" => $quantity,
			"minimum" => 1,
			"subtract" => 1,
			"stock_status_id" => $stock_status_id,
			"shipping" => 1,
			"keyword" => $this->mySEO($this->seoName),
			"image" => $image,
			"date_available" => date("Y-m-d"),
			"length" => '',
			"width" => '',
			"height" => '',
			"length_class_id" => 1,
			"weight" => '',
			"weight_class_id" => 1,
			"status" => 1,
			"sort_order" => 1,
			"manufacturer_id" => (int) $manufacturer_id,
			"product_category" => $category_id,
			"product_store" => array(
				0 => 0
			),
			"product_image" => $product_image,
			"related" => '',
			"product_option" => $product_option,
			"option" => '',
			"points" => '',
			"product_reward" =>array(),
			"product_layout" =>array(
				0 => array(
					"layout_id" => ''
				)
			)
		);
		
		if( $this->product_update == "name" ) {
			$product_id = $this->model_catalog_import->getProductByName( $product_description_name );
		} elseif( $this->product_update == "sku" ) {
			$product_id = $this->model_catalog_import->getProductBySku( $product_info["sku"] );
		}
		
		if( $product_id ) {
			// Update Product
			$this->model_catalog_import->editProduct( $product_id, $product_info );
			
			$this->import_product_updated ++;
			
		} else {
			// Add New Product
			$product_id = $this->model_catalog_import->addProduct( $product_info );
		
			$this->import_product_added ++;
			
		}
		
		return $product_id;

	}
	
	private function mySEO($data) {

		$data = trim($data);

		$letters_replace = array(
			"а"=>"a","А"=>"a", "б"=>"b","Б"=>"b",
			"в"=>"v","В"=>"v", "г"=>"g","Г"=>"g",
			"д"=>"d","Д"=>"d", "е"=>"e","Е"=>"e",
			"ж"=>"zh","Ж"=>"zh", "з"=>"z","З"=>"z",
			"и"=>"i","И"=>"i", "й"=>"y","Й"=>"y",
			"к"=>"k","К"=>"k", "л"=>"l","Л"=>"l",
			"м"=>"m","М"=>"m", "н"=>"n","Н"=>"n",
			"о"=>"o","О"=>"o", "п"=>"p","П"=>"p",
			"р"=>"r","Р"=>"r", "с"=>"s","С"=>"s",
			"т"=>"t","Т"=>"t", "у"=>"u","У"=>"u",
			"ф"=>"f","Ф"=>"f", "х"=>"h","Х"=>"h",
			"ц"=>"c","Ц"=>"c", "ч"=>"ch","Ч"=>"ch",
			"ш"=>"sh","Ш"=>"sh", "щ"=>"sch","Щ"=>"sch",
			"ъ"=>"'","Ъ"=>"'", "ы"=>"i","Ы"=>"i",
			"ь"=>"","Ь"=>"", "э"=>"e","Э"=>"e",
			"ю"=>"yu","Ю"=>"yu", "я"=>"ya","Я"=>"ya"
		);

		$search = array(
					',',
					'.',
					' ',
					'`',
// 					'\'',
					'"',
					'/',
					'\\',
					'[',
					']',
					'|',
					'{',
					'}',
					'(',
					')',
					'!',
					'~',
					'&',
					'*',
					'^',
					'_',
					'+',
					'-----',
					'----',
					'---',
					'--');

		$replace = '-';

		$data = iconv("UTF-8","UTF-8//TRANSLIT//IGNORE",strtr($data, $letters_replace));

		$dataInSeo = strtolower(str_replace($search, $replace, $data));

		return $dataInSeo;

	}
	
	private function import_manufacturer( $rowData, $manufacturer_settings ){
	
		$_manufacturer_name = str_replace( array_keys( $rowData ), $rowData, $manufacturer_settings["name"] );
		
		$manufacturer_ids = $this->manufacturer_ids;
		
		if( !empty( $_manufacturer_name ) && empty( $manufacturer_ids[ $_manufacturer_name ] ) ) {
		
			$manufacturer_info = array(
				"name" => $_manufacturer_name,
				"manufacturer_store" => array(
					0 => 0
				),
				"keyword" => '',
				"image" => '',
				"sort_order" => 0
			);
		
			$manufacturer_id = $this->model_catalog_import->addManufacturer( $manufacturer_info );

			$manufacturer_ids[ $_manufacturer_name ] = $manufacturer_id;

			$this->manufacturer_ids = $manufacturer_ids;
		
		} elseif( !empty( $manufacturer_ids[ $_manufacturer_name ] ) ) {
			$manufacturer_id = $manufacturer_ids[ $_manufacturer_name ];
		} else {
			$manufacturer_id = 0;
		};

		return $manufacturer_id;

	}

	private function collectProductOption($rowData, $option_settings) {
		$this->load->model('catalog/import');

		$_product_option = array();

		foreach($option_settings['options'] as $option) {
			$option_id = (int)str_replace(array_keys($rowData), $rowData, $option['option_id']);

			$product_option_value = array();

			foreach($option['values'] as $value) {
				$option_value_name = str_replace(array_keys($rowData), $rowData, $value['option_value_name']);

				$option_value_id = $this->model_catalog_import->getOptionValueId($option_id, $option_value_name);

				if(!$option_value_id) continue;

				$product_option_value[] = array(
					'option_value_id'	=> (int)$option_value_id,
					'quantity'			=> (int)str_replace(array_keys($rowData), $rowData, $value['quantity']),
					'subtract'			=> (int)$value['subtract'],
					'price'				=> (float)str_replace(array_keys($rowData), $rowData, $value['price']),
					'price_prefix'		=> $value['price_prefix'],
					'points'			=> (float)str_replace(array_keys($rowData), $rowData, $value['points']),
					'points_prefix'		=> $value['points_prefix'],
					'weight'			=> (float)str_replace(array_keys($rowData), $rowData, $value['weight']),
					'weight_prefix'		=> $value['weight_prefix']
				);
			}

			if(!$product_option_value) continue;

			$_product_option[] = array(
				'type'						=> $this->model_catalog_import->getOptionType($option_id),
				'option_id'					=> $option_id,
				'option_value'				=> '',
				'required'					=> (int)str_replace(array_keys($rowData), $rowData, $option['required']),
				'product_option_value'		=> $product_option_value
			);
		}

		return $_product_option;
	}

	private function import_category( $rowData, $category_settings ){
		
		$category_ids = $this->category_ids;
		$parent_id = 0;
		
		$category_paths = !empty( $category_settings["category_path"] ) ? explode(",", htmlspecialchars_decode ( $category_settings["category_path"] ) ): array();
		$return = array();
		
		foreach( $category_paths as $key => $category_path ){
		
			$_categorys = !empty( $category_path ) ? explode( $this->category_delimiter , str_replace( array_keys( $rowData ), $rowData, $category_path ) ) : array() ;
			$_categorys_new = array();
			$parent_id = 0;
			
			foreach( $_categorys as $_key => $_category ){
				$_category_name_ = str_replace( array_keys( $rowData ), $rowData, $_category );
				if (empty($_category_name_)) {
					continue;
				}
				$_category = trim( $_category );
			
				$_category_name = array();
				$_category_meta_description = array();
				$_category_meta_keyword = array();
				$_category_description = array();

				foreach( $this->languages as $language ){
					$_category_name[ $language["language_id"] ] = str_replace( array_keys( $rowData ), $rowData, $_category );
					$_category_meta_description[ $language["language_id"] ] = !empty( $category_settings["category_description"][ $language["language_id"] ]["meta_description"] ) ? str_replace( array_keys( $rowData ), $rowData, $category_settings["category_description"][ $language["language_id"] ]["meta_description"] ) : "";
					$_category_meta_keyword[ $language["language_id"] ] = !empty( $category_settings["category_description"][ $language["language_id"] ]["meta_keyword"] ) ? str_replace( array_keys( $rowData ), $rowData, $category_settings["category_description"][ $language["language_id"] ]["meta_keyword"] ) : "";
					$_category_description[ $language["language_id"] ] = !empty( $category_settings["category_description"][ $language["language_id"] ]["description"] ) ? str_replace( array_keys( $rowData ), $rowData, $category_settings["category_description"][ $language["language_id"] ]["description"] ) : "";
				}

				$_categorys_new[$_key] = trim( str_replace( array_keys( $rowData ), $rowData, $_category ) );
				$new_category_path = implode( " &gt; ", $_categorys_new );

				if( !empty($category_ids[ $new_category_path ]) ){
					$parent_id = $category_ids[$new_category_path];
				} else {
					$_category_description_fields = array();
					foreach( $this->languages as $language ){
						$_category_description_fields[ $language["language_id"] ] = array(
							"name" => $_category_name[ $language["language_id"] ],
							"meta_description" => $_category_meta_description[ $language["language_id"] ],
							"meta_keyword" => $_category_meta_keyword[ $language["language_id"] ],
							"description" => $_category_description[ $language["language_id"] ],
						);
					}
					$category_info = array(
						'category_description' => $_category_description_fields,
						'parent_id' => $parent_id,
						'category_store' => array(
							0 => 0
						),
						'keyword' => '',
						'image' => '',
						'column' => 1,
						'sort_order' => 0,
						'status' => '1',
						'category_layout' => array(
							0 => array(
								'layout_id' => ''
							)
						),
					);

					$category_ids[$new_category_path] = $this->model_catalog_import->addCategory( $category_info );
					$parent_id = $category_ids[$new_category_path];
					
					
				}
			}
			
			$return[] = $category_ids[$new_category_path];
			
		}

		$this->category_ids = $category_ids;

		return $return;
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['import_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 1024)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}



function error_handler_for_export($errno, $errstr, $errfile, $errline) {
	global $config;
	global $log;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
		
	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

?>