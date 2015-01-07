<?php
class ControllerModuleActions extends Controller {
	private $error = array();
	private $module_type = "actions";

	public function index() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );
		$this->load->model('fido/news');

		$this->model_fido_news->checkNews();

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['module_type'] = $this->module_type;

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting($this->module_type, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getModule();
	}

	public function insert() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );
		$this->load->model('fido/news');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_fido_news->addNews($this->request->post, $this->module_type);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/' . $this->module_type . '/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );
		$this->load->model('fido/news');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_fido_news->editNews($this->request->get['news_id'], $this->request->post, $this->module_type);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/' . $this->module_type . '/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );
		$this->load->model('fido/news');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $news_id) {
				$this->model_fido_news->deleteNews($news_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/' . $this->module_type . '/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function listing() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	private function getModule() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );
		$this->load->model('fido/news');

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['numchars'])) {
			$this->data['error_numchars'] = $this->error['numchars'];
		} else {
			$this->data['error_numchars'] = '';
		}

		if (isset($this->error['newspage_thumb'])) {
			$this->data['error_newspage_thumb'] = $this->error['newspage_thumb'];
		} else {
			$this->data['error_newspage_thumb'] = '';
		}

		if (isset($this->error['newspage_popup'])) {
			$this->data['error_newspage_popup'] = $this->error['newspage_popup'];
		} else {
			$this->data['error_newspage_popup'] = '';
		}

		if (isset($this->error['module_chars'])) {
			$this->data['error_module_chars'] = $this->error['module_chars'];
		} else {
			$this->data['error_module_chars'] = array();
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_module'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/' . $this->module_type, 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data[$this->module_type] = $this->url->link('module/' . $this->module_type . '/listing', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action'] = $this->url->link('module/' . $this->module_type, 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post[$this->module_type . '_headline_chars'])) {
			$this->data[$this->module_type . '_headline_chars'] = $this->request->post[$this->module_type . '_headline_chars'];
		} else {
			$this->data[$this->module_type . '_headline_chars'] = $this->config->get($this->module_type . '_headline_chars');
		}

		if (isset($this->request->post[$this->module_type . '_thumb_width'])) {
			$this->data[$this->module_type . '_thumb_width'] = $this->request->post[$this->module_type . '_thumb_width'];
		} else {
			$this->data[$this->module_type . '_thumb_width'] = $this->config->get($this->module_type . '_thumb_width');
		}

		if (isset($this->request->post[$this->module_type . '_thumb_height'])) {
			$this->data[$this->module_type . '_thumb_height'] = $this->request->post[$this->module_type . '_thumb_height'];
		} else {
			$this->data[$this->module_type . '_thumb_height'] = $this->config->get($this->module_type . '_thumb_height');
		}

		if (isset($this->request->post[$this->module_type . '_popup_width'])) {
			$this->data[$this->module_type . '_popup_width'] = $this->request->post[$this->module_type . '_popup_width'];
		} else {
			$this->data[$this->module_type . '_popup_width'] = $this->config->get($this->module_type . '_popup_width');
		}

		if (isset($this->request->post[$this->module_type . '_popup_height'])) {
			$this->data[$this->module_type . '_popup_height'] = $this->request->post[$this->module_type . '_popup_height'];
		} else {
			$this->data[$this->module_type . '_popup_height'] = $this->config->get($this->module_type . '_popup_height');
		}

		$this->data['modules'] = array();
		
		if (isset($this->request->post[$this->module_type . '_module'])) {
			$this->data['modules'] = $this->request->post[$this->module_type . '_module'];
		} elseif ($this->config->get($this->module_type . '_module')) {
			$this->data['modules'] = $this->config->get($this->module_type . '_module');
		}				
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/news.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getList() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );
		$this->load->model('fido/news');

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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/' . $this->module_type . '/listing', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['module'] = $this->url->link('module/' . $this->module_type, 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('module/' . $this->module_type . '/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('module/' . $this->module_type . '/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['module_type'] = $this->module_type;
		$this->data[$this->module_type] = array();

		$news_total = $this->model_fido_news->getTotalNews($this->module_type);

		$results = $this->model_fido_news->getNews($this->module_type);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/' . $this->module_type . '/update', 'token=' . $this->session->data['token'] . '&news_id=' . $result['news_id'], 'SSL')
			);

			$this->data[$this->module_type][] = array(
				'news_id'     => $result['news_id'],
				'title'       => $result['title'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_available'  => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    => isset($this->request->post['selected']) && in_array($result['news_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->template = 'module/news/list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data = array_merge( $this->data , $this->load->language('module/' . $this->module_type) );
		$this->load->model('fido/news');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

		if (isset($this->error['short_descr'])) {
			$this->data['error_short_description'] = $this->error['short_descr'];
		} else {
			$this->data['error_short_description'] = '';
		}		
         
		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}	
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/' . $this->module_type . '/listing', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['news_id'])) {
			$this->data['action'] = $this->url->link('module/' . $this->module_type . '/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/' . $this->module_type . '/update', 'token=' . $this->session->data['token'] . '&news_id=' . $this->request->get['news_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('module/' . $this->module_type . '/listing', 'token=' . $this->session->data['token'], 'SSL');

		if ((isset($this->request->get['news_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$news_info = $this->model_fido_news->getNewsStory($this->request->get['news_id']);
		}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['news_description'])) {
			$this->data['news_description'] = $this->request->post['news_description'];
		} elseif (isset($this->request->get['news_id'])) {
			$this->data['news_description'] = $this->model_fido_news->getNewsDescriptions($this->request->get['news_id']);
		} else {
			$this->data['news_description'] = array();
		}

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['news_store'])) {
			$this->data['news_store'] = $this->request->post['news_store'];
		} elseif (isset($news_info)) {
			$this->data['news_store'] = $this->model_fido_news->getNewsStores($this->request->get['news_id']);
		} else {
			$this->data['news_store'] = array(0);
		}			

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($news_info)) {
			$this->data['keyword'] = $news_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($news_info)) {
			$this->data['status'] = $news_info['status'];
		} else {
			$this->data['status'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($news_info)) {
			$this->data['image'] = $news_info['image'];
		} else {
			$this->data['image'] = '';
		}
        
		if (isset($this->request->post['date_available'])) {
			$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (isset($news_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($news_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() + 86400);
		}
		
		if (isset($this->request->post['date_added'])) {
			$this->data['date_added'] = $this->request->post['date_added'];
		} elseif (isset($news_info)) {
			$this->data['date_added'] = date('Y-m-d', strtotime($news_info['date_added']));
		} else {
			$this->data['date_added'] = date('Y-m-d', time());
		}		
		
		$this->load->model('tool/image');

		if (isset($news_info) && $news_info['image'] && file_exists(DIR_IMAGE . $news_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($news_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->template = 'module/news/form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post[$this->module_type. '_thumb_width'] || !$this->request->post[$this->module_type. '_thumb_height']) {
			$this->error['newspage_thumb'] = $this->language->get('error_newspage_thumb');
		}

		if (!$this->request->post[$this->module_type. '_popup_width'] || !$this->request->post[$this->module_type. '_popup_height']) {
			$this->error['newspage_popup'] = $this->language->get('error_newspage_popup');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['news_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}

			if (strlen($value['short_descr']) < 3 || strlen($value['short_descr']) > 600) {
				$this->error['short_descr'][$language_id] = "В описании должно быть от 3 до 600 символов!";
			}			
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
