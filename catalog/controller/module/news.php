<?php
class ControllerModuleNews extends Controller {
	private $module_type = "news";

	protected function index($setting) {
		static $module = 0;

		$this->load->language('module/' . $this->module_type);
		$this->load->model('fido/news');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_read_more'] = $this->language->get('text_read_more');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_date_available'] = $this->language->get('text_date_available');
		$this->data['show_headline'] = $this->config->get('news_module_headline');

		$this->data['module_type'] = $this->module_type;
		$this->data['setting'] = $setting;

		$this->data['news_count'] = $this->model_fido_news->getTotalNews($this->module_type);
		$this->load->model('tool/image');

		$this->document->addScript('catalog/view/javascript/jquery/bxslider/jquery.bxslider.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/bxslider/jquery.bxslider.css');

		$this->data['news'] = array();

		$russian_months = array(
			"1" => "янаваря",
			"2" => "февраля",
			"3" => "марта",
			"4" => "апреля",
			"5" => "мая",
			"6" => "июня",
			"7" => "июля",
			"8" => "августа",
			"9" => "сентября",
			"10" => "октября",
			"11" => "ноября",
			"12" => "декабря",
		);

		if (!isset($setting['limit']))
			$setting['limit'] = 3;


		$results = $this->model_fido_news->getNewsShorts($setting['limit'], $this->module_type);

		foreach ($results as $result) {
			$date = explode("/",date("d/n/Y", strtotime($result['date_added'])));
			$date[1] = $russian_months[$date[1]];


			$title = implode(array_slice(explode('<br>',wordwrap($result['title'],180,'<br>',false)),0,1));
					if (strlen($result['title']) > 180)
						$title .= " ...";

			$limit = 320;
			$description = implode(array_slice(explode('<br>',wordwrap($result['description'],$limit,'<br>',false)),0,1));
			if (strlen($result['description']) > $limit)
				$description .= " ...";


			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], 218, "*");
			} else {
				$image = false;
			}

			$this->data['news_data'][] = array(
				'title'        => html_entity_decode($title),
				'href'         => $this->url->link('information/news', 'news_id=' . $result['news_id']),
				'date_added'   => $date,
				'date_added_plane'   => date('d.m.Y',strtotime($result['date_added'])),
				'date_available'   => date($this->language->get('date_format_short'), strtotime($result['date_available'])),
				'description'  => strip_tags(html_entity_decode($description)),
				'image' => $image
			);
		}
		$this->id = 'news';

		$this->data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/news.tpl';
		} else {
			$this->template = 'default/template/module/news.tpl';
		}

		$this->render();
	}
}
?>
