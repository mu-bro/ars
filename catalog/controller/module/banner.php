<?php  
class ControllerModuleBanner extends Controller {
	protected function index($setting) {
		static $module = 0;

		$this->data = array_merge( $this->data , $this->language->load('module/currency'));
		
		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addScript('catalog/view/javascript/camera/scripts/jquery.easing.1.3.js');
		$this->document->addScript('catalog/view/javascript/camera/scripts/camera.js');
		$this->document->addStyle('catalog/view/javascript/camera/css/camera.css');
				
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {

			if (!empty($setting['width'])) {
				$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
			} else {
				$image = HTTP_SERVER . "image/". $result['image'];
			}
				
				$this->data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $image
				);
			}
		}
		
		$this->data['module'] = $module++;
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/banner.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/banner.tpl';
		} else {
			$this->template = 'default/template/module/banner.tpl';
		}
		
		$this->render();
	}
}
?>