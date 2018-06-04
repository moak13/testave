<?php

namespace App\Controllers;

use Slim\Views\Twig as View;
use App\Models\Admin;

class PanelController extends BaseController

{
   public function index($request, $response)

   {
      return $this->container->view->render($response, 'templates/admin/page/panel.twig');
   }

}

?>