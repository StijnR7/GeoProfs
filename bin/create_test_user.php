<?php
// Usage (PowerShell):
// $env:DATABASE_URL = "sqlite:///%cd%/var/data_dev.db"; php bin/create_test_user.php

use App\Entity\User;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Tools\SchemaTool;

require __DIR__ . '/../vendor/autoload.php';

// Ensure we use an SQLite database file inside var/
$projectDir = realpath(__DIR__ . '/..');
$sqlitePath = $projectDir . '/var/data_dev.db';

// If DATABASE_URL is not set, default to local sqlite
if (empty(getenv('DATABASE_URL'))) {
    putenv('DATABASE_URL="sqlite:///' . $sqlitePath . '"');
    $_ENV['DATABASE_URL'] = 'sqlite:///' . $sqlitePath;
}

// Boot the kernel so Doctrine and services are available
// Boot the Symfony Kernel directly
require $projectDir . '/vendor/autoload.php';
if (!class_exists('\App\Kernel')) {
    echo "Kernel class \App\\Kernel not found.\n";
    exit(1);
}

$kernel = new \App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();

// Create schema for mapped entities (if not exists)
$meta = $em->getMetadataFactory()->getAllMetadata();
if (!empty($meta)) {
    $schemaTool = new SchemaTool($em);
    try {
        $schemaTool->createSchema($meta);
        echo "Created schema in SQLite DB: $sqlitePath\n";
    } catch (\Exception $e) {
        // ignore if schema already exists
        echo "Schema create: " . $e->getMessage() . "\n";
    }
}

// Create or update the test user
$repo = $em->getRepository(User::class);
$email = 'testuser@example.com';
$user = $repo->findOneBy(['email' => $email]);
if (!$user) {
    $user = new User();
    $user->setEmail($email);
}

$plain = 'Welkom123';
$hashed = password_hash($plain, PASSWORD_BCRYPT);
$user->setPassword($hashed);
$em->persist($user);
$em->flush();

echo "User '$email' created/updated with password 'Welkom123' in $sqlitePath\n";
