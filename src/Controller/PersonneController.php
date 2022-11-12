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
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personnes', name: 'personnes_')]
class PersonneController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function listPersonnesAction(EntityManagerInterface $entityManager)
    {
        // recuperation des donnees de la base
        $personnes = $entityManager->getRepository(Personne::class)->findAll();

        // affichage de lapage
        return $this->render('personne/personnes.html.twig', [
            'personnes' => $personnes
        ]);
    }

    #[Route('/add', name: 'add')]
    public function ajoutPersonneAction(EntityManagerInterface $entityManager, Request $request, Session $session)
    {
        $personne = new Personne();
        
        $form = $this->createForm(PersonneType::class, $personne);

        // bouton d'ajout
        $form->add('send', SubmitType::class, [
            'label' => 'Ajouter'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // ajout a la BD
            $entityManager->persist($personne);
            $entityManager->flush();

            // flash message 
            $session->set('personne_message', 'Personne Ajoutée !');

            // redirection vers la liste des personnes
            return $this->redirectToRoute('personnes_list');
        }


        // affichage de la page de formulaire
        return $this->render('personne/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{nom}-{prenom}-{id}', name: 'edit')]
    public function editPersonneAction(string $nom, string $prenom, int $id, EntityManagerInterface $entityManager, Request $request, Session $session) {

        $personne = $entityManager->getRepository(Personne::class)->findOneBy([
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom
        ]);
        $form = $this->createForm(PersonneType::class, $personne);

        // bouton de modification
        $form->add('send', SubmitType::class, [ 'label' => 'Modifier' ]);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // mise a jour de la date de modification
            $personne->setUpdatedAt(new DateTimeImmutable());
            // enregistrer les modifications 
            $entityManager->flush();
            
            // flash message
            $session->set('personne_message', 'Personne modifiée');
            
            // redirection vers la liste des personnes
            return $this->redirectToRoute('personnes_list');
        }

        // affichage de la page de formulaire
        return $this->render('personne/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{nom}-{prenom}-{id}', name: 'delete')]
    public function deletePersonneAction(string $nom, string $prenom, int $id, EntityManagerInterface $entityManager, Session $session) {

        $personne = $entityManager->getRepository(Personne::class)->findOneBy([
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom
        ]);

        if(!is_null($personne)) {
            // suppression d'enregistrement
            $entityManager->remove($personne);
            $entityManager->flush();

            // flash message
            $session->set('personne_deleted', 'Personne Supprimée !');
            return $this->redirectToRoute('personnes_list');
        } else {
            // flash message (cas ou personne n'existe pas)
            $session->set('personne_deleted', 'Personne n\'existe pas !');
            return $this->redirectToRoute('personnes_list');
        }

    }

}
