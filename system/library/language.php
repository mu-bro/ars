<?php
class Language {
	private $default = 'english';
	private $directory;
	private $data = array();
 
	public function __construct($directory) {
		$this->directory = $directory;
	}
	
	public function get($key, $editable = true) {
		global $session, $language_zone_id;
		if( !empty( $session->data["token"] ) && !empty( $session->data["lng_editable"] ) && $language_zone_id == "customer" && $editable ){
			return "<span class=lng_etitable lng_key=" . $key . " zone=" . $language_zone_id . " lng_file=" . $session->data["last_loaded_lng_file"] . ">" . (isset($this->data[$key]) ? str_replace(array('"', "'"), "", $this->data[$key] ) : $key) . "</span>";
		}
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}
	
	public function load($filename, $directory = false) {
		global $session, $language_zone_id;

		global $session;
		$session->data["last_loaded_lng_file"] = $filename;

		if( !empty( $directory ) ) {
			$file = DIR_LANGUAGE . $directory . '/' . $filename . '.php';
		} else {
			$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';
		}

		if (file_exists($file)) {
			$_ = array();

			require($file);

			if( !empty( $session->data["token"] ) && !empty( $session->data["lng_editable"] ) && $language_zone_id == "customer"){
				foreach ($_ as $key => $value) {
					$_[$key] = "<span class=lng_etitable lng_key=" . $key . " zone=" . $language_zone_id . " lng_file=" . $session->data["last_loaded_lng_file"] . ">" . $value . "</span>";
				}
			}

			$this->data = array_merge($this->data, $_);

			return $this->data;
		}

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (file_exists($file)) {
			$_ = array();

			require($file);

			if( !empty( $session->data["token"] ) && !empty( $session->data["lng_editable"] ) && $language_zone_id == "customer"){
				foreach ($_ as $key => $value) {
					$_[$key] = "<span class=lng_etitable lng_key=" . $key . " zone=" . $language_zone_id . " lng_file=" . $session->data["last_loaded_lng_file"] . ">" . $value . "</span>";
				}
			}			

			$this->data = array_merge($this->data, $_);

			return $this->data;
		} else {
			trigger_error('Error: Could not load language ' . $filename . '!');
			exit();
		}
  	}
}
?>
