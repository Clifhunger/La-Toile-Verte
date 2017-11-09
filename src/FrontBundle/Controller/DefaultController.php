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
     *  @Route("/quiz/{id}", name="quiz_id", requirements={"id": "\d+"})
     */
    public function quizAction($id = null)
    {
        if ($id == null) {
            return $this->render('FrontBundle:Default:quiz.html.twig');
        }
        else {
            $quizes = $this->getDoctrine()->getRepository('DataBaseBundle:Quiz')->find($id);
            if ($quizes) {
                $questions = $quizes->getQuestions();
                $page = $this->render('default.htm.twig', array('quiz' => $quizes, 'questions' => $questions));
            
                $filename = 'quiz/' . rand(1000, 9999);
                $full_path = $this->get('kernel')->getRootDir() . '/../web/' . $filename;
                $myfile = fopen($full_path, "w") or die("Unable to open file!");
                fwrite($myfile, $page->getContent());
                fclose($myfile);
                return $this->render('FrontBundle:Default:quiz.html.twig', array('filename' => $filename, 'full_path' => $full_path,));
            }
            else {
                $this->addFlash(
                    'error',
                    'Quiz Introuvable'
                );
                return $this->redirectToRoute('quiz');
            }
        }
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

    /**
     *  @Route("/article/id={id}", name="article")
     */
    public function articleAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('DataBaseBundle:Article');
        $article = $repository->find($id);

        return $this->render('FrontBundle:Default:article.html.twig', array('article'=>$article));
    }
}
