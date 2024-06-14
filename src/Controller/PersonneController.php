<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Team;
use App\Form\PersonneType;
use App\Form\TeamType;
use App\Repository\PersonneRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonneController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(PersonneRepository $personneRepo, TeamRepository $teamRepo): Response
    {
        $personne = new Personne();
        $personneForm = $this->createForm(PersonneType::class, $personne,
        ['action' => $this->generateUrl('ajouter_personne')]);

        $team = new Team();
        $teamForm = $this->createForm(TeamType::class, $team,
        ['action' => $this->generateUrl('ajouter_team')]);

        return $this->render('personne/index.html.twig', [
            'teamForm' => $teamForm,
            'personneForm' => $personneForm,
            'team' => $teamRepo ->findAll(),
            'personne' => $personneRepo ->findAll(),


        ]);
    }

    #[Route('/ajouter-equipe', name: 'ajouter_team')]
    public function ajouterEquipe(EntityManagerInterface $em, Request $request): Response
    {
        $team = new Team();
        $formTeam = $this->createForm(TeamType::class, $team);
        $formTeam->handleRequest($request);

        if ($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('app_main');
        }
        return $this->redirectToRoute('app_main');
    }

    #[Route('/ajouter-personne', name: 'ajouter_personne')]
    public function ajouterpersonne(EntityManagerInterface $em, Request $request): Response
    {
        $personne = new Personne();
        $formPersonne = $this->createForm(PersonneType::class, $personne);
        $formPersonne->handleRequest($request);

        if ($formPersonne->isSubmitted() && $formPersonne->isValid()) {
            $team = $formPersonne->get('teams')->getData();
            if ($team){
                $personne->addTeam($team);
            }

            $em->persist($personne);
            $em->flush();

            return $this->redirectToRoute('app_main');
        }
        return $this->redirectToRoute('app_main');
    }

    #[Route('team/delete/{id}', name: 'team_delete')]
    public function deleteTeam(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {

            $entityManager->remove($team);
            $entityManager->flush();

        return $this->redirectToRoute('app_main');
    }

    #[Route('/{id}', name: 'personne_delete')]
    public function deletePersonne(Request $request, Personne $personne, EntityManagerInterface $entityManager): Response
    {

            $entityManager->remove($personne);
            $entityManager->flush();


        return $this->redirectToRoute('app_main');
    }

    #[Route('/delete/personne/equipe/{team}/{personne}', name: 'personne_team_delete')]
    public function effacerPersonneEquipe(Team $team,Personne $personne,EntityManagerInterface $em): Response
    {
        $team->removePersonne($personne);
        $em->flush();
        return $this->redirectToRoute('app_main');

    }
}


