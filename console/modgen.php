#!/usr/bin/php
<?php
// Language
require_once 'lang/en_g.php';

echo "\n=====================\n";
echo $_lang['title']."\n";
echo "@TvorZasp\n";
echo "=====================\n";

//fscanf(STDIN, "%d\n", $number)

// Settings
$language_std = "english";
$theme_std = "default";
$name_std = "Name Name";
$genmodel_std = true;
$big = true;

// Questions
do{
	echo $_lang['module_name'] . ":";
	$modulename = trim(fgets(STDIN)); 
	if($modulename){
		break;
	}
}while (1);

$folders_std = dirname(dirname(__FILE__))."/sceleton/".$modulename."/";

echo $_lang['module_folder']."(".$_lang['standart']." '".$folders_std."'):"; 
$folders = trim(fgets(STDIN));
if(!$folders){
	$folders = $folders_std; 
}

echo $_lang['language']."(".$_lang['standart']." '".$language_std."'):"; 
$language = trim(fgets(STDIN)); 
if(!$language){
	$language = $language_std; 
}

echo $_lang['theme_name']."(".$_lang['standart']." '".$theme_std."'):"; 
$theme = trim(fgets(STDIN));
if(!$theme){
	$theme = $theme_std; 
}

echo $_lang['autor_name']."(".$_lang['standart']." '".$name_std."'):"; 
$name = trim(fgets(STDIN));
if(!$name){
	$name = $name_std; 
}

do{
	echo $_lang['model']."(Yes/No):"; 
	$genmodel = trim(fgets(STDIN)); 
	if($genmodel == "yes" || $genmodel == "Yes" || $genmodel == "Y" || $genmodel == "y"){
		$genmodel_std = true; 
		break;
	}elseif($genmodel == "no" || $genmodel == "No" || $genmodel == "N" || $genmodel == "n"){
		$genmodel_std = false;
		break; 
	}
}while (1);


do{
	echo $_lang['all_module']."(Yes/No):"; 
	$big = trim(fgets(STDIN)); 
	if($big == "yes" || $big == "Yes" || $big == "Y" || $big == "y"){
		$big = true; 
		break;
	}elseif($big == "no" || $big == "No" || $big == "N" || $big == "n"){
		$big = false;
		break; 
	}
}while (1);

// Admin controller
@mkdir($folders);
@mkdir($folders."/admin/");
@mkdir($folders."/admin/controller/");
@mkdir($folders."/admin/controller/module/");
$module_admin_controller = fopen($folders."/admin/controller/module/".$modulename.".php", 'w');

$module_admin_controller_text = '<?php
class ControllerModule'.ucwords($modulename).' extends Controller {
	private $error = array(); 
	
	public function index() { 
		$this->load->language("module/'.$modulename.'");

		$this->document->setTitle($this->language->get("heading_title")); 
		
		$this->load->model("setting/setting");
				
		if (($this->request->server["REQUEST_METHOD"] == "POST") && $this->validate()) {
			$this->model_setting_setting->editSetting("'.$modulename.'", $this->request->post);		
					
			$this->session->data["success"] = $this->language->get("text_success");
						
			$this->redirect($this->url->link("extension/module", "token=" . $this->session->data["token"], "SSL"));
		}
		
		$this->data["heading_title"] = $this->language->get("heading_title");
		
		//buttons
		$this->data["button_save"] = $this->language->get("button_save");
		$this->data["button_cancel"] = $this->language->get("button_cancel");
		$this->data["button_add_module"] = $this->language->get("button_add_module");
		$this->data["button_remove"] = $this->language->get("button_remove");
		
		//errors
		if (isset($this->error["warning"])) {
			$this->data["error_warning"] = $this->error["warning"];
		} else {
			$this->data["error_warning"] = "";
		}
		
		//breadcrumbs
		$this->data["breadcrumbs"] = array();

   		$this->data["breadcrumbs"][] = array(
       		"text"      => $this->language->get("text_home"),
			"href"      => $this->url->link("common/home", "token=" . $this->session->data["token"], "SSL"),
      		"separator" => false
   		);

   		$this->data["breadcrumbs"][] = array(
       		"text"      => $this->language->get("text_module"),
			"href"      => $this->url->link("extension/module", "token=" . $this->session->data["token"], "SSL"),
      		"separator" => " :: "
   		);
		
   		$this->data["breadcrumbs"][] = array(
       		"text"      => $this->language->get("heading_title"),
			"href"      => $this->url->link("module/'.$modulename.'", "token=" . $this->session->data["token"], "SSL"),
      		"separator" => " :: "
   		);
		
		$this->data["action"] = $this->url->link("module/'.$modulename.'", "token=" . $this->session->data["token"], "SSL");
		
		$this->data["cancel"] = $this->url->link("extension/module", "token=" . $this->session->data["token"], "SSL");
		
		//------------------------------
		//insert you data
		//------------------------------
		
		
		
		$this->data["modules"] = array();
		
		if (isset($this->request->post["'.$modulename.'_module"])) {
			$this->data["modules"] = $this->request->post["'.$modulename.'_module"];
		} elseif ($this->config->get("category_module")) { 
			$this->data["modules"] = $this->config->get("'.$modulename.'_module");
		}	
				
		$this->load->model("design/layout");
		
		$this->data["layouts"] = $this->model_design_layout->getLayouts();
		
		$this->template = "module/'.$modulename.'.tpl";
		$this->children = array(
			"common/header",
			"common/footer",
		);
		
		$this->data["token"] = $this->session->data["token"];
				
		$this->response->setOutput($this->render());
	}
	
	public function install(){
	
	}
	
	public function uninstall(){
	
	}
	
