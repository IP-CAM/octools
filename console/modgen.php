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
		
		$this->data["text_enabled"] = $this->language->get("text_enabled");
		$this->data["text_disabled"] = $this->language->get("text_disabled");
		$this->data["text_content_top"] = $this->language->get("text_content_top");
		$this->data["text_content_bottom"] = $this->language->get("text_content_bottom");		
		$this->data["text_column_left"] = $this->language->get("text_column_left");
		$this->data["text_column_right"] = $this->language->get("text_column_right");
		
		$this->data["entry_layout"] = $this->language->get("entry_layout");
		$this->data["entry_position"] = $this->language->get("entry_position");
		$this->data["entry_status"] = $this->language->get("entry_status");
		$this->data["entry_sort_order"] = $this->language->get("entry_sort_order");
		
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
		} elseif ($this->config->get("'.$modulename.'_module")) { 
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

// Text
$_["text_module"]         = "Модули";
$_["text_success"]        = "Модуль '.$modulename.' успешно обновлен!";
$_["text_content_top"]    = "Содержание шапки";
$_["text_content_bottom"] = "Содержание подвала";
$_["text_column_left"]    = "Левая колонка";
$_["text_column_right"]   = "Правая колонка";

// Entry
$_["entry_layout"]        = "Схема:";
$_["entry_position"]      = "Расположение:";
$_["entry_status"]        = "Статус:";
$_["entry_sort_order"]    = "Порядок сортировки:";

// Error
$_["error_permission"]    = "У Вас нет прав для изменения модуля '.$modulename.'!";
?>';
fwrite($module_admin_language, $module_admin_language_text);
fclose($module_admin_language);

// Admin view
@mkdir($folders."/admin/view/");
@mkdir($folders."/admin/view/template/");
@mkdir($folders."/admin/view/template/module/");
$module_admin_template = fopen($folders."/admin/view/template/module/".$modulename.".tpl", 'w');

$module_admin_template_text = '
<?php echo $header; ?>
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
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left"><select name="for_raplase_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout[\'layout_id\'] == $module[\'layout_id\']) { ?>
                <option value="<?php echo $layout[\'layout_id\']; ?>" selected="selected"><?php echo $layout[\'name\']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout[\'layout_id\']; ?>"><?php echo $layout[\'name\']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="for_raplase_module[<?php echo $module_row; ?>][position]">
                <?php if ($module[\'position\'] == \'content_top\') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>  
                <?php if ($module[\'position\'] == \'content_bottom\') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>     
                <?php if ($module[\'position\'] == \'column_left\') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module[\'position\'] == \'column_right\') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="for_raplase_module[<?php echo $module_row; ?>][status]">
                <?php if ($module[\'status\']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="for_raplase_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module[\'sort_order\']; ?>" size="3" /></td>
            <td class="left"><a onclick="$(\'#module-row<?php echo $module_row; ?>\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = \'<tbody id="module-row\' + module_row + \'">\';
	html += \'  <tr>\';
	html += \'    <td class="left"><select name="for_raplase_module[\' + module_row + \'][layout_id]">\';
	<?php foreach ($layouts as $layout) { ?>
	html += \'      <option value="<?php echo $layout[\'layout_id\']; ?>"><?php echo $layout[\'name\']; ?></option>\';
	<?php } ?>
	html += \'    </select></td>\';
	html += \'    <td class="left"><select name="for_raplase_module[\' + module_row + \'][position]">\';
	html += \'      <option value="content_top"><?php echo $text_content_top; ?></option>\';
	html += \'      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>\';
	html += \'      <option value="column_left"><?php echo $text_column_left; ?></option>\';
	html += \'      <option value="column_right"><?php echo $text_column_right; ?></option>\';
	html += \'    </select></td>\';
	html += \'    <td class="left"><select name="for_raplase_module[\' + module_row + \'][status]">\';
    html += \'      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>\';
    html += \'      <option value="0"><?php echo $text_disabled; ?></option>\';
    html += \'    </select></td>\';
	html += \'    <td class="right"><input type="text" name="for_raplase_module[\' + module_row + \'][sort_order]" value="" size="3" /></td>\';
	html += \'    <td class="left"><a onclick="$(\\\'#module-row\' + module_row + \'\\\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>\';
	html += \'  </tr>\';
	html += \'</tbody>\';
	
	$(\'#module tfoot\').before(html);
	
	module_row++;
}
//--></script>';

$module_admin_template_text = str_replace("for_raplase", $modulename, $module_admin_template_text);

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

// Text
$_["text_module"]         = "Модули";
$_["text_success"]        = "Модуль '.$modulename.' успешно обновлен!";
$_["text_content_top"]    = "Содержание шапки";
$_["text_content_bottom"] = "Содержание подвала";
$_["text_column_left"]    = "Левая колонка";
$_["text_column_right"]   = "Правая колонка";

// Entry
$_["entry_layout"]        = "Схема:";
$_["entry_position"]      = "Расположение:";
$_["entry_status"]        = "Статус:";
$_["entry_sort_order"]    = "Порядок сортировки:";

// Error
$_["error_permission"]    = "У Вас нет прав для изменения модуля '.$modulename.'!";
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
