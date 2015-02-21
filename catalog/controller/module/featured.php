<?php

class ControllerModuleFeatured extends Controller {

	protected function index($setting) {



		$this->data = array_merge( $this->data , $this->language->load('product/category'));

		$this->data = array_merge( $this->data , $this->language->load('module/featured'));



		$this->load->model('catalog/product');

		

		$this->load->model('tool/image');



		$this->data['products'] = array();



		$products = explode(',', $this->config->get('featured_product'));		



		if (empty($setting['limit'])) {

			$setting['limit'] = 5;

		}

		
		shuffle($products);
		$products = array_slice($products, 0, (int)$setting['limit']);



		$results = array();



		foreach($products as $product_id) {

			$results[] = $this->model_catalog_product->getProduct($product_id);

		}



		$this->data['products'] = $this->model_catalog_product->prepareProductList($results);



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {

			$this->template = $this->config->get('config_template') . '/template/module/featured.tpl';

		} else {

			$this->template = 'default/template/module/featured.tpl';

		}



		$this->render();

	}

}

?>