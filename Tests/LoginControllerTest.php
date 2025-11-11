<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Kernel;

class LoginControllerTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    public function testLoginPageLoads(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form input[name="_username"]');
        $this->assertSelectorExists('form input[name="_password"]');
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            '_username' => 'testuser',
            '_password' => 'Welkom123',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorTextContains('.error', 'Invalid credentials');
    }

    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = $container->get('doctrine')->getRepository(\App\Entity\User::class);
        $testUser = $userRepository->findOneByEmail('testuser@example.com');

        $client->loginUser($testUser);
        $client->request('GET', '/dashboard');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard');
    }
}
