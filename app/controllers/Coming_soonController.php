<?php

namespace App\Controllers;

use Slim\Views\Twig as View;
use App\Models\Subscriber;

class Coming_soonController extends BaseController

{
   public function index($request, $response)

   {
      return $this->container->view->render($response, 'coming_soon.twig');
   }

}

?>