<?php
namespace Clases;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministrarUsuarios{

  private $app;

  public function __construct(Application $app){
    $this->app = $app;
  }

  public function getLogin(){
    return $this->app['twig']->render('usuarios/login.html');
  }

  public function postLogin(Request $request){
    $email=$request->get("email");
    $clave=$request->get("clave");
    
    $usuario = \ORM::for_table('usuario')
    ->select('usuario.*')
    ->where_equal('usuario.email', $email)
    ->find_one();
    
    if($usuario and $usuario->clave==$clave){
      // sesion
      $this->app['session']->set('user', array('username' => $usuario->email)); 
      if($usuario->tipo=="user"){
        return $this->app->redirect('inicio');
      }else{
        return $this->app->redirect('inicioadmin');
      }
    }else{
       return $this->app->redirect('login');
    }    
  }

  // Crear usuarios
  public function getUsuarios(){
    $usuarios = \ORM::for_table('usuario')->find_array();
    return $this->app->json($usuarios);
  }

  public function postUsuarios(Request $request){
    $tipo="user";
    $email=$request->get('email');
    $clave=$request->get("clave");
    $nombre=$request->get("nombre");
    $apellido=$request->get("apellido");

    $usuario = \ORM::for_table('usuario')
    ->select('usuario.*')
    ->where_equal('usuario.email', $email)
    ->find_one();
    if($usuario){
      return $this->app->json(array('error' => 'El usuario existe'), 409); 
    }else{
      $usuario = \ORM::for_table('usuario')->create();
      $usuario->email= $email;
      $usuario->clave= $clave;
      $usuario->tipo= $tipo;        
      $usuario->nombre= $nombre;
      $usuario->apellido= $apellido;

      $usuario->save();

      return $this->app->json(array('mensaje' => 'El usuario fue agregado correctamente')); 
    }
  }

  // Administrar usuarios
  public function getIndex(){
    return $this->app['twig']->render('usuarios/usuarios.html');
  }


  // mensajes
  // public function getMensajes(){
  //   return $this->app['twig']->render('contactenos.html');
  // }

  // public function postMensajes(Request $request){
  //   $mensaje=$request->get("mensaje");
  //   // $email = $this->app['session']->get('user');
        
  //   $post = \ORM::for_table('mensajes')->create(); 
  //   // $post->email= $email;
  //   $post->mensaje= $mensaje;

  //   $post->save();

  //   return $this->app->json(array('mensaje' => 'El mensaje fue enviado correctamente')); 
  // }
}