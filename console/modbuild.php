#!/usr/bin/php
<?php
// Language
require_once 'lang/ru_b.php';

echo "\n=====================\n";
echo $_lang['title']."\n";
echo "@TvorZasp\n";
echo "=====================\n";

// Settings
$theme_std = "default";
$language_std = "english";
$folders_std = dirname(dirname(dirname(__FILE__)));

// Questions
do{
	echo $_lang['module_name'] . ":";
	$modulename = trim(fgets(STDIN)); // читаем строку из STDIN
	if($modulename){
		break;
	}
}while (1);

echo $_lang['shop_folder'] . "(".$_lang['standart']." '".$folders_std."'):"; 
$folders = trim(fgets(STDIN)); 
if(!$folders){
	$folders = $folders_std; 
}

echo $_lang['theme_name']."(".$_lang['standart']." '".$theme_std."'):"; 
$theme = trim(fgets(STDIN));
if(!$theme){
	$theme = $theme_std; 
}

echo $_lang['language']."(".$_lang['standart']." '".$language_std."'):";  
$language = trim(fgets(STDIN)); 
if(!$language){
	$language = $language_std; 
}

$pack_module_folder_std = dirname(dirname(__FILE__))."/build/".$modulename."/";

echo $_lang['module_folder'] . "(".$_lang['standart']." '".$pack_module_folder_std."'):"; 
$pack_module_folder = trim(fgets(STDIN)); 
if(!$pack_module_folder){
	$pack_module_folder = $pack_module_folder_std; 
}

@mkdir($pack_module_folder);


// Admin controller
@mkdir($pack_module_folder."/admin/");
@mkdir($pack_module_folder."/admin/controller/");
@mkdir($pack_module_folder."/admin/controller/module/");
copy($folders."/admin/controller/module/".$modulename.".php", $pack_module_folder."/admin/controller/module/".$modulename.".php");

// Admin language
@mkdir($pack_module_folder."/admin/language/");
@mkdir($pack_module_folder."/admin/language/".$language);
@mkdir($pack_module_folder."/admin/language/".$language."/module/");
copy($folders."/admin/language/".$language."/module/".$modulename.".php", $pack_module_folder."/admin/language/".$language."/module/".$modulename.".php");

// Admin model
@mkdir($pack_module_folder."/admin/model/");
@mkdir($pack_module_folder."/admin/model/module/");
copy($folders."/admin/model/module/".$modulename.".php", $pack_module_folder."/admin/model/module/".$modulename.".php");

// Admin view
@mkdir($pack_module_folder."/admin/view/");
@mkdir($pack_module_folder."/admin/view/template/");
@mkdir($pack_module_folder."/admin/view/template/module/");
copy($folders."/admin/view/template/module/".$modulename.".tpl", $pack_module_folder."/admin/view/template/module/".$modulename.".tpl");

// Catalog controller
@mkdir($pack_module_folder."/catalog/");
@mkdir($pack_module_folder."/catalog/controller/");
@mkdir($pack_module_folder."/catalog/controller/module/");
copy($folders."/catalog/controller/module/".$modulename.".php", $pack_module_folder."/catalog/controller/module/".$modulename.".php");

// Catalog language
@mkdir($pack_module_folder."/catalog/language/");
@mkdir($pack_module_folder."/catalog/language/".$language);
@mkdir($pack_module_folder."/catalog/language/".$language."/module/");
copy($folders."/catalog/language/".$language."/module/".$modulename.".php", $pack_module_folder."/catalog/language/".$language."/module/".$modulename.".php");

// Catalog model
@mkdir($pack_module_folder."/catalog/model/");
@mkdir($pack_module_folder."/catalog/model/module/");
copy($folders."/catalog/model/module/".$modulename.".php", $pack_module_folder."/catalog/model/module/".$modulename.".php");

// Catalog view
@mkdir($pack_module_folder."/catalog/view/");
@mkdir($pack_module_folder."/catalog/view/theme/");
@mkdir($pack_module_folder."/catalog/view/theme/" . $theme ."/" );
@mkdir($pack_module_folder."/catalog/view/theme/" . $theme ."/template/");
@mkdir($pack_module_folder."/catalog/view/theme/" . $theme ."/template/module/");
copy($folders."/catalog/view/theme/" . $theme ."/template/module/".$modulename.".tpl", $pack_module_folder."/catalog/view/theme/" . $theme ."/template/module/".$modulename.".tpl");

// Add a Components
//if($components){
//	@mkdir($pack_module_folder."/system/");
//	@mkdir($pack_module_folder."/system/library/");
//	
//	copy($folders."/system/library/component.php", $pack_module_folder."/system/library/component.php");
//	
//	$to = $pack_module_folder."/components/";
//	$from = $folders."/components/";
//	
//	rec_copy($from, $to);
//}

// Add ORM files
//if($orm){
//	@mkdir($pack_module_folder."/system/");
//	@mkdir($pack_module_folder."/system/library/");
//	
//	$to = $pack_module_folder."/system/library/crystal/";
//	$from = $folders."/system/library/crystal/";
//	
//	rec_copy($from, $to);
//}

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