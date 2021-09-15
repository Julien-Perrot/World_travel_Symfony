<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/commentaire/delete/{id}", name="delete_commentaire")
     */
    public function delete(Commentaire $commentaire, EntityManagerInterface $manager): Response
    {
        $id = $commentaire->getLieu()->getId();

        $manager->remove($commentaire);
        $manager->flush();

        return $this->redirectToRoute('show_lieu', [
            'id' =>$id
        ]);
    }
}
