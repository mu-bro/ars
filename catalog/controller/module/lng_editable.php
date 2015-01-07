<?php 
class ControllerModuleLngEditable extends Controller {
	public function index() {
		if( !empty( $this->session->data["lng_editable"] ) && !empty( $this->session->data["token"] ) ) {
			$this->data['title'] = $this->document->getTitle();
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/lngedit.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/lngedit.tpl';
			} else {
				$this->template = 'default/template/module/lngedit.tpl';
			}

			$this->response->setOutput($this->render());
		}		
		
	}
}
?>