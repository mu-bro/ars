<?php
class ControllerModuleBestSeller extends Controller {
	protected function index($setting) {

		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/animate.min.css');
		$this->data = array_merge( $this->data , $this->language->load('module/bestseller'));
		$this->data = array_merge( $this->data , $this->language->load('product/category'));
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');

		$this->data['products'] = array();
		$product_id = $this->request->get['product_id'];

		if (isset($product_id)) {
			$attributes = $this->model_catalog_product->getProductAttributes($product_id);
			$filter = array();
			foreach ($attributes as $attribute_group) {
				foreach ($attribute_group['attribute'] as $attribute) {
					if (in_array($attribute['attribute_id'],array(13,14))) {
						$filter['attribute_value'][$attribute['attribute_id']] = array($attribute['text'] - 2, $attribute['text'] - 1, $attribute['text'], $attribute['text'] + 1, $attribute['text'] + 2);	
					} elseif ($attribute['attribute_id'] == 12) {
						$filter['attribute_value'][$attribute['attribute_id']] = array($attribute['text'] - 3, $attribute['text'] - 2, $attribute['text'] - 1, $attribute['text'],
							$attribute['text'] + 1, $attribute['text'] + 2, $attribute['text'] + 3);
					} else {
						$filter['attribute_value'][$attribute['attribute_id']][] = $attribute['text'];
					}

				}

			}//p($filter,$attributes);
			$filter["exclude_product"] = $product_id;
			$filter["limit"] = $setting['limit'];
			$results = $this->model_catalog_product->getProductsByFilter($filter);
			$this->load->model('tool/image');
			$this->data['products'] = $this->model_catalog_product->prepareProductList($results);
		} else {
			$this->data['products'] = array();
		}


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bestseller.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/bestseller.tpl';
		} else {
			$this->template = 'default/template/module/bestseller.tpl';
		}

		$this->render();
	}
}
?>