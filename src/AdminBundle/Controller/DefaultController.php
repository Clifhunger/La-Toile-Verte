<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:template.html.twig');
    }

    /**
     * @Route("/articles", name="adminArticles")
     */
    public function adminArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('DataBaseBundle:Article')->findAll();
        return $this->render('AdminBundle:Default:articles.html.twig', array('articles' => $articles));
    }

    /**
     *  @Route("/adminArticle/id={id}", name="adminArticle")
     */
    public function adminArticleAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('DataBaseBundle:Article');
        $article = $repository->find($id);

        return $this->render('AdminBundle:Default:article.html.twig', array('article'=>$article));
    }

    /**
     *  @Route("/createArticle", name="createArticle")
     */
    public function createArticleAction()
    {
        return $this->render('AdminBundle:Default:createArticle.html.twig');
    }


}
