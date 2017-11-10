<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function indexAction()
    {
        return $this->render('FrontBundle:Default:home.html.twig');
    }

    public function errorRedirect($message) {
        $this->addFlash(
            'error',
            $message
        );
        return $this->redirectToRoute('quiz');
    }

    /**
     *  @Route("/quiz", name="quiz")
     *  @Route("/quiz/{id}", name="quiz_id", requirements={"id": "\d+"})
     */
    public function quizAction($id = null, Request $request)
    {
        if ($id == null) {
            $form = $this->createFormBuilder()
            ->add('id', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $quizSessionCode = $form->getData();
                $quizSession = $this->getDoctrine()->getRepository('DataBaseBundle:QuizSession')->findOneByCode($quizSessionCode);

                if($quizSession) {
                    $now = new \DateTime("now");
                    if($quizSession->getBeginDate() > $now) {
                        $this->addFlash(
                            'error',
                            'Le Quiz n\'est pas encore ouvert'
                        );
                        return $this->redirectToRoute('quiz');
                    }
                    if($quizSession->getEndDate() < $now) {
                        $this->addFlash(
                            'error',
                            'Le Quiz est fermé'
                        );
                        return $this->redirectToRoute('quiz');
                    }
                    return $this->redirect('quiz/' . $quizSession->getCode());
                }
                else {
                    $this->addFlash(
                        'error',
                        'Quiz Introuvable'
                    );
                    return $this->redirectToRoute('quiz');
                }
            }

            return $this->render('FrontBundle:Default:quiz.html.twig', array('form' => $form->createView(),));
        }
        else {
            $quiz = $this->getDoctrine()->getRepository('DataBaseBundle:QuizSession')->findOneByCode($id)->getQuiz();
            $questions = $quiz->getQuestions();
            $page = $this->render('default.htm.twig', array('quiz' => $quiz, 'questions' => $questions));
        
            $filename = 'quiz/' . rand(1000, 9999);
            $full_path = $this->get('kernel')->getRootDir() . '/../web/' . $filename;
            $myfile = fopen($full_path, "w") or die("Unable to open file!");
            fwrite($myfile, $page->getContent());
            fclose($myfile);
            return $this->render('FrontBundle:Default:quiz.html.twig', array('filename' => $filename, 'full_path' => $full_path,));
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
