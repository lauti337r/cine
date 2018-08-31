<?php

class Pelicula_Controller {

  var $messages = null;

  //VUELVE AL MENU
  function returntomenu(){
    $ing = new Ingreso_Controller();
    return $ing->main();
  }

  ///-/-/->   LISTADOS   <-\-\-
  //LISTA TODAS LAS PELICULAS EN ORDEN
  function listadoPeliculas() {
    $tpl = new TemplatePower("templates/listadoPeliculas.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");

    $mod = new Pelicula_Model();
    $res = $mod->listarPeliculas();
    $mod->borrarActorPelicula(28,288);

    if($res){
      $tpl->gotoBlock("_ROOT");
      foreach($res as $fila){
        $tpl->newBlock("LISTADO");
        $tpl->assign("titulo",$fila['pe_nombre']);
        $tpl->assign("genero",$fila['ge_nombre']);
        $tpl->assign("fechaestreno",$fila['pe_fechaEstreno']);
        $tpl->assign("director",$fila['di_nombreArtistico']);
        $tpl->assign("duracion",$fila['pe_duracion']);
        $tpl->assign("id",$fila['id_pelicula']);
        $actores = $mod->listarActores($fila['id_pelicula']);
        $ac = "";
        foreach ($actores as $actor) {
          $ac .= $actor['ac_nombreArtistico'] . ", ";
        }
        $ac = substr($ac,0,-2);
        $tpl->assign("actores",$ac);
      }
    }

    return $tpl->getOutputContent();
  }
  //LISTA TODAS LAS PELICULAS POR DIRECTOR
  function listadoxDirector(){
    $tpl = new TemplatePower("templates/listadoxDirector.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $mod = new Pelicula_Model();
    $res = $mod->listarDirectores();

    if($res){
      $tpl->gotoBlock("_ROOT");
      foreach($res as $dir){
        $tpl->newBlock("LISTADO_DIR");
        $tpl->assign("director",$dir['di_nombreArtistico']);
        $res_pel = $mod->peliculasPorDirector($dir['id_director']);
        foreach($res_pel as $pel){
          $tpl->newBlock("LISTADO_PEL");
          $tpl->assign("titulo",$pel['pe_nombre']);
        }
      }
    }

    return $tpl->getOutputContent();

  }
  //LISTA TODAS LAS PELICULAS POR GENERO
  function listadoxGenero(){
    $tpl = new TemplatePower("templates/listadoxGenero.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $mod = new Pelicula_Model();
    $res = $mod->listarGeneros();

    if($res){
      $tpl->gotoBlock("_ROOT");
      foreach($res as $gen){
        $tpl->newBlock("LISTADO_GEN");
        $tpl->assign("genero",$gen['ge_nombre']);
        $res_pel = $mod->peliculasPorGenero($gen['id_genero']);
        foreach($res_pel as $pel){
          $tpl->newBlock("LISTADO_PEL");
          $tpl->assign("titulo",$pel['pe_nombre']);
        }
      }
    }

    return $tpl->getOutputContent();
  }
  //LISTA TODAS LAS PELICULAS POR ACTOR
  function listadoxActor(){
    $tpl = new TemplatePower("templates/listadoxActor.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $mod = new Pelicula_Model();
    $res = $mod->listarActoresTotal();

    if($res){
      $tpl->gotoBlock("_ROOT");
      foreach($res as $actor){
        $tpl->newBlock("LISTADO_ACT");
        $tpl->assign("actor",$actor['ac_nombreArtistico']);
        $res_pel = $mod->peliculasPorActor($actor['id_actor']);
        foreach($res_pel as $pel){
          $tpl->newBlock("LISTADO_PEL");
          $tpl->assign("titulo", $pel['pe_nombre']);
        }
      }
    }

    return $tpl->getOutputContent();
  }

  ////-/-/->   PELICULA   <-\-\-
  ///INSERCION
  //CREA EL TEMPLATE DE altaPelicula.html
  function altaPelicula() {
    $tpl = new TemplatePower("templates/altaPelicula.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $mod = new Pelicula_Model();
    $res_d = $mod->listarDirectores();

    if($res_d){
      $tpl->gotoBlock("_ROOT");
      foreach($res_d as $director){
        $tpl->newBlock("DIRECTORES");
        $tpl->assign("director",$director['di_nombreArtistico']);
      }
    }

    $res_g = $mod->listarGeneros();

    if($res_g){
      $tpl->gotoBlock("_ROOT");
      foreach($res_g as $genero){
        $tpl->newBlock("GENEROS");
        $tpl->assign("genero",$genero['ge_nombre']);
      }
    }

    return $tpl->getOutputContent();
  }
  //LLAMA A LA FUNCION DEL MODEL
  function agregarPelicula(){
    $idGen = $this->idGenero($_REQUEST['genero']);
    $idDir = $this->idDirector($_REQUEST['director']);
    $titulo = $_REQUEST['nombrePelicula'];
    $duracion = $_REQUEST['duracion'];
    $fecha_estreno = $_REQUEST['fechaEstreno'];
    $mod = new Pelicula_Model();
    $mod->insertarPelicula($idGen, $idDir, $titulo, $duracion,$fecha_estreno);

    return $this->listadoPeliculas();
  }
  ///BORRAR PELICULA
  //LLAMA A LA FUNCION DEL MODEL
  function borrarPelicula(){
    $mod = new Pelicula_Model();
    $mod->borrarPelicula($_REQUEST['idPel']);

    return $this->listadoPeliculas();
  }
  ///EDICION
  //CREA EL TEMPLATE DE editarPelicula.html
  function editarPelicula(){
    $tpl = new TemplatePower("templates/editarPelicula.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $idPel = $_REQUEST['idPel'];

    $mod = new Pelicula_Model();
    $datos = $mod->datosPelicula($idPel);

    $tpl->assign("id",$datos['id_pelicula']);
    $tpl->assign("titulo",$datos['pe_nombre']);
    $tpl->assign("duracion",$datos['pe_duracion']);
    $tpl->assign("fechaEstreno",$datos['pe_fechaEstreno']);

    $res_g = $mod->listarGeneros();
    if($res_g){
      foreach($res_g as $gen){
        if($datos['id_genero']==$gen['id_genero']) {
          $tpl->gotoBlock("_ROOT");
          $tpl->assign("genero_ac", $gen['ge_nombre']);
        }
        else{
          $tpl->newBlock("GENEROS");
          $tpl->assign("genero", $gen['ge_nombre']);
        }
      }
    }

    $res_d = $mod->listarDirectores();
    if($res_d) {
      foreach ($res_d as $dir) {
        if ($datos['id_director'] == $dir['id_director']) {
          $tpl->gotoBlock("_ROOT");
          $tpl->assign("director_ac", $dir['di_nombreArtistico']);
        }
        else{
          $tpl->newBlock("DIRECTORES");
          $tpl->assign("director", $dir['di_nombreArtistico']);
        }
      }
    }

    return $tpl->getOutputContent();
  }
  //LLAMA A LA FUNCION DEL MODEL
  function editPel(){
    $mod = new Pelicula_Model();
    $idGen = $this->idGenero($_REQUEST['genero']);
    $idDir = $this->idDirector($_REQUEST['director']);
    $mod->editarPelicula($_REQUEST['idPel'],$idGen,$idDir,$_REQUEST['nombrePelicula'],$_REQUEST['duracion'],$_REQUEST['fechaEstreno']);

    return $this->listadoPeliculas();
  }

  ////-/-/->   DIRECTOR   <-\-\-
  ///INSERCION
  //CREA EL TEMPLATE DE insertarDirector
  function insertarDirector(){
    $tpl = new TemplatePower("templates/insertarDirector.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");

    return $tpl->getOutputContent();
  }
  //LLAMA A LA FUNCION DEL MODEL
  function agregarDirector(){
    $mod = new Pelicula_Model();
    $mod->insertarDirector($_REQUEST['nombreArtista'],$_REQUEST['apellidoArtista'],$_REQUEST['dniArtista'],
        $_REQUEST['emailArtista'],$_REQUEST['nombreArtistico'] );

    return $this->returntomenu();
  }
  ///BORRAR ACTOR
  //CREA EL TEMPLATE DE eliminarDirector.html
  function eliminarDirector(){
    $tpl = new TemplatePower("templates/eliminarDirector.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $mod = new Pelicula_Model();
    $res = $mod->listarDirectores();

    if($res){
      foreach($res as $director){
        $tpl->newBlock("DIRECTORES");
        $tpl->assign("id_director",$director['id_director']);
        $tpl->assign("director",$director['di_nombreArtistico']);
      }
    }

    return $tpl->getOutputContent();
  }
  //LLAMA A LA FUNCION DEL MODEL
  function elimDirector(){
    $mod = new Pelicula_Model();
    $mod->eliminarDirector($_REQUEST['director']);

    return $this->returntomenu();
  }
  
  ////-/-/->   ACTOR   <-\-\-
  ///INSERCION
  //CREA EL TEMPLATE DE insertarActor.html
  function insertarActor(){
    $tpl = new TemplatePower("templates/insertarActor.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");

    return $tpl->getOutputContent();
  }
  //LLAMA A LA FUNCION DEL MODEL
  function agregarActor(){
    $mod = new Pelicula_Model();
    $mod->insertarActor($_REQUEST['nombreArtista'],$_REQUEST['apellidoArtista'],$_REQUEST['dniArtista'],
        $_REQUEST['emailArtista'],$_REQUEST['nombreArtistico'] );

    return $this->returntomenu();
  }
  ///--->BORRAR ACTOR
  //CREA EL TEMPLATE DE eliminarActor.html
  function eliminarActor(){
    $tpl = new TemplatePower("templates/eliminarActor.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $mod = new Pelicula_Model();
    $res = $mod->listarActoresTotal();

    if($res){
      foreach($res as $actor){
        $tpl->newBlock("ACTORES");
        $tpl->assign("id_actor",$actor['id_actor']);
        $tpl->assign("actor",$actor['ac_nombreArtistico']);
      }
    }

    return $tpl->getOutputContent();
  }
  //LLAMA A LA FUNCION DEL MODEL
  function elimActor(){
    $mod = new Pelicula_Model();
    $mod->eliminarActor($_REQUEST['actor']);

    return $this->returntomenu();
  }
  
  ////-\-\->   PELICULA_ACTOR   <-\-\-
  ///CREAR RELACION ACTOR_PELICULA
  //CREA EL TEMPLATE DE relActPel.html
  function relActPel(){
    $tpl = new TemplatePower("templates/relActPel.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");

    $mod = new Pelicula_Model();
    $res_a = $mod->listarActoresTotal();
    $res_p = $mod->listarPeliculas();

    if($res_a){
      foreach ($res_a as $actor){
        $tpl->newBlock("ACTORES");
        $tpl->assign("id_actor",$actor['id_actor']);
        $tpl->assign("actor",$actor['ac_nombreArtistico']);
      }
    }

    if($res_p){
      foreach ($res_p as $pelicula){
        $tpl->newBlock("PELICULAS");
        $tpl->assign("id_pelicula",$pelicula['id_pelicula']);
        $tpl->assign("pelicula",$pelicula['pe_nombre']);
      }
    }

    return $tpl->getOutputContent();

  }
  //LLAMA A LA FUNCION DEL MODEL
  function relacionarActorPelicula(){
    $mod = new Pelicula_Model();
    $mod->insertarActoraPelicula($_REQUEST['actor'],$_REQUEST['pelicula']);

    return $this->relActPel();
  }
  ///ELIMINAR RELACION ACTOR_PELICULA
  //CREA EL TEMPLATE DE elimActPel1.html
  function eliminarActorPelicula1(){
    $tpl = new TemplatePower("templates/elimActPel1.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $mod = new Pelicula_Model();
    $res_p = $mod->listarPeliculas();

    if($res_p){
      foreach ($res_p as $pelicula){
        $tpl->newBlock("PELICULAS");
        $tpl->assign("id_pelicula",$pelicula['id_pelicula']);
        $tpl->assign("pelicula",$pelicula['pe_nombre']);
      }
    }

    return $tpl->getOutputContent();
  }
  //CREA EL TEMPLATE DE elimActPel2.html
  function eliminarActorPelicula2(){
    $tpl = new TemplatePower("templates/elimActPel2.html");
    $tpl->prepare();
    $tpl->gotoBlock("_ROOT");
    $tpl->assign("id",$_REQUEST['pelicula']);
    $mod = new Pelicula_Model();
    $res_a = $mod->listarActores($_REQUEST['pelicula']);

    if($res_a){
      foreach ($res_a as $actor){
        $tpl->newBlock("ACTORES");
        $tpl->assign("id_actor",$actor['id_actor']);
        $tpl->assign("actor",$actor['ac_nombreArtistico']);
      }
    }

    return $tpl->getOutputContent();
  }
  //LLAMA A LA FUNCION DEL MODEL
  function elimActPel(){
    $mod = new Pelicula_Model();
    $mod->borrarActorPelicula($_REQUEST['idPel'],$_REQUEST['actor']);

    return $this->returntomenu();
  }

  ////-/-/->   FUNCIONES VARIAS   <-\-\-
  //DEVUELVE EL ID DE LA PELICULA $nomPel
  function idPel($nomPel){
    $mod = new Pelicula_Model();
    return $mod->idPel($nomPel);
  }
  //DEVUELVE EL ID DEL ACTOR $nomArtistico
  function idActor($nomArtistico){
    $mod = new Pelicula_Model();
    return $mod->idActor($nomArtistico);
  }
  //DEVUELVE EL ID DEL DIRECTOR $nomArtistico
  function idDirector($nomArtistico){
    $mod = new Pelicula_Model();
    return $mod->idDirector($nomArtistico);
  }
  //DUVUELVE EL ID DEL GENERO $genero
  function idGenero($genero){
    $mod = new Pelicula_Model();
    return $mod->idGenero($genero);
  }
}