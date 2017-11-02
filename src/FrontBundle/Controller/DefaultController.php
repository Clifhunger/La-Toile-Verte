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
        $quizes = $this->getDoctrine()->getRepository('DataBaseBundle:Quiz')->find(1);
        $questions = $quizes->getQuestions();
        if ($quizes) {
            $page = $this->render('default.htm.twig', array('quiz' => $quizes, 'questions' => $questions));
        }
        $myfile = fopen($this->get('kernel')->getRootDir() . '/../web/quiz/default.htm.twig', "w") or die("Unable to open file!");
        fwrite($myfile, $page->getContent());
        return $this->render('FrontBundle:Default:quiz.html.twig');
    }


    /**
     *  @Route("/blog", name="blog")
     */
    public function BlogAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('DataBaseBundle:Article')->findVisible();
        return $this->render('FrontBundle:Default:blog.html.twig', array('articles' => $articles));
    }
}
