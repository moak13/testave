<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

$app = new \Slim\App([

'settings' => [

       'displayErrorDetails' => true,

]

]);

require __DIR__ . '/../app/routes.php';

$container = $app->getContainer();

$container['view'] = function ($container) {

   $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [

       'cache' => false,

   ]);

   $view->addExtension(new \Slim\Views\TwigExtension(

       $container->router,

       $container->request->getUri()

   ));

   return $view;

};

$container['database'] = function($container) use ($capsule) {
  return $capsule;
};

$container['AuthAdmin'] = function($container) {
  return new \App\Auth\AuthAdmin($container);
};

$container['AuthUser'] = function($container) {
  return new \App\Auth\AuthUser($container);
};

$container['PasswordHash'] = function($container) {
  return new \App\Services\PasswordHash_config\PasswordHash($container);
};

$container['HomeController'] = function($container) {
   return new \App\Controllers\HomeController($container);
};

$container['Coming_soonController'] = function($container) {
  return new \App\Controllers\Coming_soonController($container);
};

$container['PanelController'] = function($container) {
  return new \App\Controllers\PanelController($container);
};

$container['AdminAuthController'] = function($container) {
  return new \App\Controllers\AdminAuth\AdminAuthController($container);
};

$container['UserAuthController'] = function($container) {
  return new \App\Controllers\UserAuth\UserAuthController($container);
};
?>