<?php

/**
 * Created by PhpStorm.
 * User: lau_3
 * Date: 04/06/2016
 * Time: 11:41
 */
class Pelicula_Model {

    ////-/-/->   LISTADOS   <-\-\-
    //LISTA TODAS LAS PELICULAS
    function listarPeliculas(){
        global $mysqli;
        $sqlConsulta="SELECT p.id_pelicula, p.pe_nombre, g.ge_nombre, d.di_nombreArtistico, p.pe_duracion, p.pe_fechaEstreno
                      FROM pelicula AS p, director AS d, genero AS g 
                      WHERE g.id_genero=p.id_genero AND d.id_director=p.id_director
                      ORDER BY p.id_pelicula";

        $result = $mysqli->query($sqlConsulta);
        return $result;
    }
    //LISTA LOS ACTORES DE LA PELICULA $idPel
    function listarActores($idPel){
        global $mysqli;
        $sqlConsulta = "SELECT a.ac_nombreArtistico, a.id_actor FROM pelicula_actor AS pa, actor AS a 
                        WHERE a.id_actor = pa.id_actor AND pa.id_pelicula=$idPel";
        $result = $mysqli->query($sqlConsulta);
        return $result;
    }
    //LISTA LAS PELICULAS DEL DIRECTOR $idDir
    function peliculasPorDirector($idDir){
        global $mysqli;
        $sqlConsulta = "SELECT pe_nombre FROM pelicula WHERE id_director=$idDir";
        return $mysqli->query($sqlConsulta);
    }
    //LISTA LAS PELICULAS DEL GENERO $idGen
    function peliculasPorGenero($idGen){
        global $mysqli;
        $sqlConsulta = "SELECT pe_nombre FROM pelicula WHERE id_genero=$idGen";
        return $mysqli->query($sqlConsulta);
    }
    //LISTA TODOS LOS ACTORES
    function listarActoresTotal(){
        global $mysqli;
        $result = $mysqli->query("SELECT id_actor,ac_nombreArtistico FROM actor");
        return $result;
    }
    //LISTA LOS DATOS DE LA PELICULA $idPel
    function datosPelicula($idPel){
        global $mysqli;
        $result = $mysqli->query("SELECT * FROM pelicula WHERE id_pelicula=$idPel");
        return $result->fetch_assoc();
    }
    //LISTA TODOS LOS DIRECTORES
    function listarDirectores(){
        global $mysqli;
        $result = $mysqli->query("SELECT di_nombreArtistico,id_director FROM director");
        return $result;
    }
    //LISTA TODOS LOS GENEROS
    function listarGeneros(){
        global $mysqli;
        $result = $mysqli->query("SELECT ge_nombre,id_genero FROM genero");
        return $result;
    }
    //LISTA LAS PELICULAS DEL ACTOR $idActor
    function peliculasPorActor($idActor){
        global $mysqli;
        $query = "SELECT p.pe_nombre FROM pelicula_actor AS pa, pelicula AS p WHERE pa.id_pelicula = p.id_pelicula AND pa.id_actor=$idActor";
        return $mysqli->query($query);
    }

