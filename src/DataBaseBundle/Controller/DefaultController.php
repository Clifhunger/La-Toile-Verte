<?php

namespace DataBaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DataBaseBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/signin")
     */
    public function signInAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
        ->add('name', TextType::class, array('label' => 'Prenom', 'required' => true))
        ->add('lastName', TextType::class, array('label' => 'Nom', 'required' => true))
        ->add('email', EmailType::class, array('label' => 'Email', 'required' => true))
        ->add('password', PasswordType::class, array('label' => 'Mot de Passe', 'required' => true))
        ->add('role', ChoiceType::class, array(
            'label' => 'Rôle',
            'choices'  => array(
                'Élève' => 'eleve',
                'Professeur' => 'prof'
            ),
            'expanded' => false,
            'multiple' => false,
            'required' => true,
        ))
        ->add('school', TextType::class, array('label' => 'Établissement', 'required' => true))
        ->add('signin', SubmitType::class, array('label' => 'S\'inscrire'))
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('signin_success');
        }
        return $this->render('DataBaseBundle:Default:signin.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/signin/success", name="signin_success")
     */
    public function signInSuccessAction()
    {
        return $this->render('DataBaseBundle:Default:signin_success.html.twig');
    }
}
