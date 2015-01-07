<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height, $type = "", $creation = false,$crop = false) {

		if ($creation) {
			$img_dir = $creation . '/';
			$add_folder = str_replace(DIR_WEBSITE,"",$img_dir);
		} else {
			$img_dir = DIR_IMAGE;
			$add_folder = '';
		}
		$cache_dir = DIR_IMAGE;

		if (!file_exists($img_dir . $filename) || !is_file($img_dir . $filename)) {
			return;
		} 

		$width_ = $width == '*' || $width == 'auto' || $width == 0 ? 'auto': $width;
		$height_ = $height == '*' || $height == 'auto' || $height == 0 ? 'auto': $height;
		
		$info = pathinfo($filename);
		
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . $add_folder . $this->slugify(utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type .'.' . $extension);
		
		if (!file_exists($cache_dir . $new_image) || (filemtime($img_dir . $old_image) > filemtime($cache_dir . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists($cache_dir . $path)) {
					@mkdir($cache_dir . $path, 0777);
				}		
			}
			
			list($width_orig, $height_orig) = getimagesize($img_dir . $old_image);
			$orig_size = array(
				'width' => $width_orig,
				'height' => $height_orig
			);
			if( $width == "*" || $width == "auto" ) $width = $this->calcW( $orig_size, $height );
			if( $height == "*" || $height == "auto" ) $height = $this->calcH( $orig_size, $width );			

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image($img_dir . $old_image);
				$image->resize($width, $height, $type, $crop);
				$image->save($cache_dir . $new_image);
			} else {
				copy($img_dir . $old_image, $cache_dir . $new_image);
			}
		}
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}	
	}
	
	/**
	 * Slugify string.
	 * Used to make filename without rusian letters, spaces, etc.
	 */
	public function slugify($string) {
		return strtolower(trim(preg_replace('~[^0-9a-z\.]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
	}

	private function calcW( $imageInfo, $height ){
		return ( $imageInfo['width'] * $height ) / $imageInfo['height'];
	}
	private function calcH( $imageInfo, $width ){
		return ( $imageInfo["height"] * $width ) / $imageInfo['width'];
	}	
}
?>
