<?php 
class ControllerPaymentPPProIframe extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/pp_pro_iframe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro_iframe', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['sig'])) {
			$this->data['error_sig'] = $this->error['sig'];
		} else {
			$this->data['error_sig'] = '';
		}

		if (isset($this->error['user'])) {
			$this->data['error_user'] = $this->error['user'];
		} else {
			$this->data['error_user'] = '';
		}

		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_pro_iframe_sig'])) {
			$this->data['pp_pro_iframe_sig'] = $this->request->post['pp_pro_iframe_sig'];
		} else {
			$this->data['pp_pro_iframe_sig'] = $this->config->get('pp_pro_iframe_sig');
		}

		if (isset($this->request->post['pp_pro_iframe_user'])) {
			$this->data['pp_pro_iframe_user'] = $this->request->post['pp_pro_iframe_user'];
		} else {
			$this->data['pp_pro_iframe_user'] = $this->config->get('pp_pro_iframe_user');
		}

		if (isset($this->request->post['pp_pro_iframe_password'])) {
			$this->data['pp_pro_iframe_password'] = $this->request->post['pp_pro_iframe_password'];
		} else {
			$this->data['pp_pro_iframe_password'] = $this->config->get('pp_pro_iframe_password');
		}

		if (isset($this->request->post['pp_pro_iframe_transaction_method'])) {
			$this->data['pp_pro_iframe_transaction_method'] = $this->request->post['pp_pro_iframe_transaction_method'];
		} else {
			$this->data['pp_pro_iframe_transaction_method'] = $this->config->get('pp_pro_iframe_transaction_method');
		}

		if (isset($this->request->post['pp_pro_iframe_test'])) {
			$this->data['pp_pro_iframe_test'] = $this->request->post['pp_pro_iframe_test'];
		} else {
			$this->data['pp_pro_iframe_test'] = $this->config->get('pp_pro_iframe_test');
		}

		if (isset($this->request->post['pp_pro_iframe_total'])) {
			$this->data['pp_pro_iframe_total'] = $this->request->post['pp_pro_iframe_total'];
		} else {
			$this->data['pp_pro_iframe_total'] = $this->config->get('pp_pro_iframe_total'); 
		}

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_pro_iframe_canceled_reversal_status_id'])) {
			$this->data['pp_pro_iframe_canceled_reversal_status_id'] = $this->request->post['pp_pro_iframe_canceled_reversal_status_id'];
		} else {
			$this->data['pp_pro_iframe_canceled_reversal_status_id'] = $this->config->get('pp_pro_iframe_canceled_reversal_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_completed_status_id'])) {
			$this->data['pp_pro_iframe_completed_status_id'] = $this->request->post['pp_pro_iframe_completed_status_id'];
		} else {
			$this->data['pp_pro_iframe_completed_status_id'] = $this->config->get('pp_pro_iframe_completed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_denied_status_id'])) {
			$this->data['pp_pro_iframe_denied_status_id'] = $this->request->post['pp_pro_iframe_denied_status_id'];
		} else {
			$this->data['pp_pro_iframe_denied_status_id'] = $this->config->get('pp_pro_iframe_denied_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_expired_status_id'])) {
			$this->data['pp_pro_iframe_expired_status_id'] = $this->request->post['pp_pro_iframe_expired_status_id'];
		} else {
			$this->data['pp_pro_iframe_expired_status_id'] = $this->config->get('pp_pro_iframe_expired_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_failed_status_id'])) {
			$this->data['pp_pro_iframe_failed_status_id'] = $this->request->post['pp_pro_iframe_failed_status_id'];
		} else {
			$this->data['pp_pro_iframe_failed_status_id'] = $this->config->get('pp_pro_iframe_failed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_pending_status_id'])) {
			$this->data['pp_pro_iframe_pending_status_id'] = $this->request->post['pp_pro_iframe_pending_status_id'];
		} else {
			$this->data['pp_pro_iframe_pending_status_id'] = $this->config->get('pp_pro_iframe_pending_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_processed_status_id'])) {
			$this->data['pp_pro_iframe_processed_status_id'] = $this->request->post['pp_pro_iframe_processed_status_id'];
		} else {
			$this->data['pp_pro_iframe_processed_status_id'] = $this->config->get('pp_pro_iframe_processed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_refunded_status_id'])) {
			$this->data['pp_pro_iframe_refunded_status_id'] = $this->request->post['pp_pro_iframe_refunded_status_id'];
		} else {
			$this->data['pp_pro_iframe_refunded_status_id'] = $this->config->get('pp_pro_iframe_refunded_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_reversed_status_id'])) {
			$this->data['pp_pro_iframe_reversed_status_id'] = $this->request->post['pp_pro_iframe_reversed_status_id'];
		} else {
			$this->data['pp_pro_iframe_reversed_status_id'] = $this->config->get('pp_pro_iframe_reversed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_voided_status_id'])) {
			$this->data['pp_pro_iframe_voided_status_id'] = $this->request->post['pp_pro_iframe_voided_status_id'];
		} else {
			$this->data['pp_pro_iframe_voided_status_id'] = $this->config->get('pp_pro_iframe_voided_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_geo_zone_id'])) {
			$this->data['pp_pro_iframe_geo_zone_id'] = $this->request->post['pp_pro_iframe_geo_zone_id'];
		} else {
			$this->data['pp_pro_iframe_geo_zone_id'] = $this->config->get('pp_pro_iframe_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_pro_iframe_status'])) {
			$this->data['pp_pro_iframe_status'] = $this->request->post['pp_pro_iframe_status'];
		} else {
			$this->data['pp_pro_iframe_status'] = $this->config->get('pp_pro_iframe_status');
		}

		if (isset($this->request->post['pp_pro_iframe_sort_order'])) {
			$this->data['pp_pro_iframe_sort_order'] = $this->request->post['pp_pro_iframe_sort_order'];
		} else {
			$this->data['pp_pro_iframe_sort_order'] = $this->config->get('pp_pro_iframe_sort_order');
		}

		if (isset($this->request->post['pp_pro_iframe_checkout_method'])) {
			$this->data['pp_pro_iframe_checkout_method'] = $this->request->post['pp_pro_iframe_checkout_method'];
		} else {
			$this->data['pp_pro_iframe_checkout_method'] = $this->config->get('pp_pro_iframe_checkout_method');
		}

		if (isset($this->request->post['pp_pro_iframe_debug'])) {
			$this->data['pp_pro_iframe_debug'] = $this->request->post['pp_pro_iframe_debug'];
		} else {
			$this->data['pp_pro_iframe_debug'] = $this->config->get('pp_pro_iframe_debug');
		}

		$this->data['ipn_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_pro_iframe/notify';

		$this->template = 'payment/pp_pro_iframe.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->load->model('payment/pp_pro_iframe');
		$this->model_payment_pp_pro_iframe->install();
	}

	public function uninstall() {
		$this->load->model('payment/pp_pro_iframe');
		$this->model_payment_pp_pro_iframe->uninstall();
	}

	public function refund() {
		$this->load->language('payment/pp_pro_iframe');
		$this->load->model('payment/pp_pro_iframe');

		$this->document->setTitle($this->language->get('text_refund'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_refund'),
			'href'      => $this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		//button actions
		$this->data['action'] = $this->url->link('payment/pp_pro_iframe/doRefund', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['order_id'])) {
			$this->data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] , 'SSL');
		} else {
			$this->data['cancel'] = '';
		}

		$this->data['transaction_id'] = $this->request->get['transaction_id'];

		$pp_transaction = $this->model_payment_pp_pro_iframe->getTransaction($this->request->get['transaction_id']);

		$this->data['amount_original'] = $pp_transaction['AMT'];
		$this->data['currency_code'] = $pp_transaction['CURRENCYCODE'];

		$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedTransaction($this->request->get['transaction_id']), 2);

		if($refunded != 0.00) {
			$this->data['refund_available'] = number_format($this->data['amount_original'] + $refunded, 2);
			$this->data['attention'] = $this->language->get('text_current_refunds').': '.$this->data['refund_available'];
		} else {
			$this->data['refund_available'] = '';
			$this->data['attention'] = '';
		}

		$this->data['token'] = $this->session->data['token'];

		if(isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$this->template = 'payment/pp_pro_iframe_refund.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function doRefund() {
		/**
		 * used to issue a refund for a captured payment
		 *
		 * refund can be full or partial
		 */
		if (isset($this->request->post['transaction_id']) && isset($this->request->post['refund_full'])) {

			$this->load->model('payment/pp_pro_iframe');
			$this->load->language('payment/pp_pro_iframe');

			if ($this->request->post['refund_full'] == 0 && $this->request->post['amount'] == 0) {
				$this->session->data['error'] = $this->language->get('error_partial_amt');
			} else {
				$order_id = $this->model_payment_pp_pro_iframe->getOrderId($this->request->post['transaction_id']);
				$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($order_id);

				if ($paypal_order) {
					$call_data = array();
					$call_data['METHOD'] = 'RefundTransaction';
					$call_data['TRANSACTIONID'] = $this->request->post['transaction_id'];
					$call_data['NOTE'] = urlencode($this->request->post['refund_message']);
					$call_data['MSGSUBID'] = uniqid(mt_rand(), true);

					$current_transaction = $this->model_payment_pp_pro_iframe->getLocalTransaction($this->request->post['transaction_id']);

					if ($this->request->post['refund_full'] == 1) {
						$call_data['REFUNDTYPE'] = 'Full';
					} else {
						$call_data['REFUNDTYPE'] = 'Partial';
						$call_data['AMT'] = number_format($this->request->post['amount'], 2);
						$call_data['CURRENCYCODE'] = $this->request->post['currency_code'];
					}

					$result = $this->model_payment_pp_pro_iframe->call($call_data);

					$transaction = array(
						'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
						'transaction_id' => '',
						'parent_transaction_id' => $this->request->post['transaction_id'],
						'note' => $this->request->post['refund_message'],
						'msgsubid' => $call_data['MSGSUBID'],
						'receipt_id' => '',
						'payment_type' => '',
						'payment_status' => 'Refunded',
						'transaction_entity' => 'payment',
						'pending_reason' => '',
						'amount' => '-' . (isset($call_data['AMT']) ? $call_data['AMT'] : $current_transaction['amount']),
						'debug_data' => json_encode($result),
					);

					if ($result == false) {
						$transaction['payment_status'] = 'Failed';
						$this->model_payment_pp_pro_iframe->addTransaction($transaction, $call_data);
						$this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $paypal_order['order_id'], 'SSL'));
					} else if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {

						$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
						$transaction['payment_type'] = $result['REFUNDSTATUS'];
						$transaction['pending_reason'] = $result['PENDINGREASON'];
						$transaction['amount'] = '-' . $result['GROSSREFUNDAMT'];

						$this->model_payment_pp_pro_iframe->addTransaction($transaction);

						if ($result['TOTALREFUNDEDAMOUNT'] == $this->request->post['amount_original']) {
							$this->model_payment_pp_pro_iframe->updateRefundTransaction($this->request->post['transaction_id'], 'Refunded');
						} else {
							$this->model_payment_pp_pro_iframe->updateRefundTransaction($this->request->post['transaction_id'], 'Partially-Refunded');
						}

						//redirect back to the order
						$this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $paypal_order['order_id'], 'SSL'));
					} else {
						$this->model_payment_pp_pro_iframe->log(json_encode($result));
						$this->session->data['error'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : 'There was an error') . (isset($result['L_LONGMESSAGE0']) ? '<br />' . $result['L_LONGMESSAGE0'] : '');
						$this->redirect($this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
					}
				} else {
					$this->session->data['error'] = $this->language->get('error_data_missing');
					$this->redirect($this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
				}
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_data');
			$this->redirect($this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
		}
	}

	public function reauthorise() {
		$this->load->language('payment/pp_pro_iframe');
		$this->load->model('payment/pp_pro_iframe');

		$json = array();

		if (isset($this->request->post['order_id'])) {
			$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			$call_data = array();
			$call_data['METHOD'] = 'DoReauthorization';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];
			$call_data['AMT'] = number_format($paypal_order['total'], 2);
			$call_data['CURRENCYCODE'] = $paypal_order['currency_code'];

			$result = $this->model_payment_pp_pro_iframe->call($call_data);

			if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$this->model_payment_pp_pro_iframe->updateAuthorizationId($paypal_order['paypal_iframe_order_id'], $result['AUTHORIZATIONID']);

				$transaction = array(
					'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
					'transaction_id' => '',
					'parent_transaction_id' => $paypal_order['authorization_id'],
					'note' => '',
					'msgsubid' => '',
					'receipt_id' => '',
					'payment_type' => 'instant',
					'payment_status' => $result['PAYMENTSTATUS'],
					'transaction_entity' => 'auth',
					'pending_reason' => $result['PENDINGREASON'],
					'amount' => '-' . '',
					'debug_data' => json_encode($result),
				);

				$this->model_payment_pp_pro_iframe->addTransaction($transaction);

				$transaction['created'] = date("Y-m-d H:i:s");

				$json['data'] = $transaction;
				$json['error'] = false;
				$json['msg'] = 'Ok';
			} else {
				$json['error'] = true;
				$json['msg'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : $this->language->get('error_general'));
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_missing_data');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function viewTransaction() {
		$this->load->model('payment/pp_pro_iframe');
		$this->load->language('payment/pp_pro_iframe');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_transaction'),
			'href'      => $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->get['transaction_id'], 'SSL'),
			'separator' => ' :: '
		);

		$transaction = $this->model_payment_pp_pro_iframe->getTransaction($this->request->get['transaction_id']);
		$transaction = array_map('urldecode', $transaction);

		$this->data['transaction'] = $transaction;
		$this->data['view_link'] = $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->document->setTitle($this->language->get('text_transaction'));

		if (isset($this->request->get['order_id'])) {
			$this->data['back'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL');
		} else {
			$this->data['back'] = '';
		}

		$this->template = 'payment/pp_pro_iframe_transaction.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function capture() {
		$this->load->language('payment/pp_pro_iframe');
		/**
		 * used to capture authorised payments
		 *
		 * capture can be full or partial amounts
		 */
		if(isset($this->request->post['order_id']) && $this->request->post['amount'] > 0 && isset($this->request->post['order_id']) && isset($this->request->post['complete'])) {

			$this->load->model('payment/pp_pro_iframe');

			$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			if($this->request->post['complete'] == 1) {
				$complete = 'Complete';
			} else {
				$complete = 'NotComplete';
			}

			$call_data = array();
			$call_data['METHOD'] = 'DoCapture';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];
			$call_data['AMT'] = number_format($this->request->post['amount'], 2);
			$call_data['CURRENCYCODE'] = $paypal_order['currency_code'];
			$call_data['COMPLETETYPE'] = $complete;
			$call_data['MSGSUBID'] = uniqid(mt_rand(), true);

			$result = $this->model_payment_pp_pro_iframe->call($call_data);

			$transaction = array(
				'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
				'transaction_id' => '',
				'parent_transaction_id' => $paypal_order['authorization_id'],
				'note' => '',
				'msgsubid' => $call_data['MSGSUBID'],
				'receipt_id' => '',
				'payment_type' => '',
				'payment_status' => '',
				'pending_reason' => '',
				'transaction_entity' => 'payment',
				'amount' => '',
				'debug_data' => json_encode($result),
			);

			if ($result == false) {
				$transaction['amount'] = number_format($this->request->post['amount'], 2);
				$paypal_iframe_order_transaction_id = $this->model_payment_pp_pro_iframe->addTransaction($transaction, $call_data);

				$json['error'] = true;

				$json['failed_transaction']['paypal_iframe_order_transaction_id'] = $paypal_iframe_order_transaction_id;
				$json['failed_transaction']['amount'] = $transaction['amount'];
				$json['failed_transaction']['created'] = date("Y-m-d H:i:s");

				$json['msg'] = $this->language->get('error_timeout');
			} else if(isset($result['ACK']) && $result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$transaction['transaction_id'] = $result['TRANSACTIONID'];
				$transaction['payment_type'] = $result['PAYMENTTYPE'];
				$transaction['payment_status'] = $result['PAYMENTSTATUS'];
				$transaction['pending_reason'] = (isset($result['PENDINGREASON']) ? $result['PENDINGREASON'] : '');
				$transaction['amount'] = $result['AMT'];

				$this->model_payment_pp_pro_iframe->addTransaction($transaction);

				unset($transaction['debug_data']);
				$transaction['created'] = date("Y-m-d H:i:s");

				$captured = number_format($this->model_payment_pp_pro_iframe->totalCaptured($paypal_order['paypal_iframe_order_id']), 2);
				$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedOrder($paypal_order['paypal_iframe_order_id']), 2);

				$transaction['captured'] = $captured;
				$transaction['refunded'] = $refunded;
				$transaction['remaining'] = number_format($paypal_order['total'] - $captured, 2);

				$transaction['status'] = 0;
				if($transaction['remaining'] == 0.00) {
					$transaction['status'] = 1;
					$this->model_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);
				}

				$transaction['void'] = '';

				if($this->request->post['complete'] == 1 && $transaction['remaining'] > 0) {
					$transaction['void'] = array(
						'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
						'transaction_id' => '',
						'parent_transaction_id' => $paypal_order['authorization_id'],
						'note' => '',
						'msgsubid' => '',
						'receipt_id' => '',
						'payment_type' => '',
						'payment_status' => 'Void',
						'pending_reason' => '',
						'amount' => '',
						'debug_data' => 'Voided after capture',
						'transaction_entity' => 'auth',
					);

					$this->model_payment_pp_pro_iframe->addTransaction($transaction['void']);
					$this->model_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);
					$transaction['void']['created'] = date("Y-m-d H:i:s");
					$transaction['status'] = 1;
				}

				$json['data'] = $transaction;
				$json['error'] = false;
				$json['msg'] = 'Ok';
			} else {
				$json['error'] = true;
				$json['msg'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : 'There was an error');
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->setOutput(json_encode($json));
	}

	public function void() {
		$this->load->language('payment/pp_pro_iframe');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('payment/pp_pro_iframe');

			$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			$call_data = array();
			$call_data['METHOD'] = 'DoVoid';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];

			$result = $this->model_payment_pp_pro_iframe->call($call_data);

			if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$transaction = array(
					'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
					'transaction_id' => '',
					'parent_transaction_id' => $paypal_order['authorization_id'],
					'note' => '',
					'msgsubid' => '',
					'receipt_id' => '',
					'payment_type' => 'void',
					'payment_status' => 'Void',
					'pending_reason' => '',
					'transaction_entity' => 'auth',
					'amount' => '',
					'debug_data' => json_encode($result),
				);

				$this->model_payment_pp_pro_iframe->addTransaction($transaction);
				$this->model_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);

				unset($transaction['debug_data']);
				$transaction['created'] = date("Y-m-d H:i:s");

				$json['data'] = $transaction;
				$json['error'] = false;
				$json['msg'] = 'Transaction void';
			} else {
				$json['error'] = true;
				$json['msg'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : $this->language->get('error_general'));
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_missing_data');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function orderAction() {
		$this->load->model('payment/pp_pro_iframe');
		$this->load->language('payment/pp_pro_iframe');

		$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->get['order_id']);

		if ($paypal_order) {

			$this->data['paypal_order'] = $paypal_order;
			$this->data['token'] = $this->session->data['token'];

			$this->data['order_id'] = $this->request->get['order_id'];
			$this->data['order_id'] = $this->request->get['order_id'];

			$captured = number_format($this->model_payment_pp_pro_iframe->totalCaptured($this->data['paypal_order']['paypal_iframe_order_id']), 2);
			$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedOrder($this->data['paypal_order']['paypal_iframe_order_id']), 2);

			$this->data['paypal_order']['captured'] = $captured;
			$this->data['paypal_order']['refunded'] = $refunded;
			$this->data['paypal_order']['remaining'] = number_format($this->data['paypal_order']['total'] - $captured, 2);

			$this->data['transactions'] = array();

			$this->data['view_link'] = $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['refund_link'] = $this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['resend_link'] = $this->url->link('payment/pp_pro_iframe/resend', 'token=' . $this->session->data['token'], 'SSL');

			if ($paypal_order) {
				$captured = number_format($this->model_payment_pp_pro_iframe->totalCaptured($paypal_order['paypal_iframe_order_id']), 2);
				$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedOrder($paypal_order['paypal_iframe_order_id']), 2);

				$this->data['paypal_order'] = $paypal_order;

				$this->data['paypal_order']['captured'] = $captured;
				$this->data['paypal_order']['refunded'] = $refunded;
				$this->data['paypal_order']['remaining'] = number_format($paypal_order['total'] - $captured, 2);

				foreach ($paypal_order['transactions'] as $transaction) {
					$this->data['transactions'][] = array(
						'paypal_iframe_order_transaction_id' => $transaction['paypal_iframe_order_transaction_id'],
						'transaction_id' => $transaction['transaction_id'],
						'amount' => $transaction['amount'],
						'created' => $transaction['created'],
						'payment_type' => $transaction['payment_type'],
						'payment_status' => $transaction['payment_status'],
						'pending_reason' => $transaction['pending_reason'],
						'view' => $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'] . "&transaction_id=" . $transaction['transaction_id'] . '&order_id=' . $this->request->get['order_id'], 'SSL'),
						'refund' => $this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . "&transaction_id=" . $transaction['transaction_id'] . "&order_id=" . $this->request->get['order_id'], 'SSL'),
						'resend' => $this->url->link('payment/pp_pro_iframe/resend', 'token=' . $this->session->data['token'] . "&paypal_iframe_order_transaction_id=" . $transaction['paypal_iframe_order_transaction_id'], 'SSL'),
					);
				}
			}

			$this->data['reauthorise_link'] = $this->url->link('payment/pp_pro_iframe/reauthorise', 'token=' . $this->session->data['token'], 'SSL');

			$this->template = 'payment/pp_pro_iframe_order.tpl';
			$this->response->setOutput($this->render());
		}
	}

	public function resend() {
			$this->load->model('payment/pp_pro_iframe');
			$this->load->language('payment/pp_pro_iframe');

			$json = array();

			if (isset($this->request->get['paypal_iframe_order_transaction_id'])) {
				$transaction = $this->model_payment_pp_pro_iframe->getFailedTransaction($this->request->get['paypal_iframe_order_transaction_id']);

				if ($transaction) {
					$call_data = unserialize($transaction['call_data']);

					$result = $this->model_payment_pp_pro_iframe->call($call_data);

					if ($result) {
						$parent_transaction = $this->model_payment_pp_pro_iframe->getLocalTransaction($transaction['parent_transaction_id']);

						if ($parent_transaction['amount'] == abs($transaction['amount'])) {
							$this->model_payment_pp_pro_iframe->updateRefundTransaction($transaction['parent_transaction_id'], 'Refunded');
						} else {
							$this->model_payment_pp_pro_iframe->updateRefundTransaction($transaction['parent_transaction_id'], 'Partially-Refunded');
						}

						if (isset($result['REFUNDTRANSACTIONID'])) {
							$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
						} else {
							$transaction['transaction_id'] = $result['TRANSACTIONID'];
						}

						if (isset($result['PAYMENTTYPE'])) {
							$transaction['payment_type'] = $result['PAYMENTTYPE'];
						} else {
							$transaction['payment_type'] = $result['REFUNDSTATUS'];
						}

						if (isset($result['PAYMENTSTATUS'])) {
							$transaction['payment_status'] = $result['PAYMENTSTATUS'];
						} else {
							$transaction['payment_status'] = 'Refunded';
						}

						if (isset($result['AMT'])) {
							$transaction['amount'] = $result['AMT'];
						} else {
							$transaction['amount'] = $transaction['amount'];
						}

						$transaction['pending_reason'] = (isset($result['PENDINGREASON']) ? $result['PENDINGREASON'] : '');

						$this->model_payment_pp_pro_iframe->updateTransaction($transaction);

						$json['success'] = $this->language->get('success_transaction_resent');
					} else {
						$json['error'] = $this->language->get('error_timeout');
					}
				} else {
					$json['error'] = $this->language->get('error_transaction_missing');
				}
			} else {
				$json['error'] = $this->language->get('error_missing_data');
			}

			$this->response->setOutput(json_encode($json));
		}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro_iframe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_pro_iframe_sig']) {
			$this->error['sig'] = $this->language->get('error_sig');
		}

		if (!$this->request->post['pp_pro_iframe_user']) {
			$this->error['user'] = $this->language->get('error_user');
		}

		if (!$this->request->post['pp_pro_iframe_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>