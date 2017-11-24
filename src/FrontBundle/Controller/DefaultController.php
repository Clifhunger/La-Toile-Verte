<?php

namespace FrontBundle\Controller;

use DataBaseBundle\Entity\Article;
use DataBaseBundle\Entity\QuizSession;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

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
     * @Route("/quiz/handleQuizSessionsAction", name="handleQuizSessionsAction")
     */
    public function handleQuizSessionsAction() {
        $em = $this->getDoctrine()->getManager();
        $quizSession = $em->getRepository('DataBaseBundle:QuizSession')->findOneByCode($_POST["code"]);
        if(array_key_exists("edit", $_POST)) {

        }
        if(array_key_exists("delete", $_POST)) {
            $quizSession->setOver(true);
            $em->flush();
            $filename = 'quiz/' . $_POST["code"];
            $full_path = $this->get('kernel')->getRootDir() . '/../web/' . $filename;
            unlink($full_path);
        }
        return $this->redirectToRoute('quiz');
    }

    /**
     *  @Route("/quiz", name="quiz")
     *  @Route("/quiz/{id}", name="quiz_id", requirements={"id": "\d+"})
     */
    public function quizAction($id = null, Request $request)
    {
        $user = $this->getUser();
        if ($user->getRole() == "prof") {
            $quizes = $this->getDoctrine()->getRepository('DataBaseBundle:Quiz')->findAll();
            $quizSession = new QuizSession();
            
            $form = $this->createFormBuilder($quizSession)
                ->add('quiz', ChoiceType::class, array(
                    'choices' => $quizes,
                    'choice_label' => 'label',
                ))
                ->add('begin_date', DateTimeType ::class, array(
                    'label' => 'Début',
                    'view_timezone' => 'Europe/Paris',
                    'model_timezone' => 'Europe/Paris',
                ))
                ->add('end_date', DateTimeType ::class, array(
                    'label' => 'Fin',
                    'view_timezone' => 'Europe/Paris',
                    'model_timezone' => 'Europe/Paris',
                ))
                ->add('submit', SubmitType::class, array('label' => 'Créer'))
                ->getForm();
        
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $code = rand(100000, 999999);
                $quizSession = $form->getData();

                $quizSession->setCreationDate(new \DateTime());
                $quizSession->setCreator($user);
                $quizSession->setCode($code);

                $em = $this->getDoctrine()->getManager();
                $em->persist($quizSession);
                $em->flush();

                $quiz = $this->getDoctrine()->getRepository('DataBaseBundle:QuizSession')->findOneByCode($code)->getQuiz();
                $questions = $quiz->getQuestions();
                $page = $this->render('default.htm.twig', array('quiz' => $quiz, 'questions' => $questions));
            
                $filename = 'quiz/' . $code;
                $full_path = $this->get('kernel')->getRootDir() . '/../web/' . $filename;
                $myfile = fopen($full_path, "w") or die("Unable to open file!");
                fwrite($myfile, $page->getContent());
                fclose($myfile);

                return $this->redirectToRoute('quiz');
            }

            $quizeSessions = $this->getDoctrine()->getRepository('DataBaseBundle:QuizSession')->findByCreator($user);
        
            return $this->render('FrontBundle:Default:quizSession.html.twig', array(
                'form' => $form->createView(),
                'quiz_sessions' => $quizeSessions,
            ));
        }
        else {
            if ($id == null) {
                $form = $this->createFormBuilder()
                ->add('id', TextType::class)
                ->add('save', SubmitType::class, array('label' => 'Valider'))
                ->getForm();

                $form->handleRequest($request);
                
                if ($form->isSubmitted() && $form->isValid()) {
                    $quizSessionCode = $form->getData();
                    $quizSessionCode = str_replace('#', '', $quizSessionCode);
                    $quizSession = $this->getDoctrine()->getRepository('DataBaseBundle:QuizSession')->findOneByCode($quizSessionCode);

                    if(!$quizSession->getOver()) {
                        if ($quizSession->getDoneUsers()->contains($user)) {
                            $this->addFlash(
                                'error',
                                'Vous avez déjà participé au quiz'
                            );
                            return $this->redirectToRoute('quiz');
                        }
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
                                'Le Quiz est terminé'
                            );
                            return $this->redirectToRoute('quiz');
                        }
                        return $this->redirect('quiz/' . $quizSession->getCode());
                    }
                    else {
                        $this->addFlash(
                            'error',
                            'Quiz fermé'
                        );
                        return $this->redirectToRoute('quiz');
                    }
                }

                return $this->render('FrontBundle:Default:quiz.html.twig', array('form' => $form->createView(),));
            }
            else {
                $quizSession = $this->getDoctrine()->getRepository('DataBaseBundle:QuizSession')->findOneByCode($id);
                
                if(!$quizSession->getOver()) {
                    if ($quizSession->getDoneUsers()->contains($user)) {
                        $this->addFlash(
                            'error',
                            'Vous avez déjà participé au quiz'
                        );
                        return $this->redirectToRoute('quiz');
                    }
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
                            'Le Quiz est terminé'
                        );
                        return $this->redirectToRoute('quiz');
                    }
                }
                else {
                    $this->addFlash(
                        'error',
                        'Quiz fermé'
                    );
                    return $this->redirectToRoute('quiz');
                }
                $filename = 'quiz/' . $id;
                $full_path = $this->get('kernel')->getRootDir() . '/../web/' . $filename;
                return $this->render('FrontBundle:Default:quiz.html.twig', array('filename' => $filename, 'full_path' => $full_path, 'id' => $id));
            }
        }
    }

    /**
     * @Route("/quiz/{id}/finishQuiz", name="finishQuiz", requirements={"id": "\d+"})
     */
    public function finishQuizfAction($id = null, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $quizSession = $em->getRepository('DataBaseBundle:QuizSession')->findOneByCode($id);
        $user = $this->getUser();
        $quizSession->addDoneUser($user);
        $em->flush();
    }

    /**
     * @Route("/quiz/{id}/getCertif", name="getCertif", requirements={"id": "\d+"})
     */
    public function getCertifAction($id = null, Request $request) {
        $html = $this->renderView('FrontBundle:Default:certification.html.twig', array(
            'user'  => $this->getUser(),
            'date' => $_POST['date'],
            'percent' => $_POST['percent'],
        ));

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'certification.pdf'
        );
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
     *  @Route("/blogTCroissant", name="blogTrieCroissant")
     */
    public function BlogTrieCAction( Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Recherhce', SearchType ::class, array('label' => 'Rechercher', 'required'   => false,))
            ->add('trie_par', ChoiceType::class, array('label' => 'Trier par', 'required' => false,
                'choices'  => array('Titre' =>'title', 'Date' => 'dateCreation', 'Nombre de vue' => 'likes','Nombre de commentaire' => 'nb-com')))
            ->add('filtrer', SubmitType::class, array('label' => 'Filtrer'))
            ->add('Croissant', SubmitType::class)
            ->add('Decroissant', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->get('Decroissant')->isClicked())
        {
            return $this->redirectToRoute('blogTrieDecroissant', array('request' => $request),307);
        }
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('DataBaseBundle:Article')->rechercheCustumC($form->getData());
        return $this->render('FrontBundle:Default:blog.html.twig',array('articles' => $articles,'form' => $form->createView()));
    }
    /**
     *  @Route("/blogTDecroissant", name="blogTrieDecroissant")
     */
    public function BlogTrieDAction( Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Recherhce', SearchType ::class, array('label' => 'Rechercher', 'required'   => false,))
            ->add('trie_par', ChoiceType::class, array('label' => 'Trier par', 'required' => false,
                'choices'  => array('Titre' =>'title', 'Date' => 'dateCreation', 'Nombre de vue' => 'likes','Nombre de commentaire' => 'nb-com')))
            ->add('filtrer', SubmitType::class, array('label' => 'Filtrer'))
            ->add('Croissant', SubmitType::class)
            ->add('Decroissant', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->get('Croissant')->isClicked())
        {
            return $this->redirectToRoute('blogTrieCroissant', array('request' => $request),307);
        }
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('DataBaseBundle:Article')->rechercheCustumD($form->getData());
        return $this->render('FrontBundle:Default:blog.html.twig',array('articles' => $articles,'form' => $form->createView()));
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
