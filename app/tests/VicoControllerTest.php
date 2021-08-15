<?php

namespace App\Tests;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Entity\Vico;

class VicoControllerTest extends AbstractTestCase
{
    use RefreshDatabaseTrait;

    public function testListAll(): void
    {
        $this->client->request('GET', '/api/vico', ['limit' => 10]);

        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual(10, \count($content));
        $this->assertIsArray($content);
    }

    public function testList(): void
    {
        $randomVico = $this->em->getRepository(Vico::class)->findOneBy([]);
        $this->client->request('GET', "/api/vico/{$randomVico->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/api/vico', [], [], [], json_encode([
            'name' => 'test_create'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertIsArray($content);
        $this->assertContains('test_create', $content);
    }

    public function testPatch(): void
    {
        $randomVico = $this->em->getRepository(Vico::class)->findOneBy([]);
        $this->client->request('PATCH', "/api/vico/{$randomVico->getId()}", [], [], [], json_encode([
            'name' => 'test_patch',
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_patch', $content);
    }

    public function testPut(): void
    {
        $randomVico = $this->em->getRepository(Vico::class)->findOneBy([]);
        $this->client->request('PUT', "/api/vico/{$randomVico->getId()}", [], [], [], json_encode([
            'name' => 'test_put'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_put', $content);
    }
}
