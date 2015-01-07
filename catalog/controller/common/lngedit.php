<?php
class Controllercommonlngedit extends Controller {
	public function index() {
	
		if( empty( $this->session->data["token"] ) || empty( $this->session->data["lng_editable"] ) ) {
			$this->redirect( $this->url->link("common/home") );
		}
	
		$this->load->model('localisation/language');
	
		$languages = $this->model_localisation_language->getLanguages();
		
		$languages_array = array();
		
		foreach( $languages as $language ){
			$languages_array[ $language["code"] ] = $language;
		}
		
		foreach( $this->request->post["lng_text"] as $lng_code => $language_text ){
			$current_language = $languages_array[ $lng_code ];
			
			$language_dirrectory = $current_language["directory"];
			$language_path = $this->request->post["lng_file"];
			$language_file = DIR_LANGUAGE . $language_dirrectory . "/" . $language_path . ".php";
			$language_text = htmlspecialchars_decode( $language_text );
			$language_key = $this->request->post["lng_key"];
			
			if( file_exists( $language_file ) ) {
				$file =  file_get_contents( $language_file );
				$file = str_replace( "\r" , "", $file );
				$file_content = explode( "\n", $file );
			} else {
				$file_content = array(
					"<?php"
				);
			}
			
			$append_string = '/* replaced "' . $language_key . '" */ $_["' . $language_key . '"] = "' . str_replace( '"', '\"', $language_text ). '";';

			foreach( $file_content as $key => $value ){
			
				preg_match( "/[\/][*] replaced \"" . $language_key . "\" [*][\/].*/i", $value, $matches );
				
				if( trim( $value ) == "?>" || !empty( $matches ) ) {
					unset( $file_content[$key] );
				}
			}
			
			$file_content[] = $append_string;
			$file_content[] = "?>";
			
			if( !@file_put_contents( $language_file, implode( "\n", $file_content ) ) ) {
				exit('Error! Language folder is not writable!');
			}
		}
		exit('Ok');
	}
	
	public function getLanguageVars(){
		$file_route = $this->request->post["file"];
		$key_variable = $this->request->post["key"];
		
		$this->load->model('localisation/language');
	
		$languages = $this->model_localisation_language->getLanguages();
		
		$json = array();
		
		foreach( $languages as $language ){
			$this->language->load( $language["filename"], $language["directory"] );
			$this->language->load( $file_route, $language["directory"] );
			
			$languageVar = array(
				"active" => ( $this->session->data["language"] == $language["code"] ) ? 'active': '',
				"code" => $language["code"],
				"image" => $language["image"],
				"value" => htmlspecialchars( $this->language->get( $key_variable, false ) )
			);
		
			$json[] = $languageVar;
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
}
?>