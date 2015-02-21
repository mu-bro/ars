<?php 

class ControllerInformationPodbor extends Controller {

	public function index() {



		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/animate.min.css');

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



	public function results() {

		$results_json = file_get_contents(html_entity_decode($_GET['url']));

		if (!empty($results_json)) {

			$results = json_decode($results_json);

			$filter = array(

				"attribute_value" => array()

			);

			$dimentions = explode("х",$results[0]->Dimensions);

			$filter['attribute_value'][12] = array($dimentions[0] - 3, $dimentions[0] - 2, $dimentions[0] - 1, $dimentions[0],
				$dimentions[0] + 1, $dimentions[0] + 2, $dimentions[0] + 3);
			$filter['attribute_value'][13] = array($dimentions[1] - 2, $dimentions[1] - 1, $dimentions[1], $dimentions[1] + 1, $dimentions[1] + 2);
			$filter['attribute_value'][14] = array($dimentions[2] - 2, $dimentions[2] - 1, $dimentions[2], $dimentions[2] + 1, $dimentions[2] + 2);

			$filter['attribute_value'][3]['show'] = 1;

			$filter['attribute_value'][3]['min'] = 999999;

			$filter['attribute_value'][3]['max'] = 0;

			$this->data = array_merge( $this->data , $this->language->load('product/category'));



			$polyarity_info = explode("/",str_replace(" ","",$results[0]->Polyarity));

			$filter['attribute_value'][1][] = ($polyarity_info[0] == 0) ? $this->language->get("text_capacity_0") : $this->language->get("text_capacity_1");

			if ($polyarity_info[1] == "small") {

				$filter['attribute_value'][15][] = $this->language->get("text_clamp_slim");

			}

			foreach ($results as $result) {

				if ($result->Capacity < $filter['attribute_value'][3]['min']) {

					$filter['attribute_value'][3]['min'] = $result->Capacity;

				}

				if ($result->Capacity > $filter['attribute_value'][3]['max']) {

					$filter['attribute_value'][3]['max'] = $result->Capacity + 3;

				}

			}



			$this->load->model('catalog/product');

			$this->load->model('tool/image');

			$results = $this->model_catalog_product->getProductsByFilter($filter);



			$this->data['products'] = $this->model_catalog_product->prepareProductList($results);



			$this->data = array_merge( $this->data , $this->language->load('product/category'));

			$this->template = $this->config->get('config_template') . '/template/product/product_list.tpl';

			$json['results'] = $this->render();

			$this->response->setOutput(json_encode($json));

		}



	}



}

?>