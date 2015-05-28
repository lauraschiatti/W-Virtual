<?php

require_once __DIR__.'/../vendor/autoload.php';

//Usar
use Symfony\Component\HttpFoundation\Request;
use Clases\AdministrarUsuarios;
use Clases\AdministrarSalas;
use Clases\AdministrarEquipos;

//Idiorm
ORM::configure('mysql:host=localhost;dbname=proyecto');
ORM::configure('username', 'root');
ORM::configure('password', '');

//Crear app
$app = new Silex\Application();

// Proveedores
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(),array(
    'twig.path' => __DIR__ . '/views',
));
$app->register(new Silex\Provider\SessionServiceProvider());


//Index
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html');
});

//Usuarios
$app['usuarios.controller'] = $app->share(function() use ($app) {
    return new AdministrarUsuarios($app);
});

//Login
$app->get('/usuarios/login', 'usuarios.controller:getLogin');
$app->post('/usuarios/login', 'usuarios.controller:postLogin');

//Crear usuario
$app->get('/usuarios', 'usuarios.controller:getIndex');
$app->get('/api/usuarios', 'usuarios.controller:getUsuarios');
$app->post('/api/usuarios', 'usuarios.controller:postUsuarios');

// Administrar reservas
$app->get('/reservas', function () use ($app) {
    return $app['twig']->render('usuarios/reservas.html');
});

$app->get('/reservasequipos', function () use ($app) {
    return $app['twig']->render('usuarios/reservaequipos.html');
});

// Reservas de un usuario
$app->get('/misreservas', function () use ($app) {
	$usuario = $app['session']->get('user');
    return $app['twig']->render('usuarios/misreservas.html', $usuario);
});

$app->get('misreservasequipos', function () use ($app) {
	$usuario = $app['session']->get('user');
    return $app['twig']->render('usuarios/misreservasequipos.html', $usuario);
});

//Menu usuario
$app->get('/usuarios/inicio', function () use ($app) {
    return $app['twig']->render('usuarios/inicio.html');
});

// Menu administrador
$app->get('/usuarios/inicioadmin', function () use ($app) {
    return $app['twig']->render('usuarios/inicioadmin.html');
});

// contactenos
// $app->get('/contactenos', 'usuarios.controller:getMensajes');
// $app->post('/contactenos', 'usuarios.controller:postMensajes');

//Salas
$app['salas.controller'] = $app->share(function() use ($app) {
    return new AdministrarSalas($app);
});

//Crear sala
$app->get('/salas', 'salas.controller:getIndex');
$app->get('/api/salas', 'salas.controller:getSalas');
$app->post('/api/salas', 'salas.controller:postSalas');
$app->delete('/api/salas/{id}', 'salas.controller:deleteSalas');

//Reservar sala
$app->get('/salas/reserva', 'salas.controller:getReservaIndex');
$app->get('/api/salas/reserva', 'salas.controller:getReserva');
$app->post('/api/salas/reserva', 'salas.controller:postReserva');

//equipos
$app['equipos.controller'] = $app->share(function() use ($app) {
    return new AdministrarEquipos($app);
});

//Crear equipo
$app->get('/equipos', 'equipos.controller:getIndex');
$app->get('/api/equipos', 'equipos.controller:getEquipos');
$app->post('/api/equipos', 'equipos.controller:postEquipos');
$app->delete('/api/equipos/{id}', 'equipos.controller:deleteEquipos');

//Reservar equipo
$app->get('/equipos/reserva', 'equipos.controller:getReservaIndex');
$app->get('/api/equipos/reserva', 'equipos.controller:getReserva');
$app->post('/api/equipos/reserva', 'equipos.controller:postReserva');

$app['debug'] = true;

$app->run();
