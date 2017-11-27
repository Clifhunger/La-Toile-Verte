<?php

namespace AdminBundle\Controller;

use DataBaseBundle\Entity\Article;
use DataBaseBundle\Entity\Question;
use DataBaseBundle\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $em->getRepository('DataBaseBundle:QuizSession')->findSession();
        $chart=array();
        $j=0;
        for ($i=0;$i<sizeof($session);++$j)
        {
            $dateJ=Date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-(10-$j), date("Y")));
            if($session[$i]['creationDate']->format('Y-m-d')==$dateJ)
            {
                array_push($chart,$session[$i]);
                ++$i;
            }
            else{
                array_push($chart,array('creationDate'=>$dateJ,'nbre'=>0));
            }
        }
            for ($j;$j<11;++$j) {
                $dateJ=Date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-(10-$j), date("Y")));
                array_push($chart,array('creationDate'=>$dateJ,'nbre'=>0));
            }
        return $this->render('AdminBundle:Default:index.html.twig',array('chart'=>$chart));
    }

    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function adminArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('DataBaseBundle:Article')->findAll();
        return $this->render('AdminBundle:Default:articles.html.twig', array('articles' => $articles));
    }

    /**
     *  @Route("/admin/articles/id={id}", name="admin_modify_article")
     */
    public function adminArticleAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getManager()->getRepository('DataBaseBundle:Article');
        $article = $repository->find($id);

        $form = $this->createFormBuilder($article)
            ->add('dateCreation', DateType::class, array('label' => 'Date de création'))
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('detail', TextareaType::class, array('label' => 'Détails'))
            ->add('image', FileType::class, array('label' => 'Image', 'data_class'   =>  null, 'required' => false))
            ->add('visible', CheckboxType::class, array('label' => 'Visible', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Modifier article'))
            ->add('suppr', SubmitType::class, array('label' => 'Supprimer article', 'attr' => array(
                'onclick' => 'return confirm("Confirmer la suppression ?")'
            )))
            ->getForm();

        $image_before = $article->getImage();
        $form->handleRequest($request);
        if ($form->isSubmitted() && 'save' === $form->getClickedButton()->getName()) {
            $image = $form["image"]->getData();
            $length=strripos($image,".")-strripos($image,"\\");
            $nom=substr($image,strripos($image,"\\")+1,$length-1);

            if ( $nom!="" ) {
                $image = $form["image"]->getData();
                $lien=$this->get('kernel')->getRootDir() . "\..\web\articles\img\\";

                $nom=$nom.".png";

                $article->setImage($nom);
                move_uploaded_file( $image , $lien.$nom );
            } else {
                $article->setImage($image_before);
            }

            $article->setLikes(0);
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_articles');
        }

        if ($form->isSubmitted() && 'suppr' === $form->getClickedButton()->getName()) {
            $em->remove($article);
            $em->flush();
            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('AdminBundle:Default:article.html.twig', array('article'=>$article, 'form' => $form->createView()));
    }

    /**
     *  @Route("/admin/createArticle", name="create_article")
     */
    public function createArticleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('dateCreation', DateType::class, array('label' => 'Date de création'))
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('detail', TextareaType::class, array('label' => 'Détails'))
            ->add('image', FileType::class, array('label' => 'Image'))
            ->add('visible', CheckboxType::class, array('label' => 'Visible', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Créer article'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $image = $form["image"]->getData();
            $lien=$this->get('kernel')->getRootDir() . "\..\web\articles\img\\";

            $length=strripos($image,".")-strripos($image,"\\");
            $nom=substr($image,strripos($image,"\\")+1,$length-1);
            $nom=$nom.".png";

            $article->setLikes(0);
            $article->setImage($nom);

            $em->persist($article);
            $em->flush();

            move_uploaded_file( $image , $lien.$nom );

            return $this->redirectToRoute('admin_articles');
        }
        return $this->render('AdminBundle:Default:createArticle.html.twig', array('form' => $form->createView()));
    }

    /**
     *  @Route("/admin/createQuiz", name="create_quiz")
     */
    public function createQuizAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = new Quiz();

        $form = $this->createFormBuilder($quiz)
            ->add('label', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('certified', CheckBoxType::class, array('label' => 'Certifiant'))
            ->add('save', SubmitType::class, array('label' => 'Créer quiz'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($quiz);
            $em->flush();
            return $this->redirectToRoute('admin_quiz');
        }
        return $this->render('AdminBundle:Default:createQuiz.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("admin/quiz", name="admin_quiz")
     */
    public function adminQuizAction()
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('DataBaseBundle:Quiz')->findAll();
        return $this->render('AdminBundle:Default:quiz.html.twig', array('quizs' => $quiz));
    }

    /**
     *  @Route("/admin/createQuestion", name="create_question")
     */
    public function createQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $allQuiz = $em->getRepository('DataBaseBundle:Quiz')->findAll();
        $question = new Question();

        $form = $this->createFormBuilder($question)
            ->add('quiz', ChoiceType::class, array(
                'label' => 'Quiz',
                'choices'  => $allQuiz,
                'choice_label' => 'label',
                'required' => true,))
            ->add('label', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('save', SubmitType::class, array('label' => 'Créer question'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('admin_quiz');
        }
        return $this->render('AdminBundle:Default:createQuestion.html.twig', array('form' => $form->createView()));
    }

    /**
     *  @Route("/admin/quiz/id={id}", name="admin_modify_quiz")
     */
    public function modifyQuizAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getManager()->getRepository('DataBaseBundle:Quiz');
        $quiz = $repository->find($id);

        $form = $this->createFormBuilder($quiz)
            ->add('label', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('certified', CheckBoxType::class, array('label' => 'Certifiant'))
            ->add('save', SubmitType::class, array('label' => 'Modifier quiz'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($quiz);
            $em->flush();
            return $this->redirectToRoute('admin_quiz');
        }

        return $this->render('AdminBundle:Default:modifyQuiz.html.twig', array('article'=>$quiz, 'form' => $form->createView()));
    }
}
