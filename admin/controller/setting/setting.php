<?php
class ControllerSettingSetting extends Controller {
	private $error = array();

	public function index() {

$this->data = array_merge( $this->data , $this->language->load('setting/setting')); 

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('config', $this->request->post);

			if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');

			//	$this->model_localisation_currency->updateCurrencies();
			}	

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

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

		if (isset($this->error['owner'])) {
			$this->data['error_owner'] = $this->error['owner'];
		} else {
			$this->data['error_owner'] = '';
		}

		if (isset($this->error['address'])) {
			$this->data['error_address'] = $this->error['address'];
		} else {
			$this->data['error_address'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['customer_group_display'])) {
			$this->data['error_customer_group_display'] = $this->error['customer_group_display'];
		} else {
			$this->data['error_customer_group_display'] = '';
		}

		if (isset($this->error['voucher_min'])) {
			$this->data['error_voucher_min'] = $this->error['voucher_min'];
		} else {
			$this->data['error_voucher_min'] = '';
		}	

		if (isset($this->error['voucher_max'])) {
			$this->data['error_voucher_max'] = $this->error['voucher_max'];
		} else {
			$this->data['error_voucher_max'] = '';
		}

		if (isset($this->error['ftp_host'])) {
			$this->data['error_ftp_host'] = $this->error['ftp_host'];
		} else {
			$this->data['error_ftp_host'] = '';
		}

		if (isset($this->error['ftp_port'])) {
			$this->data['error_ftp_port'] = $this->error['ftp_port'];
		} else {
			$this->data['error_ftp_port'] = '';
		}

		if (isset($this->error['ftp_username'])) {
			$this->data['error_ftp_username'] = $this->error['ftp_username'];
		} else {
			$this->data['error_ftp_username'] = '';
		}

		if (isset($this->error['ftp_password'])) {
			$this->data['error_ftp_password'] = $this->error['ftp_password'];
		} else {
			$this->data['error_ftp_password'] = '';
		}

		if (isset($this->error['image_category'])) {
			$this->data['error_image_category'] = $this->error['image_category'];
		} else {
			$this->data['error_image_category'] = '';
		}

		if (isset($this->error['image_thumb'])) {
			$this->data['error_image_thumb'] = $this->error['image_thumb'];
		} else {
			$this->data['error_image_thumb'] = '';
		}

		if (isset($this->error['image_popup'])) {
			$this->data['error_image_popup'] = $this->error['image_popup'];
		} else {
			$this->data['error_image_popup'] = '';
		}

		if (isset($this->error['image_product'])) {
			$this->data['error_image_product'] = $this->error['image_product'];
		} else {
			$this->data['error_image_product'] = '';
		}

		if (isset($this->error['image_additional'])) {
			$this->data['error_image_additional'] = $this->error['image_additional'];
		} else {
			$this->data['error_image_additional'] = '';
		}	

		if (isset($this->error['image_related'])) {
			$this->data['error_image_related'] = $this->error['image_related'];
		} else {
			$this->data['error_image_related'] = '';
		}

		if (isset($this->error['image_compare'])) {
			$this->data['error_image_compare'] = $this->error['image_compare'];
		} else {
			$this->data['error_image_compare'] = '';
		}

		if (isset($this->error['image_wishlist'])) {
			$this->data['error_image_wishlist'] = $this->error['image_wishlist'];
		} else {
			$this->data['error_image_wishlist'] = '';
		}

		if (isset($this->error['image_cart'])) {
			$this->data['error_image_cart'] = $this->error['image_cart'];
		} else {
			$this->data['error_image_cart'] = '';
		}

		if (isset($this->error['error_filename'])) {
			$this->data['error_error_filename'] = $this->error['error_filename'];
		} else {
			$this->data['error_error_filename'] = '';
		}		

		if (isset($this->error['catalog_limit'])) {
			$this->data['error_catalog_limit'] = $this->error['catalog_limit'];
		} else {
			$this->data['error_catalog_limit'] = '';
		}

		if (isset($this->error['admin_limit'])) {
			$this->data['error_admin_limit'] = $this->error['admin_limit'];
		} else {
			$this->data['error_admin_limit'] = '';
		}

