<?php
class ControllerCatalogRequest extends Controller {
	private $error = array();
	private $modelName = "catalog/request";
	private $fields = array(
				"name" => array(
					"type" => "S", //string
					"default" => "",
					"multy" => true,
					"sort" => true,
					"filter" => true,
					"list" => true
					
				),
				"descr" => array(
					"type" => "T", // text
					"default" => "",
					"multy" => true,
				),
				"status" => array(
					"type" => "I", // int
					"default" => "0",
					"sort" => true,
					"filter" => true,
					"variants" => array(0,1,2),
					"list" => true,
				),
				"date" => array(
					"type" => "D", // date
					"default" => "",
					"sort" => true,
					"filter" => true,
					"list" => true,
				),				
			);

	public function index() {
		$this->load->model($this->modelName);
		$this->model_catalog_request->DBconfig($this->modelName,$this->fields);
		$this->data = array_merge( $this->data , $this->language->load($this->modelName));
		$this->document->setTitle($this->language->get('heading_title'));
		$this->getList();
	}

	public function saveOrUpdate() {
		$this->data = array_merge( $this->data , $this->language->load($this->modelName));
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->modelName);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if (isset($this->request->get['request_id'])) {
				$this->model_catalog_request->saveOrUpdate($this->modelName, $this->fields, $this->request->post, $this->request->get['request_id']);
			} else {
				$this->model_catalog_request->saveOrUpdate($this->modelName, $this->fields, $this->request->post);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order','page'));
			$this->redirect($this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {

		$this->data = array_merge( $this->data , $this->language->load($this->modelName));
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->modelName);

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $request_id) {
				$this->model_catalog_request->deleteVacancy($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order','page'));
			$this->redirect($this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getList();
	}

	protected function getList() {
		function add_alter(&$item1, $key, $prefix) {
			$item1 = $prefix . $item1;
		}

		$sort = (isset($this->request->get['sort'])) ? $this->request->get['sort'] : "name";
		$order = (isset($this->request->get['order'])) ? $this->request->get['order'] : "ASC";
		$page = (isset($this->request->get['page'])) ? $this->request->get['page'] : 1;
		
		$filter_array  = array_keys($this->document->getFilteredFields($this->fields));		
		array_walk($filter_array, 'add_alter', 'filter_');
		
		$url = $this->url->generateUrlFromArray($this->request->get, array_merge(array('sort','order','page'),$filter_array) );	

		$this->data['breadcrumbs'] = $this->document->generateBreadCrumbs(
				array(
					array(
						"text" => $this->language->get('heading_title'),
						"href" => $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL')
					)
				));

		$this->data['insert'] = $this->url->link($this->modelName . '/saveOrUpdate', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link($this->modelName . 'catalog/request/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['list'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		foreach ($filter_array as $v) {
			$data[$v] = (isset($this->request->get[$v])) ? $this->request->get[$v] : null;
		}
		foreach ($data as $k => $v) {
			$this->data[$k] = $v;
		}

		$request_total = $this->model_catalog_request->getTotalList($this->modelName);

		$results = $this->model_catalog_request->getList($data,$this->modelName,$this->fields);

		foreach ($results as $k => $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/request/saveOrUpdate', 'token=' . $this->session->data['token'] . '&request_id=' . $result['request_id'] . $url, 'SSL')
			);
			$results[$k]['action'] = $action;
			$results[$k]['textStatus'] = $this->statusArray[$result['status']];
			$results[$k]['selected'] = isset($this->request->post['selected']) && in_array($result['request_id'], $this->request->post['selected']);
		}

		$this->data['requests'] = $results;

		$this->data['statusArray'] = $this->statusArray;


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

		$this->data['sort_name'] = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_phone'] = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . '&sort=phone' . $url, 'SSL');
		$this->data['sort_request_date'] = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . '&sort=request_date' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order','filter_name','filter_status'));

		$pagination = new Pagination();
		$pagination->total = $request_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'catalog/request_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		$this->data['token'] = $this->session->data['token'];

		$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order','page','filter_name','filter_status'));

		$this->data['breadcrumbs'] = $this->document->generateBreadCrumbs(
				array(
					array(
						"text" => $this->language->get('heading_title'),
						"href" => $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL')
					)
				));

		$urlAdd = (!isset($this->request->get['request_id'])) ? "" : '&request_id=' . $this->request->get['request_id'];
		$this->data['action'] = $this->url->link('catalog/request/saveOrUpdate', 'token=' . $this->session->data['token'] . $urlAdd . $url, 'SSL');

		if (isset($this->request->get['request_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$request_info = $this->model_catalog_request->getVacancy($this->request->get['request_id']);
		}

		$this->data['cancel'] = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$db_fields = array(
			"name" => "",
			"descr" => "",
			"keyword" => "",
			"short_descr" => "",
			"sort_order" => ""
		);

		foreach($db_fields as $field => $def_value) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($request_info)) {
				$this->data[$field] = $request_info[$field];
			} else {
				$this->data[$field] = $def_value;
			}
		}

		$this->template = 'catalog/request_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', $this->modelName)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', $this->modelName)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

}
?>