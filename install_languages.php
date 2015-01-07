<?php
// Version
define('VERSION', '1.5.2');

// Configuration
require_once('config.php');

// Install 
if (!defined('DIR_APPLICATION')) {
        header('Location: install/index.php');
            exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

$languages = $db->query("SELECT * FROM " . DB_PREFIX . "language");

function key_lenght_sort($a,$b){
    if (strlen($a) == strlen($b))
        return 0;
    if (strlen($a) < strlen($b))
        return 1;
    return -1;
}

function funcRecursiveLang($dir_name, $language , $admin = false) {
    global $db;
    $dir = @opendir ($dir_name);
    
    while ($file = readdir ($dir)) {
        if (($file == ".") || ($file == ".."))
            continue;
        if (filetype ("$dir_name/$file") == "dir") {
            funcRecursiveLang("$dir_name/$file", $language, $admin);
        } else {
            $_ = array();

            $viev_file_content = $controller_file_content =  "";
            
            include $dir_name."/".$file;  
            uksort($_, "key_lenght_sort");
                        
            $first_folder = ($admin) ? 'admin' : 'catalog';            
                        
            $controller_file = "$first_folder/controller/".str_replace(array("$first_folder/language/", $language["directory"]."/"), array("", ""), $dir_name."/".$file);

		if ($admin) {
			$insert_directory = addslashes('admin/' . str_replace(array("admin/language/", ".php", $language["directory"]."/"), "", $dir_name."/".$file));
			$main_lang_file = 'admin/'.$language["directory"];
			$lang_load_pregmatch = '[\s*\$this->language->load\(('."\'".'|'.'\"'.')(.*)('."\'".'|'.'\"'.')\)\s*\;]';
		} else {
			$insert_directory = addslashes(str_replace(array("catalog/language/", ".php", $language["directory"]."/"), "", $dir_name."/".$file));
			$main_lang_file = $language["directory"];
			$lang_load_pregmatch = '[\s*\$this->language->load\(('."\'".'|'.'\"'.')(.*)('."\'".'|'.'\"'.')\)\s*\;]';
		}
		
/*		foreach ($_ as $name => $value) {
		
			func_insert_lang_value($language["code"],$insert_directory,$name,$value);
		}*/		

		if (file_exists($controller_file)) {                
			$controller_file_content = file_get_contents($controller_file);

			if (preg_match_all('[\s*\$this->data\[('."\'".'|'.'\"'.')(.*)('."\'".'|'.'\"'.')\]\s*\=\s*\$this->language->get\(('."\'".'|'.'\"'.')(.*)('."\'".'|'.'\"'.')\)\s*;]', $controller_file_content, $ret_preg)) {
				foreach ($ret_preg[0] as $k => $repl) {
// 					if (in_array($ret_preg[2][$k] , array_keys($_))) {
// 						func_insert_lang_value($language["code"],$insert_directory,$ret_preg[2][$k],$_[$ret_preg[2][$k]]);
// 					} else {
// 						func_check_lang_value($language["code"],$main_lang_file,$ret_preg[2][$k]);
// 					}
					$controller_file_content = str_replace($repl, "", $controller_file_content);
				}
			}
			if (preg_match_all($lang_load_pregmatch, $controller_file_content, $ret_preg)) {
				foreach ($ret_preg[0] as $repl) {
					$repl = str_replace(';', "" , $repl);
				$controller_file_content = str_replace($repl, "\n\r" . '$this->data = array_merge( $this->data , ' . trim($repl) . ')' , $controller_file_content);
				}
			}	
			echo $controller_file."<br>";
			$fp = fopen($controller_file, "w");
			fwrite($fp, $controller_file_content);
			fclose($fp);
		}
        }
    }
    closedir($dir);
}

function func_insert_lang_value($code,$insert_directory,$name,$value) {
	global $db;	
	$check = $db->query("SELECT value FROM language_text WHERE code = '".$code."' AND directory = '".$insert_directory."' AND name = '".addslashes($name)."' ")->num_rows;    
		
	if ($check == 0)
		$db->query("INSERT INTO language_text (code, directory, name, value) 
			VALUES 
				(
					'".$code."', 
					'".$insert_directory."',
					'".addslashes($name)."',
					'".addslashes($value)."'
				)");
				

}
function func_check_lang_value($code,$insert_directory,$name) {
	global $db;	
	$check = $db->query("SELECT value FROM language_text WHERE code = '".$code."' AND directory = '".$insert_directory."' AND name = '".addslashes($name)."' ")->num_rows;    
	if (empty($check))
		$db->query("INSERT INTO language_text (code, directory, name, value) 
			VALUES 
				(
					'".$code."', 
					'".$insert_directory."',
					'".addslashes($name)."',
					''
				)");
				

}
function funcChangeLoad($dir_name, $language) {
    global $db;
    $dir = @opendir ($dir_name);
    while ($file = readdir ($dir)) {
        if (($file == ".") || ($file == ".."))
            continue;
        if (filetype ("$dir_name/$file") == "dir") {
            funcChangeLoad("$dir_name/$file", $language);
        } else {
            $_ = array();

            $viev_file_content = $controller_file_content =  "";
            
            
            $viev_file = "catalog/view/theme/default/template/".str_replace(array(".php", "catalog/language/", $language["directory"]."/"), array(".tpl", "", ""), $dir_name."/".$file);
            $controller_file = "catalog/controller/".str_replace(array("catalog/language/", $language["directory"]."/"), array("", ""), $dir_name."/".$file);

            if (file_exists($viev_file) && file_exists($controller_file)) {
                $viev_file_content = file_get_contents($viev_file);
                $controller_file_content = file_get_contents($controller_file);

		
                    if (preg_match_all('[\s*\$this->language->load\(('."\'".'|'.'\"'.')(.*)('."\'".'|'.'\"'.')\)\s*\;]', $controller_file_content, $ret_preg)) {

                        foreach ($ret_preg[0] as $repl) {
				$repl = str_replace(';', "" , $repl);
				
                            $controller_file_content = str_replace($repl, "\n\r" . '$this->data = array_merge( $this->data , ' . trim($repl) . ')' , $controller_file_content);
                        }
                    }
                
                echo $controller_file."<br>";
                $fp = fopen($controller_file, "w");
                fwrite($fp, $controller_file_content);
                fclose($fp);

            }
        }
    }
    closedir($dir);
}


foreach ($languages->rows as $language) {
	$dir_name = "catalog/language/".$language["directory"];
	funcRecursiveLang($dir_name, $language);
	
// 	$dir_name = "admin/language/".$language["directory"];
// 	funcRecursiveLang($dir_name, $language, true);
}

?>
