<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(LieuRepository $repo ): Response
    {
        return $this->render('accueil/index.html.twig', [
            'lieux' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/lieu/{id}", name="show_lieu")
     */
    public function show(Lieu $lieu, Request $request, EntityManagerInterface $manager): Response
    {
        $commentaire = new Commentaire();
        $formulaire = $this ->createForm(CommentaireType::class, $commentaire);

        $formulaire->handleRequest($request);
        if( $formulaire->isSubmitted() && $formulaire->isValid()) {

            $commentaire->setCreateAt(new \DateTime());
            $commentaire->setLieu($lieu);

            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute("show_lieu", ['id'=>$lieu->getId()]);
        }

        return $this->render('accueil/lieu.html.twig', [
            'lieu' => $lieu,
            'formulaire' => $formulaire->createView()
        ]);
    }

    /**
     * @Route("/lieu/create", name="create_lieu", priority=1)
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $lieu = new Lieu();

        $formulaire = $this->createForm(LieuType::class, $lieu);

        // C'est la partie du traitement du formulaire : 
            $formulaire->handleRequest($request);
            if ($formulaire->isSubmitted() && $formulaire->isValid() ) {
                
                $lieu->setCreateAt(new \DateTime());

                    $fichierImage = $formulaire->get('image')->getData();

                        $extension = $fichierImage->guessExtension();

                    $imageName = uniqid().'.'.$extension;

                    $fichierImage->move(
                        $this->getParameter('images_lieux_directory'),
                        $imageName
                    );

                $lieu->setImage($imageName);

                $manager->persist($lieu);
                $manager->flush();

                return $this->redirectToRoute("show_lieu", ['id'=>$lieu->getId()]);
            }

        return $this->render('accueil/create.html.twig', [
            'formulaire' => $formulaire->createView()
        ]);
            
    }

    /**
     * @Route("/lieu/delete/{id}", name="delete_lieu")
     */
    public function delete(Lieu $lieu, EntityManagerInterface $manager): Response
    {
        $manager->remove($lieu);
        $manager->flush();

        return $this->redirectToRoute("accueil");
    }

}
