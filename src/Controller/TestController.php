<?php

namespace App\Controller;

use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(EntityManagerInterface $em): Response
    {
        $tests = $em->getRepository(Test::class)->findAll();
        $output = array_map(fn($t) => $t->getTekst(), $tests);

        return new Response(implode('<br>', $output));
    }
}
