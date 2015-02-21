<?php
class ControllerModuleSpecial extends Controller {
	protected function index($setting) {

		$this->data = array_merge( $this->data , $this->language->load('module/special'));
		$this->data = array_merge( $this->data , $this->language->load('product/category'));
		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/animate.min.css');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');

		$this->data['products'] = array();
		
		$data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($data);

		$this->data['products'] = $this->model_catalog_product->prepareProductList($results);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/special.tpl';
		} else {
			$this->template = 'default/template/module/special.tpl';
		}

		$this->render();
	}
}
?>