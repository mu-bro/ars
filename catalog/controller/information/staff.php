<?php 
class ControllerInformationStaff extends Controller {
	public function index() {

		$this->data = array_merge( $this->data , $this->language->load('information/staff'));

		$this->load->model('catalog/staff');

		$this->load->model('tool/image');		

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = $this->document->generateBreadCrumbs(
				array(
					array(
						"text" => $this->language->get('heading_title'),
						"href" => $this->url->link('information/staff')
					)
				));

		$this->data['categories'] = array();

		$results = $this->model_catalog_staff->getStaff();

		foreach ($results as $k => $result) {
			if ($result['image']) {
				$results[$k]['image'] = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$results[$k]['image'] = false;
			}

			$results[$k]['href'] = $this->url->link("information/staff/info","staff_id=" . $result['staff_id']);
			$results[$k]['short_descr'] = str_replace("\n","<br/>",$result['short_descr']);
		}

		$this->data['results'] = $results;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/staff_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/staff_list.tpl';
		} else {
			$this->template = 'default/template/information/staff_list.tpl';
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

	public function info() {

		$this->document->addScript('catalog/view/javascript/jquery/fancybox/jquery.fancybox.pack.js');
		$this->document->addStyle('catalog/view/javascript/jquery/fancybox/jquery.fancybox.css');
			
		$this->data = array_merge( $this->data , $this->language->load('information/staff'));

		$this->load->model('catalog/staff');

		$this->load->model('tool/image'); 

		if (isset($this->request->get['staff_id'])) {
			$staff_id = (int)$this->request->get['staff_id'];
		} else {
			$staff_id = 0;
		} 

		$this->data['breadcrumbs'] = $this->document->generateBreadCrumbs(
				array(
					array(
						"text" => $this->language->get('text_brand'),
						"href" => $this->url->link('information/staff')
					)
				));		

		$staff_info = $this->model_catalog_staff->getStaffInfo($staff_id);

		if ($staff_info) {
			$this->document->setTitle($staff_info['name']);

			if ($staff_info['image']) {
				$staff_info['thumb'] = $this->model_tool_image->resize($staff_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$staff_info['popup'] = $this->model_tool_image->resize($staff_info['image'], 600, 600);
			} else {
				$staff_info['image'] = false;
			}

			$staff_info['descr'] = html_entity_decode($staff_info['descr'], ENT_QUOTES, 'UTF-8');

			$this->data['breadcrumbs'][] = array(
				'text'      => $staff_info['name'],
				'href'      => $this->url->link('information/staff/info', 'staff_id=' . $this->request->get['staff_id'] ),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['heading_title'] = $staff_info['name'];
			$this->data['staff_info'] = $staff_info;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/staff_info.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/staff_info.tpl';
			} else {
				$this->template = 'default/template/information/staff_info.tpl';
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
		} else {

			$url = $this->url->generateUrlFromArray($this->request->get,array('staff_id','sort','order','page','limit'));

			$this->document->setTitle($this->language->get('text_error'));

			$this->data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
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
}
?>