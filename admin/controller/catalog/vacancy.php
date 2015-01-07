<?php    
class ControllerCatalogVacancy extends Controller {
	private $error = array();
	private $modelName = "catalog/vacancy";

	public function index() {
		$this->data = array_merge( $this->data , $this->language->load($this->modelName));
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->modelName);
		$this->getList();
	}

	public function saveOrUpdate() {
		$this->data = array_merge( $this->data , $this->language->load($this->modelName));
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->modelName);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if (isset($this->request->get['vacancy_id'])) {
				$this->model_catalog_vacancy->saveOrUpdate($this->request->post, $this->request->get['vacancy_id']);
			} else {
				$this->model_catalog_vacancy->saveOrUpdate($this->request->post);
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
			foreach ($this->request->post['selected'] as $vacancy_id) {
				$this->model_catalog_vacancy->deleteVacancy($vacancy_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order','page'));
			$this->redirect($this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order','page'));

		$this->data['breadcrumbs'] = $this->document->generateBreadCrumbs(
				array(
					array(
						"text" => $this->language->get('heading_title'),
						"href" => $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL')
					)
				));		

		$this->data['insert'] = $this->url->link('catalog/vacancy/saveOrUpdate', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/vacancy/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['vacancies'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$vacancy_total = $this->model_catalog_vacancy->getTotalVacancys();

		$results = $this->model_catalog_vacancy->getVacancys($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/vacancy/saveOrUpdate', 'token=' . $this->session->data['token'] . '&vacancy_id=' . $result['vacancy_id'] . $url, 'SSL')
			);

			$this->data['vacancies'][] = array(
				'vacancy_id' => $result['vacancy_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['vacancy_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}

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
		$this->data['sort_sort_order'] = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order'));

		$pagination = new Pagination();
		$pagination->total = $vacancy_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/vacancy_list.tpl';
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

		$url = $this->url->generateUrlFromArray($this->request->get,array('sort','order','page'));
		
		$this->data['breadcrumbs'] = $this->document->generateBreadCrumbs(
				array(
					array(
						"text" => $this->language->get('heading_title'),
						"href" => $this->url->link($this->modelName, 'token=' . $this->session->data['token'] . $url, 'SSL')
					)
				));

		$urlAdd = (!isset($this->request->get['vacancy_id'])) ? "" : '&vacancy_id=' . $this->request->get['vacancy_id'];
		$this->data['action'] = $this->url->link('catalog/vacancy/saveOrUpdate', 'token=' . $this->session->data['token'] . $urlAdd . $url, 'SSL');

		if (isset($this->request->get['vacancy_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$vacancy_info = $this->model_catalog_vacancy->getVacancy($this->request->get['vacancy_id']);
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
			} elseif (!empty($vacancy_info)) {
				$this->data[$field] = $vacancy_info[$field];
			} else {
				$this->data[$field] = $def_value;
			}
		}

		$this->template = 'catalog/vacancy_form.tpl';
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