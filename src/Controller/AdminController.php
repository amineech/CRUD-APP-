<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/admins', name: 'admins_')]
class AdminController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function listAdminsAction(EntityManagerInterface $entityManager, Security $security)
    {
        $admins = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/admins.html.twig', [
           'admins' => $admins 
        ]);
    }

    // activer un admin
    #[Route('/user/enable/{id}', name: 'user_enable')]
    public function enableAdminAction(int $id, EntityManagerInterface $entityManager, Session $session) {

        $admin = $entityManager->getRepository(User::class)->find($id);
        
        if(!$admin) {
            // handle case when user does not exist
            $session->set('red_msg', 'Utilisateur n\'éxiste pas');
            return $this->redirectToRoute('admins_list');
        }
        $admin->setIsApproved(true);
        $entityManager->flush();
        
        $session->set('green_msg', 'Admin activé');
        return $this->redirectToRoute('admins_list');

    }

    // desactiver un admin
    #[Route('/user/disable/{id}', name: 'user_disable')]
    public function disableAdminAction(int $id, EntityManagerInterface $entityManager, Session $session) {

        $admin = $entityManager->getRepository(User::class)->find($id);

        if(!$admin) {
            // handle case when user does not exist
            $session->set('red_msg', 'Utilisateur n\'éxiste pas');
            return $this->redirectToRoute('admins_list');
        }
            
        $admin->setIsApproved(false);
        $entityManager->flush();

        $session->set('red_msg', 'Admin desactivé');
        return $this->redirectToRoute('admins_list');
    }

    // supprimer un admin
    #[Route('/delete/{id}', name: 'delete')]
    public function deleteAdminAction(int $id, EntityManagerInterface $entityManager, Session $session) {
        $admin = $entityManager->getRepository(User::class)->find($id);

        if(!$admin) {
            // handle case when user does not exist
            $session->set('red_msg', 'Utilisateur n\'éxiste pas');
            return $this->redirectToRoute('admins_list');
        }

        $entityManager->remove($admin);
        $entityManager->flush();

        $session->set('red_msg', 'Admin supprimé');
        return $this->redirectToRoute('admins_list');
    }
}
