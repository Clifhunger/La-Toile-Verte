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
     *  @Route("/quiz", name="quiz")
     */
    public function quizAction()
    {
        return $this->render('FrontBundle:Default:quiz.html.twig', array('test'=>'123'));
    }


    /**
     *  @Route("/blog", name="blog")
     */
    public function BlogAction()
    {
        return $this->render('FrontBundle:Default:blog.html.twig');
    }
}