		if (isset($this->error['encryption'])) {
			$this->data['error_encryption'] = $this->error['encryption'];
		} else {
			$this->data['error_encryption'] = '';
		}		

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->data['templates'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}

		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/currency');
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$this->load->model('localisation/length_class');
		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		$this->load->model('localisation/weight_class');
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$this->load->model('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('catalog/information');
		$this->data['informations'] = $this->model_catalog_information->getInformations();

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/stock_status');
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		$this->load->model('localisation/return_status');
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();	

		$config_fields = array(
			"config_name" => false,
			"config_owner" => false,
			"config_address" => false,
			"config_schedule" => false,
			"config_schedule_bottom" => false,
			"config_email" => false,
			"config_telephone_pre" => false,
			"config_telephone" => false,
			"config_telephone2_pre" => false,
			"config_telephone2" => false,
			"config_fax" => false,
			"config_title" => false,
			"config_meta_description" => false,
			"config_layout_id" => false,
			"config_template" => false,
			"config_country_id" => false,
			"config_zone_id" => false,
			"config_language" => false,
			"config_admin_language" => false,
			"config_currency" => false,
			"config_currency_auto" => false,
			"config_length_class_id" => false,
			"config_weight_class_id" => false,
			"config_catalog_limit" => false,
			"config_admin_limit" => false,
			"config_product_count" => false,
			"config_review_status" => false,
			"config_download" => false,
			"config_voucher_min" => false,
			"config_voucher_max" => false,
			"config_tax" => false,
			"config_vat" => false,
			"config_tax_default" => false,
			"config_tax_customer" => false,
			"config_customer_online" => false,
			"config_customer_group_id" => false,
			"config_customer_price" => false,
			"config_account_id" => false,
			"config_cart_weight" => false,
			"config_guest_checkout" => false,
			"config_checkout_id" => false,
			"config_order_edit" => 7,
			"config_customer_group_display" => array(),
			"config_invoice_prefix" => 'INV-' . date('Y') . '-00',
			"config_order_status_id" => false,
			"config_complete_status_id" => false,
			"config_stock_display" => false,
			"config_stock_warning" => false,
			"config_stock_checkout" => false,
			"config_stock_status_id" => false,
			"config_affiliate_id" => false,
			"config_commission" => '5.00',
			"config_return_id" => false,
			"config_return_status_id" => false,
			"config_logo" => false,
			"config_icon" => false,
			"config_image_category_width" => false,
			"config_image_category_height" => false,
			"config_image_thumb_width" => false,
			"config_image_thumb_height" => false,
			"config_image_popup_width" => false,
			"config_image_popup_height" => false,
			"config_image_product_width" => false,
			"config_image_product_height" => false,
			"config_image_additional_width" => false,
			"config_image_additional_height" => false,
			"config_image_related_width" => false,
			"config_image_related_height" => false
			
		);

		foreach ($config_fields as $k => $v) {
			if (isset($this->request->post[$k])) {
				$this->data[$k] = $this->request->post[$k];
			} else {
				$this->data[$k] = ($this->config->get($k)) ? $this->config->get($k) : $v;
			}
		}

		if (isset($this->request->post['config_image_compare_width'])) {
			$this->data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
		} else {
			$this->data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
		}

		if (isset($this->request->post['config_image_compare_height'])) {
			$this->data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
		} else {
			$this->data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
		}	

		if (isset($this->request->post['config_image_wishlist_width'])) {
			$this->data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
		} else {
			$this->data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
		}

		if (isset($this->request->post['config_image_wishlist_height'])) {
			$this->data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
		} else {
			$this->data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
		}	

		if (isset($this->request->post['config_image_cart_width'])) {
			$this->data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
		} else {
			$this->data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
		}

		if (isset($this->request->post['config_image_cart_height'])) {
			$this->data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
		} else {
			$this->data['config_image_cart_height'] = $this->config->get('config_image_cart_height');
		}		

		if (isset($this->request->post['config_ftp_host'])) {
			$this->data['config_ftp_host'] = $this->request->post['config_ftp_host'];
		} elseif ($this->config->get('config_ftp_host')) {
			$this->data['config_ftp_host'] = $this->config->get('config_ftp_host');		
		} else {
			$this->data['config_ftp_host'] = str_replace('www.', '', $this->request->server['HTTP_HOST']);
		}

		if (isset($this->request->post['config_ftp_port'])) {
			$this->data['config_ftp_port'] = $this->request->post['config_ftp_port'];
		} elseif ($this->config->get('config_ftp_port')) {
			$this->data['config_ftp_port'] = $this->config->get('config_ftp_port');
		} else {
			$this->data['config_ftp_port'] = 21;
		}

		if (isset($this->request->post['config_ftp_username'])) {
			$this->data['config_ftp_username'] = $this->request->post['config_ftp_username'];
		} else {
			$this->data['config_ftp_username'] = $this->config->get('config_ftp_username');
		}

		if (isset($this->request->post['config_ftp_password'])) {
			$this->data['config_ftp_password'] = $this->request->post['config_ftp_password'];
		} else {
			$this->data['config_ftp_password'] = $this->config->get('config_ftp_password');
		}

		if (isset($this->request->post['config_ftp_root'])) {
			$this->data['config_ftp_root'] = $this->request->post['config_ftp_root'];
		} else {
			$this->data['config_ftp_root'] = $this->config->get('config_ftp_root');
		}

		if (isset($this->request->post['config_ftp_status'])) {
			$this->data['config_ftp_status'] = $this->request->post['config_ftp_status'];
		} else {
			$this->data['config_ftp_status'] = $this->config->get('config_ftp_status');
		}

		if (isset($this->request->post['config_mail_protocol'])) {
			$this->data['config_mail_protocol'] = $this->request->post['config_mail_protocol'];
		} else {
			$this->data['config_mail_protocol'] = $this->config->get('config_mail_protocol');
		}

		if (isset($this->request->post['config_mail_parameter'])) {
			$this->data['config_mail_parameter'] = $this->request->post['config_mail_parameter'];
		} else {
			$this->data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
		}

		if (isset($this->request->post['config_smtp_host'])) {
			$this->data['config_smtp_host'] = $this->request->post['config_smtp_host'];
		} else {
			$this->data['config_smtp_host'] = $this->config->get('config_smtp_host');
		}		

		if (isset($this->request->post['config_smtp_username'])) {
			$this->data['config_smtp_username'] = $this->request->post['config_smtp_username'];
		} else {
			$this->data['config_smtp_username'] = $this->config->get('config_smtp_username');
		}	

		if (isset($this->request->post['config_smtp_password'])) {
			$this->data['config_smtp_password'] = $this->request->post['config_smtp_password'];
		} else {
			$this->data['config_smtp_password'] = $this->config->get('config_smtp_password');
		}	

		if (isset($this->request->post['config_smtp_port'])) {
			$this->data['config_smtp_port'] = $this->request->post['config_smtp_port'];
		} elseif ($this->config->get('config_smtp_port')) {
			$this->data['config_smtp_port'] = $this->config->get('config_smtp_port');
		} else {
			$this->data['config_smtp_port'] = 25;
		}	

		if (isset($this->request->post['config_smtp_timeout'])) {
			$this->data['config_smtp_timeout'] = $this->request->post['config_smtp_timeout'];
		} elseif ($this->config->get('config_smtp_timeout')) {
			$this->data['config_smtp_timeout'] = $this->config->get('config_smtp_timeout');
		} else {
			$this->data['config_smtp_timeout'] = 5;	
		}	

		if (isset($this->request->post['config_alert_mail'])) {
			$this->data['config_alert_mail'] = $this->request->post['config_alert_mail'];
		} else {
			$this->data['config_alert_mail'] = $this->config->get('config_alert_mail');
		}

		if (isset($this->request->post['config_account_mail'])) {
			$this->data['config_account_mail'] = $this->request->post['config_account_mail'];
		} else {
			$this->data['config_account_mail'] = $this->config->get('config_account_mail');
		}

		if (isset($this->request->post['config_alert_emails'])) {
			$this->data['config_alert_emails'] = $this->request->post['config_alert_emails'];
		} else {
			$this->data['config_alert_emails'] = $this->config->get('config_alert_emails');
		}

		if (isset($this->request->post['config_fraud_detection'])) {
			$this->data['config_fraud_detection'] = $this->request->post['config_fraud_detection']; 
		} else {
			$this->data['config_fraud_detection'] = $this->config->get('config_fraud_detection');
		}	

		if (isset($this->request->post['config_fraud_key'])) {
			$this->data['config_fraud_key'] = $this->request->post['config_fraud_key']; 
		} else {
			$this->data['config_fraud_key'] = $this->config->get('config_fraud_key');
		}		

		if (isset($this->request->post['config_fraud_score'])) {
			$this->data['config_fraud_score'] = $this->request->post['config_fraud_score']; 
		} else {
			$this->data['config_fraud_score'] = $this->config->get('config_fraud_score');
		}	

		if (isset($this->request->post['config_fraud_status_id'])) {
			$this->data['config_fraud_status_id'] = $this->request->post['config_fraud_status_id']; 
		} else {
			$this->data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');
		}		

		if (isset($this->request->post['config_secure'])) {
			$this->data['config_secure'] = $this->request->post['config_secure'];
		} else {
			$this->data['config_secure'] = $this->config->get('config_secure');
		}

		if (isset($this->request->post['config_shared'])) {
			$this->data['config_shared'] = $this->request->post['config_shared'];
		} else {
			$this->data['config_shared'] = $this->config->get('config_shared');
		}

		if (isset($this->request->post['config_robots'])) {
			$this->data['config_robots'] = $this->request->post['config_robots'];
		} else {
			$this->data['config_robots'] = $this->config->get('config_robots');
		}

		if (isset($this->request->post['config_seo_url'])) {
			$this->data['config_seo_url'] = $this->request->post['config_seo_url'];
		} else {
			$this->data['config_seo_url'] = $this->config->get('config_seo_url');
		}

		if (isset($this->request->post['config_file_extension_allowed'])) {
			$this->data['config_file_extension_allowed'] = $this->request->post['config_file_extension_allowed'];
		} else {
			$this->data['config_file_extension_allowed'] = $this->config->get('config_file_extension_allowed');
		}

		if (isset($this->request->post['config_file_mime_allowed'])) {
			$this->data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
		} else {
			$this->data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
		}		

		if (isset($this->request->post['config_maintenance'])) {
			$this->data['config_maintenance'] = $this->request->post['config_maintenance'];
		} else {
			$this->data['config_maintenance'] = $this->config->get('config_maintenance');
		}

		if (isset($this->request->post['config_password'])) {
			$this->data['config_password'] = $this->request->post['config_password'];
		} else {
			$this->data['config_password'] = $this->config->get('config_password');
		}

		if (isset($this->request->post['config_encryption'])) {
			$this->data['config_encryption'] = $this->request->post['config_encryption'];
		} else {
			$this->data['config_encryption'] = $this->config->get('config_encryption');
		}

		if (isset($this->request->post['config_compression'])) {
			$this->data['config_compression'] = $this->request->post['config_compression']; 
		} else {
			$this->data['config_compression'] = $this->config->get('config_compression');
		}

		if (isset($this->request->post['config_error_display'])) {
			$this->data['config_error_display'] = $this->request->post['config_error_display']; 
		} else {
			$this->data['config_error_display'] = $this->config->get('config_error_display');
		}

		if (isset($this->request->post['config_error_log'])) {
			$this->data['config_error_log'] = $this->request->post['config_error_log']; 
		} else {
			$this->data['config_error_log'] = $this->config->get('config_error_log');
		}

		if (isset($this->request->post['config_error_filename'])) {
			$this->data['config_error_filename'] = $this->request->post['config_error_filename']; 
		} else {
			$this->data['config_error_filename'] = $this->config->get('config_error_filename');
		}

		if (isset($this->request->post['config_google_analytics'])) {
			$this->data['config_google_analytics'] = $this->request->post['config_google_analytics']; 
		} else {
			$this->data['config_google_analytics'] = $this->config->get('config_google_analytics');
		}

		$this->load->model('tool/image');

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo')) && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon')) && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
		} else {
			$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);		

		$this->template = 'setting/setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}	

		if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
			$this->error['address'] = $this->language->get('error_address');
		}

		if ((utf8_strlen($this->request->post['config_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['config_email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if (!$this->request->post['config_title']) {
			$this->error['title'] = $this->language->get('error_title');
		}	

		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
		}	

		if (!$this->request->post['config_voucher_min']) {
			$this->error['voucher_min'] = $this->language->get('error_voucher_min');
		}	

		if (!$this->request->post['config_voucher_max']) {
			$this->error['voucher_max'] = $this->language->get('error_voucher_max');
		}	

		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		} 

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}	

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}	

		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$this->error['image_compare'] = $this->language->get('error_image_compare');
		}

		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
		}			

		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}

		if ($this->request->post['config_ftp_status']) {
			if (!$this->request->post['config_ftp_host']) {
				$this->error['ftp_host'] = $this->language->get('error_ftp_host');
			}

			if (!$this->request->post['config_ftp_port']) {
				$this->error['ftp_port'] = $this->language->get('error_ftp_port');
			}

			if (!$this->request->post['config_ftp_username']) {
				$this->error['ftp_username'] = $this->language->get('error_ftp_username');
			}	

			if (!$this->request->post['config_ftp_password']) {
				$this->error['ftp_password'] = $this->language->get('error_ftp_password');
			}											
		}

		if (!$this->request->post['config_error_filename']) {
			$this->error['error_filename'] = $this->language->get('error_error_filename');
		}

		if (!$this->request->post['config_catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
		}

		if (!$this->request->post['config_admin_limit']) {
			$this->error['admin_limit'] = $this->language->get('error_limit');
		}

		if ((utf8_strlen($this->request->post['config_encryption']) < 3) || (utf8_strlen($this->request->post['config_encryption']) > 32)) {
			$this->error['encryption'] = $this->language->get('error_encryption');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function template() {
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}

		if (file_exists(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
			$image = $server . 'image/templates/' . basename($this->request->get['template']) . '.png';
		} else {
			$image = $server . 'image/no_image.jpg';
		}

		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}		

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>