<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $tests = $em->getRepository(Test::class)->findAll();
        $testOutput = array_map(fn($t) => $t->getTekst(), $tests);

        $users = $userRepository->findAll();

        $userLines = array_map(function($u) {
            $roles = is_array($u->getRoles()) ? implode(', ', $u->getRoles()) : (string) $u->getRoles();
            return sprintf('ID: %s | Email: %s | Roles: %s | Password(hash): %s', $u->getId(), $u->getEmail(), $roles, $u->getPassword());
        }, $users);

        $html = '';
        $html .= '<h2>Tests</h2>';
        $html .= implode('<br>', $testOutput) ?: 'No tests found.';
        $html .= '<hr>';
        $html .= '<h2>Users (development only - hashed passwords shown)</h2>';
        $html .= implode('<br>', $userLines) ?: 'No users found.';

        return new Response($html);
    }
}
