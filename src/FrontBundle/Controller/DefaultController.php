<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function indexAction()
    {
        return $this->render('FrontBundle:Default:home.html.twig');
    }

    /**
     *  @Route("/connexion", name="connexion")
     */
    public function connexionAction()
    {
        return $this->render('FrontBundle:Default:connexion.html.twig');
    }


    /**
     *  @Route("/blog", name="blog")
     */
    public function BlogAction()
    {
        return $this->render('FrontBundle:Default:blog.html.twig');
    }

}
