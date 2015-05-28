<?php
namespace Clases;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministrarSalas{

  private $app;

  public function __construct(Application $app){
    $this->app = $app;
  }
  // Crear salas
  public function getIndex(){
    return $this->app['twig']->render('salas/sala.html');
  }

  public function getSalas(){    
    $salas = \ORM::for_table('sala')->find_array();    
    return $this->app->json($salas);    
  }

  public function postSalas(Request $request){
    $nombre=$request->get("nombre");
    $capacidad=$request->get("capacidad");

    $sala = \ORM::for_table('sala')
    ->select('sala.*')
    ->where_equal('sala.nombre', $nombre)
    ->find_one();
    if($sala){
      return $this->app->json(array('error' => 'La sala existe'), 409); 
    }else{
      $sala = \ORM::for_table('sala')->create();
      $sala->nombre= $nombre;
      $sala->capacidad= $capacidad;

      $sala->save();
      return $this->app->json(array('mensaje' => 'La sala fue agregada correctamente')); 
    }
  }

  public function deleteSalas($id, Request $request){ 
    $sala = \ORM::for_table('sala')
    ->select('sala.*')
    ->find_one($id);
    if($sala){
      $sala->delete();
      return $this->app->json(array('error' => 'La sala fue eliminada correctamente')); 

    }else{        
        return $this->app->json(array('mensaje' => 'La sala no fue eliminada'), 409);         
    }
  }

  // reservas
  public function getReservaIndex(){
    return $this->app['twig']->render('salas/reserva_sala.html');
  }

  public function getReserva(Request $request){
    $username=$request->query->get("username");
    if($username){
      $salas = \ORM::for_table('reservasala')->where('usuario',$username)->find_array();    
      return $this->app->json($salas);
    }else{
      $salas = \ORM::for_table('reservasala')->find_array();    
      return $this->app->json($salas);
    }
  }

  public function postReserva(Request $request){
    $nombre=$request->get("nombre");
    $fecha=$request->get("fecha");
    $hora=$request->get("hora");
    // $usuario="usuario";
    // sesion
    $usuario = $this->app['session']->get('user');

    $reserva = \ORM::for_table('reservasala')
    ->select('reservasala.*')
    ->where_equal('reservasala.nombre', $nombre)
    ->where_equal('reservasala.hora', $hora)
    ->where_equal('reservasala.fecha', $fecha)
    ->find_one();

    if($reserva and $reserva->hora==$hora and $reserva->fecha==$fecha){    
      return $this->app->json(array('error' => 'No disponible sala en esa fecha y hora'), 409);  
    }else{
      $reserva = \ORM::for_table('reservasala')->create();
      $reserva->nombre= $nombre;
      $reserva->fecha= $fecha;
      $reserva->hora= $hora;
      $reserva->usuario= $usuario['username'];
      
      $reserva->save();

      return $this->app->json(array('mensaje' => 'La reserva se realizo correctamente', 'id' => $reserva->id));  
    }
  }
  

}