	private function validate() {
		if (!$this->user->hasPermission("modify", "module/'.$modulename.'")) {
			$this->error["warning"] = $this->language->get("error_permission");
		}

		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>';

fwrite($module_admin_controller, $module_admin_controller_text);
fclose($module_admin_controller);

// Admin language
@mkdir($folders."/admin/language/");
@mkdir($folders."/admin/language/".$language."/");
@mkdir($folders."/admin/language/".$language."/module/");
$module_admin_language = fopen($folders."/admin/language/".$language."/module/".$modulename.".php", 'w');

$module_admin_language_text = '<?php
// Heading 
$_["heading_title"]  = "'.ucwords($modulename).'"; 
?>';

fwrite($module_admin_language, $module_admin_language_text);
fclose($module_admin_language);

// Admin view
@mkdir($folders."/admin/view/");
@mkdir($folders."/admin/view/template/");
@mkdir($folders."/admin/view/template/module/");
$module_admin_template = fopen($folders."/admin/view/template/module/".$modulename.".tpl", 'w');

$module_admin_template_text = '<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb["separator"]; ?><a href="<?php echo $breadcrumb["href"]; ?>"><?php echo $breadcrumb["text"]; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?> 
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$(\'#form\').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = \'<?php echo $cancel; ?>\';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  </div>
</div>';

fwrite($module_admin_template, $module_admin_template_text);
fclose($module_admin_template);

// Admin model
if($genmodel_std){
	@mkdir($folders."/admin/model/");
	@mkdir($folders."/admin/model/module/");
	$module_admin_model = fopen($folders."/admin/model/module/".$modulename.".php", 'w');
	
	$module_admin_model_text = '<?php
	class ModelModule'.ucwords($modulename).' extends Model {
		
		public function set() {  
		}
		
		private function get() {
		}
	}
	?>';
	
	fwrite($module_admin_model, $module_admin_model_text);
	fclose($module_admin_model);
}

// Catalog controller
@mkdir($folders);
@mkdir($folders."/catalog/");
@mkdir($folders."/catalog/controller/");
@mkdir($folders."/catalog/controller/module/");
$module_catalog_controller = fopen($folders."/catalog/controller/module/".$modulename.".php", 'w');

$module_catalog_controller_text = '<?php
class ControllerModule'.ucwords($modulename).' extends Controller {
	
	public function index() { 
		$this->load->language("module/'.$modulename.'");

		$this->data["heading_title"] = $this->language->get("heading_title");
		
		if (file_exists(DIR_TEMPLATE . $this->config->get("config_template") . "/template/module/'.$modulename.'.tpl")) {
			$this->template = $this->config->get("config_template") . "/template/module/'.$modulename.'.tpl";
		} else {
			$this->template = "default/template/module/'.$modulename.'.tpl";
		}
		
		';
if($big){
	$module_catalog_controller_text .= '$this->children = array(
			"common/column_left",
			"common/column_right",
			"common/content_top",
			"common/content_bottom",
			"common/footer",
			"common/header"
		);
										
		$this->response->setOutput($this->render());';
}else{
	$module_catalog_controller_text .= '
	$this->render();';
}
$module_catalog_controller_text .='
	}
}
?>';

fwrite($module_catalog_controller, $module_catalog_controller_text);
fclose($module_catalog_controller);

// Catalog language
@mkdir($folders."/catalog/language/");
@mkdir($folders."/catalog/language/".$language."/");
@mkdir($folders."/catalog/language/".$language."/module/");
$module_catalog_language = fopen($folders."/catalog/language/".$language."/module/".$modulename.".php", 'w');

$module_catalog_language_text = '<?php
// Heading 
$_["heading_title"]  = "'.ucwords($modulename).'"; 
?>';

fwrite($module_catalog_language, $module_catalog_language_text);
fclose($module_catalog_language);

// Catalog view
@mkdir($folders."/catalog/view/");
@mkdir($folders."/catalog/view/theme/");
@mkdir($folders."/catalog/view/theme/".$theme."/");
@mkdir($folders."/catalog/view/theme/".$theme."/template/");
@mkdir($folders."/catalog/view/theme/".$theme."/template/module/");
$module_catalog_template = fopen($folders."/catalog/view/theme/".$theme."/template/module/".$modulename.".tpl", 'w');

$module_catalog_template_text = '<?php echo $heading_title;?>';

fwrite($module_catalog_template, $module_catalog_template_text);
fclose($module_catalog_template);

// Catalog model
if($genmodel_std){
	@mkdir($folders."/catalog/model/");
	@mkdir($folders."/catalog/model/module/");
	$module_catalog_model = fopen($folders."/catalog/model/module/".$modulename.".php", 'w');
	
	$module_catalog_model_text = '<?php
	class ModelModule'.ucwords($modulename).' extends Model {
		
		public function set() {  
		}
		
		private function get() {
		}
	}
	?>';
	
	fwrite($module_catalog_model, $module_catalog_model_text);
	fclose($module_catalog_model);
}

echo "=====================";
echo $_lang['complete'];
echo "=====================\n";

// Functions
function rec_copy ($from_path, $to_path) { 
	if(!is_dir($to_path)){
		mkdir($to_path, 0777);
	} 
	
	$this_path = getcwd(); 
	if (is_dir($from_path)) { 
 		chdir($from_path); 
 		$handle=opendir('.'); 
	  	while (($file = readdir($handle))!==false) { 
	   		if (($file != ".") && ($file != "..")) { 
	    		if (is_dir($file)) { 
	     			rec_copy ($from_path.$file."/", $to_path.$file."/"); 
	     			chdir($from_path); 
	    		} 
	    		if (is_file($file)) copy($from_path.$file, $to_path.$file);
	   		} 
	  	} 
 		closedir($handle); 
	} 
}
?>
