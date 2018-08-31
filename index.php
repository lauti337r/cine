<?php

//===========================================================================================================
// OPEN SESSION |
//---------------
session_start();

//===========================================================================================================
// INCLUDES |
//-----------

include("inc.includes.php");


//===========================================================================================================
// OPEN CONNECTION |
//------------------

if ($config["dbEngine"]=="MYSQL"){
	$mysqli = new mysqli($config["dbhost"],$config["dbuser"],$config["dbpass"],$config["db"]);
}
//var_dump($db);

//===========================================================================================================
// INSTANCIA CLASES Y METODOS |
//-----------------------------

if ((!isset($_REQUEST["action"])) || ($_REQUEST["action"] == "")) {
  $_REQUEST["action"] = "Ingreso::main";
}
if ($_REQUEST["action"] == "") {
  $html = "";
} else {
  if (!strpos($_REQUEST["action"], "::")) {
    $_REQUEST["action"].="::menu";
  }
  list($classParam, $method) = explode('::', $_REQUEST["action"]);
  if ($method == "") {
    $method = "main";
  }
  $classToInstaciate = $classParam . "_Controller";
  if (class_exists($classToInstaciate)) {
    if (method_exists($classToInstaciate, $method)) {
      $claseTemp = new $classToInstaciate;
      $html = call_user_func_array(array($claseTemp, $method), array());
    } else {
      $html = "No tiene permitido acceder a ese contenido.";
    }
  } else {
    $html = "La p&aacute;gina solicitada no est&aacute; disponible.";
  }
}

//===========================================================================================================
// INSTANCIA TEMPLATE |
//---------------------

	$tpl = new TemplatePower("recursos/_html/index.html");
	$tpl->prepare();
	$tpl->assign("fecha_completa",date("d-m-y"));
	$tpl->assign("aplicacion","Cine");	
	
//===========================================================================================================
// LEVANTA TEMPLATE	|
//-------------------		

	$tpl->gotoBlock("_ROOT");
	$tpl->assign("contenido",$html);
	$html=$tpl->getOutputContent();
	echo $html;
?>