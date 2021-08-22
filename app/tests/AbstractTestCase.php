<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractTestCase extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\KernelBrowser */
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        // can authenticate user here
        $this->em = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $token = new UsernamePasswordToken('admin@gmail.com', '1q2w3e4r', 'api', ['ROLE_ADMIN']);
        static::$kernel->getContainer()->get('security.token_storage')->setToken($token);

        $session = $this->client->getContainer()->get('session');
        $session->set('_security_api', serialize($token));
        $session->save();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
    }
}
