<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pokemon', name: 'pokemon_')]
class PokemonController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function liste(PokemonRepository $repo): Response
    {
        $liste = $repo->findAll();
        return $this->render('pokemon/liste.html.twig',
            compact("liste")
        );
    }

    #[Route('/capture/{id}', name: 'capture')]
    public function capture(pokemon $pokemon, EntityManagerInterface $em): RedirectResponse
    {
        $pokemon->setEstCapture(!$pokemon->isEstCapture());
        $em->persist($pokemon);
        $em->flush();
        return $this->redirectToRoute('pokemon_liste');
    }


    #[Route('/details/{id}', name: 'details')]
    public function details(Pokemon $pokemon): Response
    {
        return $this->render('pokemon/details.html.twig',
            compact('pokemon')
        );
    }

    #[Route('/tri/{param}', name: 'tri')]
    public function tri(string $param, PokemonRepository $repo): Response
    {
        if ($param == 'capture') {
            $liste = $repo->findBy([], ["estCapture" => "DESC"]);
        } else {
            $liste = $repo->findBy([], ["nom" => "ASC"]);
        }
        return $this->render('pokemon/liste.html.twig',
            compact("liste")
        );
    }


}
