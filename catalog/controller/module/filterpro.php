<?php
class ControllerModuleFilterPro extends Controller {
	protected function index($setting) {
		$this->data = array_merge( $this->data , $this->language->load('module/filterpro'));
		$this->data['setting'] = $setting;

		if(VERSION == '1.5.0') {
			$filterpro_setting = unserialize($this->config->get('filterpro_setting'));
		} else {
			$filterpro_setting = $this->config->get('filterpro_setting');
		}

		$category_id = 0;
		$this->data['path'] = "";
		if(isset($this->request->get['path'])) {
			$this->data['path'] = $this->request->get['path'];
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = array_pop($parts);
		}
		$this->data['category_id'] = $category_id;

		$this->load->model('module/filterpro');

		$this->data['manufacturers'] = false;
		if($filterpro_setting['display_manufacturer'] != 'none') {
			$this->data['manufacturers'] = $this->model_module_filterpro->getManufacturersByCategoryId($category_id);
			$this->data['display_manufacturer'] = $filterpro_setting['display_manufacturer'];
		}
		$this->data['options'] = $this->model_module_filterpro->getOptionsByCategoryId($category_id);
		foreach($this->data['options'] as $i => $option) {
			$display_option = $filterpro_setting['display_option_' . $option['option_id']];
			if($display_option != 'none') {
				$this->data['options'][$i]['display'] = $display_option;
			} else {
				unset($this->data['options'][$i]);
			}
		}

		$this->data['attributes'] = $this->model_module_filterpro->getAttributesByCategoryId($category_id);

		foreach($this->data['attributes'] as $j => $attribute_group) {
			foreach($attribute_group['attribute_values'] as $attribute_id => $attribute) {
				$display_attribute = $filterpro_setting['display_attribute_' . $attribute_id];
				if($display_attribute != 'none') {
					$this->data['attributes'][$j]['attribute_values'][$attribute_id]['display'] = $display_attribute;
				} else {
					unset($this->data['attributes'][$j]['attribute_values'][$attribute_id]);
					if(!$this->data['attributes'][$j]['attribute_values']) {
						unset($this->data['attributes'][$j]);
					}
				}
				if ($display_attribute == 'slide')  {
					$this->data['attributes'][$j]['attribute_values'][$attribute_id]['min'] = (int) min($this->data['attributes'][$j]['attribute_values'][$attribute_id]['values']);
					$this->data['attributes'][$j]['attribute_values'][$attribute_id]['max'] = (int) max($this->data['attributes'][$j]['attribute_values'][$attribute_id]['values']);
				}				
			}
		}

		$this->data['price_slider'] = $filterpro_setting['price_slider'];

		if($this->data['options'] || $this->data['manufacturers'] || $this->data['attributes'] ||$this->data['price_slider']) {
			$this->document->addScript('catalog/view/javascript/jquery/jquery.tmpl.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/jquery.deserialize.js');
			$this->document->addScript('catalog/view/javascript/filterpro.min.js');
			$this->document->addStyle('catalog/view/theme/ars/stylesheet/filterpro.css');
		}

		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filterpro.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/filterpro.tpl';
		} else {
			$this->template = 'default/template/module/filterpro.tpl';
		}

		$this->render();
	}

	private function array_clean(array $haystack) {
		foreach($haystack as $key => $value) {
			if(is_array($value)) {
				$haystack[$key] = $this->array_clean($value);
				if(!count($haystack[$key])) {
					unset($haystack[$key]);
				}
			} elseif(is_string($value)) {
				$value = trim($value);
				if(!$value) {
					unset($haystack[$key]);
				}
			}
		}
		return $haystack;
	}

	public function getProducts() {

		//		$cache = md5(http_build_query($this->request->post));
		//		$json = $this->cache->get('filterpro.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $cache);
		//		if(!$json) {

		$this->language->load('module/filterpro');

		if(VERSION == '1.5.0') {
			$filterpro_setting = unserialize($this->config->get('filterpro_setting'));
		} else {
			$filterpro_setting = $this->config->get('filterpro_setting');
		}


		$page = 1;
		if(isset($this->request->post['page'])) {
			$page = (int)$this->request->post['page'];
		}

		if(isset($this->request->post['sort'])) {
			$sort = $this->request->post['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if(isset($this->request->post['order'])) {
			$order = $this->request->post['order'];
		} else {
			$order = 'ASC';
		}

		if(isset($this->request->post['limit'])) {
			$limit = $this->request->post['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}


		$this->load->model('module/filterpro');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$manufacturer = false;
		if(isset($this->request->post['manufacturer'])) {
			$manufacturer = $this->array_clean($this->request->post['manufacturer']);
			if(!count($manufacturer)) {
				$manufacturer = false;
			}
		}

		$option_value = false;
		if(isset($this->request->post['option_value'])) {
			$option_value = $this->array_clean($this->request->post['option_value']);
			if(!count($option_value)) {
				$option_value = false;
			}
		}

		$attribute_value = false;
		if(isset($this->request->post['attribute_value'])) {
			$attribute_value = $this->array_clean($this->request->post['attribute_value']);
			if(!count($attribute_value)) {
				$attribute_value = false;
			}
		}

		$data = array(
			'option_value' => $option_value,
			'manufacturer' => $manufacturer,
			'attribute_value' => $attribute_value,
			'category_id' => $this->request->post['category_id'],
			'product_id' => isset($this->request->post['product_id']) ? $this->request->post['product_id'] : "",
			'min_price' => $this->request->post['min_price'],
			'max_price' => $this->request->post['max_price'],
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order
		);

		$product_total = $this->model_module_filterpro->getTotalProducts($data);

		$totals_manufacturers = $this->model_module_filterpro->getTotalManufacturers($data);

		$totals_options = $this->model_module_filterpro->getTotalOptions($data);

		$totals_attributes = $this->model_module_filterpro->getTotalAttributes($data);

		$products = $this->model_module_filterpro->getProducts($data);


		$result = array();

		$min_price = false;
		$max_price = false;

		if(isset($this->request->post['getPriceLimits']) && $this->request->post['getPriceLimits']) {
			$priceLimits = $this->model_module_filterpro->getPriceLimits($data);
			$min_price = $priceLimits['min_price'];
			$max_price = $priceLimits['max_price'];
		}

		$url = (isset($_POST['path'])) ? ("&path=" . $_POST['path']) : "";

		$this->data['products'] = $this->model_catalog_product->prepareProductList($products,$url);

		$this->data = array_merge( $this->data , $this->language->load('product/category'));

		$this->template = $this->config->get('config_template') . '/template/product/product_list.tpl';
		$result_template = $this->render();
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'page={page}';

		$min_price = $this->currency->convert($min_price, $this->config->get('config_currency'), $this->currency->getCode());
		$max_price = $this->currency->convert($max_price, $this->config->get('config_currency'), $this->currency->getCode());
		$json = json_encode(array('result' => $result_template, 'min_price' => $min_price, 'max_price' => $max_price, 'pagination' => $pagination->render(),
								 'totals_data' => array('manufacturers' => $totals_manufacturers, 'options' => $totals_options, 'attributes' => $totals_attributes)));

		//			$this->cache->set('filterpro.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $cache, $json);
		//		}

		$this->response->setOutput($json);
	}
}

?>