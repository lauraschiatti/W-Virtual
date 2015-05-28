<?php
namespace Clases;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministrarEquipos{

  private $app;

  public function __construct(Application $app){
    $this->app = $app;
  }
  
  // Crear equipos
  public function getIndex(){
    return $this->app['twig']->render('equipos/equipo.html');
  }

  public function getEquipos(){
    $equipos = \ORM::for_table('equipo')->find_array();
    return $this->app->json($equipos);
  }

  public function postEquipos(Request $request){
    $serial=$request->get("serial");
    $sala=$request->get("sala");
    $tipo=$request->get("tipo");

    $equipo = \ORM::for_table('equipo')
    ->select('equipo.*')
    ->where_equal('equipo.serial', $serial)
    ->find_one();

    if($equipo){
      return $this->app->json(array('error' => 'El equipo existe'), 409); 
    }else{
      $equipo = \ORM::for_table('equipo')->create();
      $equipo->sala= $sala;
      $equipo->serial= $serial;
      $equipo->tipo=$tipo;

      $equipo->save();
      return $this->app->json(array('mensaje' => 'El equipo fue agregada correctamente')); 
    }
  }

  public function deleteEquipos($id, Request $request){ 
    $equipo = \ORM::for_table('equipo')
    ->select('equipo.*')
    ->find_one($id);
    if($equipo){
      $equipo->delete();
      return $this->app->json(array('mensaje' => 'El equipo fue eliminado correctamente')); 

    }else{        
        return $this->app->json(array('error' => 'El equipo no fue eliminado'), 409);         
    }
  }

   // reservas
  public function getReservaIndex(){
    return $this->app['twig']->render('equipos/reserva_equipo.html');
  }

  // editar
  public function getReserva(Request $request){
    $username=$request->query->get("username");
    if($username){
      $equipos = \ORM::for_table('reservaequipo')->where('usuario',$username)->find_array();    
      return $this->app->json($equipos);
    }else{
      $equipos = \ORM::for_table('reservaequipo')->find_array();    
      return $this->app->json($equipos);
    }
  }

  public function postReserva(Request $request){
    $serial=$request->get("serial");
    $fecha=$request->get("fecha");
    $hora=$request->get("hora");
    // $usuario="usuario";
    // sesion
    $usuario = $this->app['session']->get('user');

    $reserva = \ORM::for_table('reservaequipo')
    ->select('reservaequipo.*')
    ->where_equal('reservaequipo.serial', $serial)
    ->where_equal('reservaequipo.hora', $hora)
    ->where_equal('reservaequipo.fecha', $fecha)
    ->find_one();

    // $gestor = fopen("debug.txt", "w+");
    // fwrite($gestor, json_encode($reserva->as_array()) . "\n");
    // fwrite($gestor, $serial . ' ' . $hora . ' ' . $fecha . "\n");

    if($reserva){    
      return $this->app->json(array('error' => 'No disponible equipo en esa fecha y hora'), 409);  
    }else{
      $reserva = \ORM::for_table('reservaequipo')->create();
      $reserva->serial= $serial;
      $reserva->fecha= $fecha;
      $reserva->hora= $hora;
      $reserva->usuario= $usuario['username'];
      
      $reserva->save();

      return $this->app->json(array('mensaje' => 'La reserva se realizo correctamente', 'id' => $reserva->id));    
    }
  }

  // public function putSalas($id, Request $request){
  //   $nombre=$request->get("nombre");
  //   $capacidad=$request->get("capacidad");

  //   $sala = \ORM::for_table('sala')
  //   ->select('sala.*')
  //   //->where_equal('sala.nombre', $nombre)
  //   ->find_one($id);
  //   if($sala){
  //     $sala->nombre = $nombre;
  //     $sala->capacidad = $capacidad;
      
  //     $sala->save();
  //     return $this->app->json(array('error' => 'Los datos de la sala fueron actualizados correctamente')); 

  //   }else{        
  //       return $this->app->json(array('error' => 'La sala no fue actualizada'), 409);         
  //   }
  // }


}