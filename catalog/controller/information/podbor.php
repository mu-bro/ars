<?php 
class ControllerInformationPodbor extends Controller {
	public function index() {

	$this->data = array_merge( $this->data , $this->language->load('information/podbor'));

	$this->load->model('catalog/information');

	$this->data['breadcrumbs'] = array();

	$this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home'),
		'separator' => false
	);

	$this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('information/podbor'),
		'separator' => $this->language->get('text_separator')
	);	

	$this->document->setTitle($this->language->get('heading_title'));


	$this->data['heading_title'] = $this->language->get('heading_title');

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/podbor.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/information/podbor.tpl';
	} else {
		$this->template = 'default/template/information/podbor.tpl';
	}

	$this->children = array(
		'common/column_left',
		'common/column_right',
		'common/content_top',
		'common/content_bottom',
		'common/footer',
		'common/header'
	);

	$this->response->setOutput($this->render());
	}

}
?>