<?php  
class ControllerModuleMenu extends Controller {
	protected function index($setting) {
		static $module = 0;
		
		$this->load->model('design/menu');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');

		$this->data['logged'] = $this->customer->isLogged();
		
		$this->document->addScript('catalog/view/javascript/jquery/jquery.cycle.js');

		$this->data['links'] = array();
		
		$this->load->model("tool/image");
		
		$results = $this->model_design_menu->getMenu($setting['menu_id']);
		
		$menu_info = $this->model_design_menu->getMenuInfo($setting['menu_id']);
		
		$this->data["style_id"] = $menu_info["style_id"];
		$this->data["menu_id"] = $setting['menu_id'];

		$this->data["heading_title"] = $menu_info["name"];

		if( $setting["position"] == "content_top" || $setting["position"] == "content_bottom" || $setting["position"] == "content_header" || $setting["position"] == "content_footer" ){
			$this->data["menu_type"] = "gorizontal";
		} else {
			$this->data["menu_type"] = "vertical";
		}
		
		$this->level = 0;
		
		$this->data['links'] = $this->menuCreate( $results );

		//p($this->data['links']);
		$this->data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/menu.tpl';
		} else {
			$this->template = 'default/template/module/menu.tpl';
		}
		
		$this->render();
	}
	
	protected function menuCreate( $results ){
		$links = array();
	
		$column_id = 0;
		$item_iterator = 0;
		foreach ($results as $result) {
			
			$column_limit = !empty( $result["column_limit"] ) ? $result["column_limit"] : 999 ;
			
			if( $item_iterator >= $column_limit ){
				$item_iterator = 0;
				$column_id ++;
			}
			$item_iterator ++;
			
			$image = !empty( $result["image"] ) ? $result["image"] : false;
			
			if( !empty( $image ) ) {
				$image = $this->model_tool_image->resize($image, 24, 24);
			}
			
			$color_ = '';
			$column_width_ = 0;
			if (isset($result[ "color_" ]) && !empty($result[ "color_" ]))
				$color_ = $result[ "color_" ];
			if (isset($result[ "column_width_" ]) && $result[ "column_width_" ] > 0)
				$column_width_ = $result[ "column_width_" ];
			
			
			$link = array();
			$links_submenu = array();

			$sub_class = "";
			if( $result["menu_type"] == "delimiter" ){
// 				if( !empty( $result[ "name" ] ) ) {
					$column_id = !empty( $links ) ? $column_id + 1 : $column_id;
					$link = array(
						"image" => $image,
						"column" => $column_id,						
						"title" => $result[ "name" ],
						"type" => "delimiter",
						"href" => "",
						"sub_class" => ""
					);										
					$item_iterator = 0;
// 					$column_id ++;
// 				} else {
// 					$link = array();
// 				}
			} elseif( $result["menu_type"] == "route" ){
			
				$link_request = explode( "?", $result[ $result["menu_type"] ] );

				if(
					(empty( $this->request->server["QUERY_STRING"] ) && empty( $link_request[1] ) && strpos( $link_request[0], $this->request->server["SERVER_ADDR"] ) !== false ) || 
					(!empty( $this->request->server["QUERY_STRING"] ) && !empty( $link_request[1] ) && $link_request[1] == $this->request->server["QUERY_STRING"] )
				) $sub_class .= " active";
				
				$link = array(
					"image" => $image,
					"column" => $column_id,
					"title" => $result[ "name" ],
					"href" => $result[ $result["menu_type"] ],
					"sub_class" => $sub_class
				);
				
			} elseif( $result["menu_type"] == "html" ){
				$result["html"] = htmlspecialchars_decode( $result["html"] );
				if( trim( strip_tags( $result["html"] ) ) != "" ){
					
					$last_link = end( $links );
					if( empty( $last_link["type"] ) || $last_link["type"] != "delimiter" ) {
						$column_id = !empty( $links ) ? $column_id + 1 : $column_id;
					}
					
					$link = array(
						"image" => $image,
						"column" => $column_id,
						"title" => $result["name"],
						"href" => "",
						"html" => $result["html"],
						"type" => 'html',
						"sub_class" => $sub_class
					);
					
					$item_iterator = 0;
					$column_id ++;
				} else {
					$link = array();
				}
			} elseif( $result["menu_type"] == "category" ){
				$category_id = (int)$result[ $result["menu_type"] ];

				$active = false;
				
				$category_info = $this->model_catalog_category->getCategory( $category_id );
				$array_cat = array();
				if( !empty( $category_info ) ) {
					if( !empty( $category_info["parent_id"] ) ) $array_cat[] = $category_info["parent_id"];
					$array_cat[] = $category_info["category_id"];
				}

				$cats = array();

				$submenu = array();
				$current_category_info = array();
				
				if( !empty( $this->request->get["path"] ) ) {
					$cats = explode( "_", $this->request->get["path"] );
				}
				
				foreach( $this->model_catalog_category->getCategories( $category_id ) as $category ){
					if( $category["parent_id"] == $category_id || in_array( $category_id, $cats )){
						if( !empty( $this->request->get["path"] ) ) {
							if( in_array( $category["category_id"], $cats )  ){
								$sub_class .= " active";
								$active = true;
							}
						}
						$array_cat_modif = $array_cat;
						$array_cat_modif[] = $category["category_id"];
						$submenu[] = array(
							"image" => $image,
							"column" => $column_id,
							"title" => $category["name"],
							"href" => $this->url->link("product/category", "path=" . implode( '_', $array_cat_modif ) ),
							"sub_class" => !empty( $cats ) && in_array( $category["category_id"], $cats ) ? " active" : "",
							"category_id" => $category["category_id"]
						);
					}
				}
				
				if( $category_id === 0 ) {

					$links_submenu = $submenu;

					foreach( $links_submenu as $key => $_category_submenu ){

						$active = false;

						$array_cat = array(
							$_category_submenu["category_id"]
						);
						
						$submenu = array();
						foreach( $this->model_catalog_category->getCategories( $_category_submenu["category_id"] ) as $category ){
							if( $category["parent_id"] == $_category_submenu["category_id"] ){
								if( !empty( $this->request->get["path"] ) ) {
									$cats = explode( "_", $this->request->get["path"] );
									if( in_array( $category["category_id"], $cats )  ){
										$sub_class .= " active";
										$active = true;
									}
								}
								$array_cat_modif = $array_cat;
								$array_cat_modif[] = $category["category_id"];
								$submenu[] = array(
									"image" => $image,
									"column" => $column_id,
									"title" => $category["name"],
									"href" => $this->url->link("product/category", "path=" . implode( '_', $array_cat_modif ) ),
									"sub_class" => !empty( $cats ) && in_array( $category["category_id"], $cats ) ? " active" : "",
									"category_id" => $category["category_id"]
								);
							}
						}
						$links_submenu[ $key ]["submenu"] = $submenu;
						$links_submenu[ $key ]["sub_class"] = $active || !empty( $this->request->get["path"] ) && in_array( $_category_submenu["category_id"], $cats ) ? " active" : "";
					}

					$link = array();
				
				} else {
				
					$link = array(
						"image" => $image,
						"column" => $column_id,
						"title" => $result["name"],
						"href" => $this->url->link("product/category", "path=" . implode( '_', $array_cat) ),
						"submenu" => $submenu,
						"sub_class" => $active || !empty( $this->request->get["path"] ) && in_array( $category_id, $cats ) ? " active" : ""
					);
					
				}
				
			} elseif( $result["menu_type"] == "information" ){
				$information_id = (int)$result[ $result["menu_type"] ];
				
				$information_info = $this->model_catalog_information->getInformation($information_id);
				
				if( !empty( $this->request->get["information_id"] ) && $this->request->get["information_id"] == $information_id ) $sub_class .= " active";
				
				$link = array(
					"image" => $image,
					"column" => $column_id,
					"title" => $result["name"],
					"href" => $this->url->link("information/information", "information_id=" . $information_info["information_id"] ),
					"sub_class" => $sub_class
				);

			} elseif( $result["menu_type"] == "contactus" ){
			
				$link_request = explode( "?", $this->url->link("information/contact" ) );

				if( 
					(empty( $this->request->server["QUERY_STRING"] ) && empty( $link_request[1] ) && strpos( $link_request[0], $this->request->server["SERVER_ADDR"] ) !== false ) || 
					(!empty( $this->request->server["QUERY_STRING"] ) && !empty( $link_request[1] ) && $link_request[1] == $this->request->server["QUERY_STRING"] )
				) $sub_class .= " active";
				
				$link = array(
					"image" => $image,
					"column" => $column_id,
					"title" => $result[ "name" ],
					"href" => $this->url->link("information/contact"),
					"sub_class" => $sub_class
				);

			} elseif( $result["menu_type"] == "news" ){
			
				$link_request = explode( "?", $this->url->link("information/press" ) );

				if( 
					(empty( $this->request->server["QUERY_STRING"] ) && empty( $link_request[1] ) && strpos( $link_request[0], $this->request->server["SERVER_ADDR"] ) !== false ) || 
					(!empty( $this->request->server["QUERY_STRING"] ) && !empty( $link_request[1] ) && $link_request[1] == $this->request->server["QUERY_STRING"] )
				) $sub_class .= " active";
				
				$link = array(
					"image" => $image,
					"column" => $column_id,
					"title" => $result[ "name" ],
					"href" => $this->url->link("information/press"),
					"sub_class" => $sub_class
				);

			} elseif( $result["menu_type"] == "submenu" ){
				$link_submenu_generate = $this->menuCreate( array(
					array(
					    "name" => $result["name"],
					    "column_limit" => $result["column_limit"],
					    "style_id" => $result["style_id"],
					    "menu_route_id" => $result["menu_route_id"],
					    "store_id" => $result["store_id"],
					    "menu_type" => $result["menu_type_2"],
					    "route" => $result["route"],
					    "information" => $result["information"],
					    "category" => $result["category"],
					    "submenu" => $result["submenu"],
					    "image" => $result["image"],
					    "sort_order" => $result["sort_order"],
					    "language_id" => $result["language_id"],
					    "html" => $result["html"]
					)
				) );
				
				$link_submenu_generated = end( $link_submenu_generate );
				
				$menu_id = (int)$result[ $result["menu_type"] ];
				
				$child_results = $this->model_design_menu->getMenu( $menu_id );
				
				if( $this->level < 4 ){
					$this->level ++;
					$child_links = $this->menuCreate( $child_results );
					
					foreach( $child_links as $child_link ){
						if( $child_link["sub_class"] == " active" ) $sub_class = " active";
					}
					
					$this->level --;
					$link = array(
						"image" => $image,
						"column" => $column_id,
						"title" => $result["name"],
						"href" => !empty( $link_submenu_generated["href"] ) ? $link_submenu_generated["href"] : "",
						"submenu" => $child_links,
						"sub_class" => $sub_class
					);
					
				} else {
					$link = array();
				}
			}
			
			if (isset($color_) && !empty($color_))
				$link["color_"] = $color_;
			if (isset($column_width_) && $column_width_ > 0)
				$link["column_width_"] = $column_width_;			
			
					
			if( !empty( $link ) ) {
				$links[] = $link;
			} elseif( is_array( $links_submenu ) && count( $links_submenu ) > 0 ){
				foreach( $links_submenu as $link ){
					$links[] = $link;
				}
			}
		}
		return $links;
	}
}
?>