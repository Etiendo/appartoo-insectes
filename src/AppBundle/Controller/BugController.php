<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Informations;

class BugController extends Controller
{
    /**
     * @Route("/", name="insecte_list")
     */
    public function listAction(Request $request)
    {
        $informations = $this->getDoctrine()
            ->getRepository('AppBundle:Informations')
            ->findAll();

        return $this->render('insectes/index.html.twig', array(
            'todo' => $informations

        ));
    }

    /**
     * @Route("/insecte/create", name="insecte_create")
     */
    public function createAction(Request $request)
    {
        $informations = new Informations;

        $form = $this->createFormBuilder($informations)
            ->add('age', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('famille', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('race', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            // ->add('priority', ChoiceType::class, array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'High'=>'High'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('nourriture', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('Save', SubmitType::class, array('label'=> 'Créer insecte', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() &&  $form->isValid()) {
            $age = $form['age']->getData();
            $famille = $form['famille']->getData();
            $race = $form['race']->getData();
            $nourriture = $form['nourriture']->getData();

            $informations->setAge($age);
            $informations->setFamille($famille);
            $informations->setRace($race);
            $informations->setNourriture($nourriture);

            $sn = $this->getDoctrine()->getManager();
            $sn -> persist($informations);
            $sn -> flush();

            $this->addFlash(
                'notice',
                'Todo Added'
            );
            return $this->redirectToRoute('insect_list');
        }

        return $this->render('insectes/create.html.twig', array(
            'form' => $form->createView()

        ));
    }

    /**
     * @Route("insecte/details/{id}", name="insecte_details")
     */
    public function detailsAction($id)
    {
        $informations = $this->getDoctrine()
            ->getRepository('AppBundle:Informations')
            ->find($id);

        return $this->render('insectes/details.html.twig', array(
            'informations' => $informations
        ));
    }

    /**
     * @Route("insecte/edit/{id}", name="insecte_edit")
     */
    public function editAction($id, Request $request)
    {
        $informations = $this->getDoctrine()
            ->getRepository('AppBundle:Informations')
            ->find($id);

        $informations->setAge($informations->getAge());
        $informations->setFamille($informations->getFamille());
        $informations->setRace($informations->getRace());
        $informations->setNourriture($informations->getNourriture());

        $form = $this->createFormBuilder($informations)
            ->add('age', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('famille', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('race', TextareaType::class, array('attr' => array('nourriture' => 'form-control', 'style' => 'margin-bottom:15px')))

            ->add('Save', SubmitType::class, array('label'=> 'Effectuer modifications', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() &&  $form->isValid()) {
            $age = $form['age']->getData();
            $famille = $form['famille']->getData();
            $race = $form['race']->getData();
            $nourriture = $form['nourriture']->getData();


            $sn = $this->getDoctrine()->getManager();
            $informations = $sn->getRepository('AppBundle:Informations')->find($id);

            $informations->setAge($age);
            $informations->setFamille($famille);
            $informations->setRace($race);
            $informations->setNourriture($nourriture);

            $sn -> flush();

            $this->addFlash(
                'notice',
                'Todo Updated'
            );
            return $this->redirectToRoute('insect_list');
        }

        return $this->render('insectes/edit.html.twig', array(
            'infos' => $informations,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("insecte/delete/{id}", name="insecte_delete")
     */
    public function deleteAction($id)
    {
        $sn = $this->getDoctrine()->getManager();
        $informations = $sn->getRepository('AppBundle:Informations')->find($id);

        $sn->remove($informations);
        $sn->flush();
        //  $informations = $this->getDoctrine()
        // ->getRepository('AppBundle:Informations')
        // ->find($id);

        $this->addFlash(
            'notice',
            'Todo Removed'
        );
        return $this->redirectToRoute('insecte_list');
    }
}