    ////-/-/->   INSERCION   <-\-\-
    //INSERTA PELICULA CON LOS DATOS DADOS
    function insertarPelicula($idGen,$idDir,$titulo,$duracion,$estreno){
        global $mysqli;
        $query = "INSERT INTO  pelicula(id_pelicula,id_genero,id_director,pe_nombre,pe_duracion,pe_fechaEstreno) 
                        VALUES (NULL,$idGen,$idDir,\"$titulo\",$duracion,\"$estreno\")";
        $mysqli->query($query);
        return $mysqli->insert_id;
    }
    //CREA LA RELACION ENTRE $idActor Y $idPel
    function insertarActoraPelicula($idAct, $idPel){
        global $mysqli;
        $sqlConsulta = "INSERT INTO pelicula_actor (id_pelicula, id_actor) VALUES ($idPel,$idAct)";
        $result = $mysqli->query($sqlConsulta);
        return $result;
    }
    //INSERTA EL ACTOR CON LOS DATOS DADOS (INSERTANDO PRIMERO EL ARTISTA)
    function insertarActor($nombre, $apellido, $dni, $mail, $nombreArtistico) {
        global $mysqli;
        $sqlArtista = 'INSERT INTO artista (id_artista, ar_nombre, ar_apellido, ar_dni, ar_mail) VALUES (NULL,"' . $nombre . '","' . $apellido . '","' . $dni . '","' . $mail . '")';
        $mysqli->query($sqlArtista);
        $idArtista = $mysqli->insert_id;
        if($idArtista){
            $sqlActor = 'INSERT INTO actor (id_actor, id_artista, ac_nombreArtistico) VALUES (NULL,"' . $idArtista . '","' . $nombreArtistico . '")';
            $mysqli->query($sqlActor);
            $idArtista = $mysqli->insert_id;
        }
        return $idArtista;
    }
    //INSERTA EL DIRECTOR CON LOS DATOS DADOS (INSERTANDO PRIMERO EL ARTISTA)
    function insertarDirector($nombre, $apellido, $dni, $mail, $nombreArtistico){
        global $mysqli;
        $sqlArtista = 'INSERT INTO artista (id_artista, ar_nombre, ar_apellido, ar_dni, ar_mail) VALUES (NULL,"' . $nombre . '","' . $apellido . '","' . $dni . '","' . $mail . '")';
        $mysqli->query($sqlArtista);
        $idArtista = $mysqli->insert_id;
        if($idArtista){
            $sqlDirector = 'INSERT INTO director (id_director, id_artista, di_nombreArtistico) VALUES (NULL, '.$idArtista.', "'.$nombreArtistico.'")';
            $mysqli->query($sqlDirector);
            if($mysqli->insert_id)return $mysqli->insert_id;
        }
        return "DIRECTOR EXISTENTE";
    }

    ////-/-/->   EDICION   <-\-\-
    //EDITA LOS DATOS DE LA PELICULA $idPel CON LOS DATOS DADOS
    function editarPelicula($idPel,$idGen,$idDir,$titulo,$duracion,$estreno){
        global $mysqli;
        $query = "UPDATE pelicula SET id_genero=$idGen,id_director=$idDir,pe_nombre=\"$titulo\",pe_duracion=$duracion,
                  pe_fechaEstreno=\"$estreno\" WHERE id_pelicula=$idPel";
        $mysqli->query($query);
    }

    ////-/-/->   SUPRESION   <-\-\-
    //BORRA LA PELICULA $idPel
    function borrarPelicula($idPel) {
        global $mysqli;
        $sqlConsulta="DELETE FROM pelicula_actor WHERE id_pelicula=$idPel";
        $mysqli->query($sqlConsulta);
        $sqlConsulta="DELETE FROM pelicula WHERE id_pelicula=$idPel";
        $result=$mysqli->query($sqlConsulta);
        return $result;
    }
    //ELIMINA LA RELACION ENTRE LA PELICULA $idPel Y EL ACTOR $idAct
    function borrarActorPelicula($idPel,$idAct){
        if($idPel!="ALL" && $idAct!="ALL"){
            global $mysqli;
            $query = "DELETE FROM pelicula_actor WHERE id_actor=$idAct AND id_pelicula=$idPel";
            $mysqli->query($query);
        }else if($idPel=="ALL"){
            global $mysqli;
            $query = "DELETE FROM pelicula_actor WHERE id_actor=$idAct";
            $mysqli->query($query);
        }else if($idAct=="ALL"){
            global $mysqli;
            $query = "DELETE FROM pelicula_actor WHERE id_pelicula=$idPel";
            $mysqli->query($query);
        }
    }
    //ELIMINA EL ACTOR $idActor
    function eliminarActor($idAct){
        global $mysqli;
        $this->borrarActorPelicula("ALL",$idAct);
        $idArt = $this->idArtAct($idAct)['id_artista'];
        $query = "DELETE FROM actor WHERE id_actor=$idAct";
        $mysqli->query($query);
        $query = "DELETE FROM artista WHERE id_artista=$idArt";
        $mysqli->query($query);
    }
    //ELIMINA EL DIRECTOR $idDirector
    function eliminarDirector($idDir){
        global $mysqli;
        $res = $mysqli->query("SELECT id_pelicula FROM pelicula WHERE id_director=$idDir");
        foreach($res as $pel){
            $this->borrarPelicula($pel['id_pelicula']);
        }
        $idArt = $this->idArtDir($idDir)['id_artista'];
        $query = "DELETE FROM director WHERE id_director=$idDir";
        $mysqli->query($query);
        $query = "DELETE FROM artista WHERE id_artista=$idArt";
        $mysqli->query($query);
    }
    //ELIMINA TODAS LAS RELACIONES ACTORES CON LA PELICULA $idPel (USADA POR eliminarPelicula($idPel))
    function eliminarActorPel($idPel){
        global $mysqli;
        $mysqli->query("DELETE FROM pelicula_actor WHERE id_pelicula=$idPel");
    }

    ////-/-/->   CONSULTA   <-\-\-
    //DEVUELVE EL id DE LA PELICULA $nomPel
    function idPel($nomPel){
        global $mysqli;
        $result = $mysqli->query('SELECT id_pelicula FROM pelicula WHERE no_pelicula="' . $nomPel . '"');
        return $result->fetch_row()[0];
    }
    //DEVUELVE EL id DEL ACTOR $nomArtistico
    function idActor($nomArtistico){
        global $mysqli;
        $result = $mysqli->query('SELECT id_actor FROM actor WHERE  ac_nombreArtistico="' . $nomArtistico . '"');
        return $result->fetch_row()[0];
    }
    //DEVUELVE EL id DEL DIRECTOR $nomArtistico
    function idDirector($nomArtistico){
        global $mysqli;
        $result = $mysqli->query('SELECT id_director FROM director WHERE di_nombreArtistico="' . $nomArtistico . '"');

        return $result->fetch_row()[0];
    }
    //DEVUELVE EL id DEL GENERO $genero
    function idGenero($genero){
        global $mysqli;
        $result = $mysqli->query('SELECT id_genero FROM genero WHERE ge_nombre="' . $genero . '"');
        return $result->fetch_row()[0];
    }
    //DEVUELVE EL id de artista DEL ACTOR $idActor
    function idArtAct($idAct){
        global $mysqli;
        return $mysqli->query("SELECT id_artista FROM actor WHERE id_actor=$idAct")->fetch_assoc();
    }
    //DEVUELVE EL id de artista DEL DIRECTOR $idDir
    function idArtDir($idDir){
        global $mysqli;
        return $mysqli->query("SELECT id_artista FROM director WHERE id_director=$idDir")->fetch_assoc();
    }
}

