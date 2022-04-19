<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name:'home')]
function index(): Response
    {
    return $this->render('home/index.html.twig', [
        'users' => [
            new User('meher', 33),
            new User('arbi', 31),
            new User('akrem', 30),
            new User('user', 29),

        ],
        /*   'person' => [
    'name' => 'Meher',
    'age' => 31,
    ], */

    ]);
}
}
