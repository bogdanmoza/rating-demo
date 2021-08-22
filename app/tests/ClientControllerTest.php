<?php

namespace App\Tests;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Entity\Client;

class ClientControllerTest extends AbstractTestCase
{
    use RefreshDatabaseTrait;

    public function testListAll(): void
    {
        $this->client->request('GET', '/api/client', ['limit' => 10]);

        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual(10, \count($content));
        $this->assertIsArray($content);
    }

    public function testList(): void
    {
        $randomClient = $this->em->getRepository(Client::class)->findOneBy([]);
        $this->client->request('GET', "/api/client/{$randomClient->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/api/client', [], [], [], json_encode([
            'username'      => 'test_create_client@gmail.com',
            'plainPassword' => 'random',
            'firstName'     => 'Firstname',
            'lastName'      => 'Lastname'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertIsArray($content);
        $this->assertContains('test_create_client@gmail.com', $content);
    }

    public function testPatch(): void
    {
        $randomClient = $this->em->getRepository(Client::class)->findOneBy([]);
        $this->client->request('PATCH', "/api/client/{$randomClient->getId()}", [], [], [], json_encode([
            'username'      => 'test_patch_client@gmail.com',
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_patch_client@gmail.com', $content);
    }

    public function testPut(): void
    {
        $randomClient = $this->em->getRepository(Client::class)->findOneBy([]);
        $this->client->request('PUT', "/api/client/{$randomClient->getId()}", [], [], [], json_encode([
            'username'      => 'test_put_client@gmail.com',
            'plainPassword' => 'random',
            'firstName'     => 'Firstname',
            'lastName'      => 'Lastname'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_put_client@gmail.com', $content);
    }

    public function testDelete(): void
    {
        $randomClient = $this->em->getRepository(Client::class)->findOneBy([]);
        $this->client->request('DELETE', "/api/client/{$randomClient->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }
}
