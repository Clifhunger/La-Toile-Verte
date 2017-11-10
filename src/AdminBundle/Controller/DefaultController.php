<?php

namespace AdminBundle\Controller;

use DataBaseBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:template.html.twig');
    }

    /**
     * @Route("/articles", name="admin_articles")
     */
    public function adminArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('DataBaseBundle:Article')->findAll();
        return $this->render('AdminBundle:Default:articles.html.twig', array('articles' => $articles));
    }

    /**
     *  @Route("/adminArticle/id={id}", name="admin_modify_article")
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
            ->add('image', TextType::class, array('label' => 'Image'))
            ->add('visible', CheckboxType::class, array('label' => 'Visible', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Modifier article'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $detail = $form["detail"]->getData();
            $article->setDetail($detail);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('AdminBundle:Default:article.html.twig', array('article'=>$article, 'form' => $form->createView()));
    }

    /**
     *  @Route("/createArticle", name="create_article")
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
            ->add('image', TextType::class, array('label' => 'Image'))
            ->add('visible', CheckboxType::class, array('label' => 'Visible', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Créer article'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $article->setLikes(0);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('admin_articles');
        }
        return $this->render('AdminBundle:Default:createArticle.html.twig', array('form' => $form->createView()));
    }
}
