<?php
class ControllerInformationNews extends Controller {
	public function index() {
    	$this->data = array_merge( $this->data , $this->language->load('information/news'));
		$this->load->model('fido/news');

		$this->data['grove_href'] = $this->url->link('product/category', 'path=20', 'SSL');

		if (isset($this->request->get['news_id'])) {
			$news_id = $this->request->get['news_id'];
		} else {
			$news_id = 0;
		}
		
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);		
		
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/news'),    
        		'separator' => $this->language->get('text_separator')
      		);		
					

		$news_info = $this->model_fido_news->getNewsStory($news_id);

		$this->load->model('tool/image');

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
		
					
		if ($news_info) {
	  		$this->document->setTitle($news_info['title']);

			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('information/news', 'news_id=' . $this->request->get['news_id']),
				'text'      => $news_info['title'],
				'separator' => $this->language->get('text_separator')
			);

     		$this->data['news_info'] = $news_info;
		
			$this->data['neighbors_news'] = $this->model_fido_news->getNewsNeighbors($news_id);

     		$this->data['heading_title'] = $news_info['title'];

			$this->data['date_added'] = $news_info['date_added'];

			$this->data['news_id'] = $news_id;
		
			$this->document->setDescription($news_info['meta_description']);

			$this->data['description'] = html_entity_decode($news_info['description']);

			$this->load->model('tool/image');

			if ($news_info['image']) {
				$this->data['image'] = $this->model_tool_image->resize($news_info['image'], 350, "*");
			} else {
				$this->data['image'] = false;
			}			

			$this->data['min_height'] = $this->config->get('news_thumb_height');

			$this->data['thumb'] = $this->model_tool_image->resize($news_info['image'], 200, '*');
			
			$this->data['popup'] = $this->model_tool_image->resize($news_info['image'], 600, 600);

			$this->data['button_news'] = $this->language->get('button_news');

			$this->data['date'] = explode("/",date("d/n/Y", strtotime($news_info['date_added'])));
			$this->data['date'][1] = $russian_months[$this->data['date'][1]];

			$this->data['news'] = $this->url->link('information/news');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/news.tpl';
			} else {
				$this->template = 'default/template/information/news.tpl';
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
				
			if (isset($this->request->get['page']))
				$page = $this->request->get['page'];
			else 
				$page = 0;
			$limit = 7;
			
	  		$news_data = $this->model_fido_news->getNews($page,$limit,'news');
	  		
	  		$news_total = $this->model_fido_news->getTotalNews('news');

			$pagination = new Pagination();
			$pagination->total = $news_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('information/news', '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			$first_news = true;
	  		if ($news_data) {
				foreach ($news_data as $result) {
					$date = explode("/",date("d/n/Y", strtotime($result['date_added'])));
					$date[1] = $russian_months[$date[1]];
			
					$title = implode(array_slice(explode('<br>',wordwrap($result['title'],340,'<br>',false)),0,1));
					if (strlen($result['title']) > 340)
						$title .= " ...";
					
					
					if ($first_news) {
						$descr_limit = 1200;
						$first_news = false;
					} else
						$descr_limit = 1200;

					$descr = (!empty($result['short_descr'])) ? $result['short_descr'] : $result['description'];
					
					$description = implode(array_slice(explode('<br>',wordwrap($descr,$descr_limit,'<br>',false)),0,1));
					if (strlen($description) > $descr_limit)
						$description .= " ...";

					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], 250, "*");
					} else {
						$image = false;
					}
					
					$this->data['news_data'][] = array(
						'title'        => $title,
						'description'  => strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8')),
						'href'         => $this->url->link('information/news', 'news_id=' . $result['news_id']),
						'date_added'   => $result['date_added'],
						'image'        => $image,
						'date_added_formated'   => $date,
						'date_available' => date($this->language->get('date_format_short'), strtotime($result['date_available']))
					);
				}
//p($this->data['news_data']);
				$this->document->setTitle($this->language->get('heading_title'));

				$this->document->breadcrumbs[] = array(
					'href'      => $this->url->link('information/news'),
					'text'      => $this->language->get('heading_title'),
					'separator' => $this->language->get('text_separator')
				);

				$this->data['heading_title'] = $this->language->get('heading_title');

				$this->data['text_read_more'] = $this->language->get('text_read_more');
				$this->data['text_date_added'] = $this->language->get('text_date_added');
				$this->data['text_date_available'] = $this->language->get('text_date_available');
				$this->data['button_continue'] = $this->language->get('button_continue');

				$this->data['continue'] = $this->url->link('common/home');

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/information/news.tpl';
				} else {
					$this->template = 'default/template/information/news.tpl';
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
		  		$this->document->setTitle($this->language->get('text_error'));

	     		$this->document->breadcrumbs[] = array(
	        		'href'      => $this->url->link('information/news'),
	        		'text'      => $this->language->get('text_error'),
	        		'separator' => $this->language->get('text_separator')
	     		);

				$this->data['heading_title'] = $this->language->get('text_error');

				$this->data['text_error'] = $this->language->get('text_error');

				$this->data['button_continue'] = $this->language->get('button_continue');

				$this->data['continue'] = $this->url->link('common/home');

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
}
?>
