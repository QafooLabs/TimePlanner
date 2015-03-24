<?php

namespace Qafoo\TimePlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name = "Nobody")
    {
        return $this->render('QafooTimePlannerBundle:Default:index.html.twig', array('name' => $name));
    }
}
