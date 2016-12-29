<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyController extends Controller
{
    public function testAction()
    {
        return $this->render('AppBundle:My:test.html.twig', array(
            // ...
        ));
    }

}
