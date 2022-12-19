<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/personnes', name: 'personnes_')]
class PersonneController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function listPersonnesAction(EntityManagerInterface $entityManager, Security $security) {
        
        // forbid disabled admins (by SUPER_ADMIN) from accessing their accounts 
        if(!$security->isGranted('personnes_list', $security->getUser()))
            throw new NotFoundHttpException("Votre compte est temporairement desactivé par le SUPER_ADMIN");
        
        $personnes = $entityManager->getRepository(Personne::class)->findAll();

        return $this->render('personne/personnes.html.twig', [
            'personnes' => $personnes
        ]);
    }

    #[Route('/add', name: 'add')]
    public function ajoutPersonneAction(EntityManagerInterface $entityManager, Request $request, Session $session, Security $security) {
        
        // forbid disabled admins (by SUPER_ADMIN) from accessing their accounts 
        if(!$security->isGranted('personnes_add', $security->getUser()))
            throw new NotFoundHttpException("Votre compte est temporairement desactivé par le SUPER_ADMIN");

        $personne = new Personne();
        
        $form = $this->createForm(PersonneType::class, $personne);

        // add button
        $form->add('send', SubmitType::class, [
            'label' => 'Ajouter'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // save to DB
            $entityManager->persist($personne);
            $entityManager->flush();

            // flash message 
            $session->set('green_msg', 'Personne Ajoutée !');

            return $this->redirectToRoute('personnes_list');
        }


        // display form
        return $this->render('personne/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{nom}-{prenom}-{id}', name: 'edit')]
    public function editPersonneAction(string $nom, string $prenom, int $id, EntityManagerInterface $entityManager, Request $request, Session $session, Security $security) {

        // forbid disabled admins (by SUPER_ADMIN) from accessing their accounts 
        if(!$security->isGranted('personnes_edit', $security->getUser()))
            throw new NotFoundHttpException("Votre compte est temporairement desactivé par le SUPER_ADMIN");

        $personne = $entityManager->getRepository(Personne::class)->findOneBy([
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom
        ]);
        $form = $this->createForm(PersonneType::class, $personne);

        // edit button
        $form->add('send', SubmitType::class, [ 'label' => 'Modifier' ]);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // update the updateAt field
            $personne->setUpdatedAt(new DateTimeImmutable());
            
            // save changes 
            $entityManager->flush();
            
            // flash message
            $session->set('green_msg', 'Personne modifiée');
            
            return $this->redirectToRoute('personnes_list');
        }

        // display form
        return $this->render('personne/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{nom}-{prenom}-{id}', name: 'delete')]
    public function deletePersonneAction(string $nom, string $prenom, int $id, EntityManagerInterface $entityManager, Session $session, Security $security) {

        // forbid disabled admins (by SUPER_ADMIN) from accessing their accounts 
        if(!$security->isGranted('personnes_delete', $security->getUser()))
            throw new NotFoundHttpException("Votre compte est temporairement desactivé par le SUPER_ADMIN");

        // find person
        $personne = $entityManager->getRepository(Personne::class)->findOneBy([
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom
        ]);

        if(!is_null($personne)) {
            // delete row
            $entityManager->remove($personne);
            $entityManager->flush();

            // flash message
            $session->set('red_msg', 'Personne Supprimée !');

            return $this->redirectToRoute('personnes_list');
        } 

        // handle case when person does not exist
        $session->set('red_msg', 'Personne n\'existe pas !');
        return $this->redirectToRoute('personnes_list');
    }
}
