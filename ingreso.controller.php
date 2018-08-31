<?php

class Ingreso_Controller {

  var $messages = null;


  function main() {
    $tpl = new TemplatePower("templates/menu.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $tpl->newBlock("MENU_INS");
    $tpl->assign("op","Pelicula::altaPelicula");
    $tpl->assign("op-nom","Agregar Pelicula");
    $tpl->newBlock("MENU_LIST");
    $tpl->assign("op","Pelicula::listadoPeliculas");
    $tpl->assign("op-nom","Listado de peliculas");
    $tpl->newBlock("MENU_LIST");
    $tpl->assign("op","Pelicula::listadoxDirector");
    $tpl->assign("op-nom","Listado de peliculas por director");
    $tpl->newBlock("MENU_LIST");
    $tpl->assign("op","Pelicula::listadoxGenero");
    $tpl->assign("op-nom","Listado de peliculas por genero");
    $tpl->newBlock("MENU_LIST");
    $tpl->assign("op","Pelicula::listadoxActor");
    $tpl->assign("op-nom","Listado de peliculas por actor");
    $tpl->newBlock("MENU_INS");
    $tpl->assign("op","Pelicula::relActPel");
    $tpl->assign("op-nom","Relacionar Actor a Pelicula");
    $tpl->newBlock("MENU_INS");
    $tpl->assign("op","Pelicula::eliminarActorPelicula1");
    $tpl->assign("op-nom","Eliminar Actor de Pelicula");
    $tpl->newBlock("MENU_INS");
    $tpl->assign("op","Pelicula::insertarActor");
    $tpl->assign("op-nom","Agregar Actor");
    $tpl->newBlock("MENU_INS");
    $tpl->assign("op","Pelicula::insertarDirector");
    $tpl->assign("op-nom","Agregar Director");
    $tpl->newBlock("MENU_INS");
    $tpl->assign("op","Pelicula::eliminarActor");
    $tpl->assign("op-nom","Eliminar Actor");
    $tpl->newBlock("MENU_INS");
    $tpl->assign("op","Pelicula::eliminarDirector");
    $tpl->assign("op-nom","Eliminar Director");

    return $tpl->getOutputContent();
  }

 
}
