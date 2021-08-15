<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
    }
}
