<?php
class ControllerModuleReviews extends Controller {
	protected function index($setting) {

		$this->data = array_merge( $this->data , $this->language->load('product/product'));
		
		$this->load->model('catalog/review');
		
		$this->data['reviews'] = array();
		
		$month_array = array(
			1 => 'января',
			2 => 'февраля',
			3 => 'марта',
			4 => 'апреля',
			5 => 'мая',
			6 => 'июня',			
			7=> 'июля',
			8 => 'августа',
			9 => 'сентября',
			10 => 'октября',
			11 => 'ноября',
			12 => 'декабря'
		);		
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId(0);
			
		$results = $this->model_catalog_review->getReviewsByProductId(0, 0, $setting['limit']);
		
		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => $result['text'],
				'date_added' => date("j ", strtotime($result['date_added'])) . $month_array[date("n", strtotime($result['date_added']))] . date(" Y", strtotime($result['date_added']))
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/reviews.tpl';
		} else {
			$this->template = 'default/template/module/reviews.tpl';
		}

		$this->render();
	}
	
	public function add() {
		$this->data = array_merge( $this->data , $this->language->load('product/product'));
		$this->load->model('catalog/review');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}
			
			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}				
				
			if (!isset($json['error'])) {
				$this->model_catalog_review->addReview(0,$this->request->post);
				
				$json['success'] = $this->language->get('text_success');
			}
			$this->response->setOutput(json_encode($json));
		}		
	}
	public function getList() {
		$this->data = array_merge( $this->data , $this->language->load('product/reviews'));
		
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/reviews/getList'),
        		'separator' => $this->language->get('text_separator')
      		);		      			
		
		$month_array = array(
			1 => 'января',
			2 => 'февраля',
			3 => 'марта',
			4 => 'апреля',
			5 => 'мая',
			6 => 'июня',			
			7=> 'июля',
			8 => 'августа',
			9 => 'сентября',
			10 => 'октября',
			11 => 'ноября',
			12 => 'декабря'
		);	
		
		$this->load->model('catalog/review');
		
		$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->data['reviews'] = array();
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId(0);
			
		$results = $this->model_catalog_review->getReviewsByProductId(0, 0, 40, 'date_added DESC,');
		
		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => $result['text'],
				'answer'       => $result['answer'],
				'date_added' => date("j ", strtotime($result['date_added'])) . $month_array[date("n", strtotime($result['date_added']))] . date(" Y", strtotime($result['date_added']))
			);
		}
		
		$this->load->model('tool/image');
		$this->data['small_logo'] = $this->model_tool_image->resize('data/logo_icon.jpg',50, 50);
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/reviews_page.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/reviews_page.tpl';
		} else {
			$this->template = 'default/template/module/reviews_page.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
}
?>