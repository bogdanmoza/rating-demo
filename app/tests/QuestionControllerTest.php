<?php

namespace App\Tests;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Entity\Question;

class QuestionControllerTest extends AbstractTestCase
{
    use RefreshDatabaseTrait;

    public function testListAll(): void
    {
        $this->client->request('GET', '/api/question', ['limit' => 10]);

        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual(10, \count($content));
        $this->assertIsArray($content);
    }

    public function testList(): void
    {
        $randomQuestion = $this->em->getRepository(Question::class)->findOneBy([]);
        $this->client->request('GET', "/api/question/{$randomQuestion->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/api/question', [], [], [], json_encode([
            'title'         => 'test_title_create',
            'description'   => 'test_description_create'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertIsArray($content);
        $this->assertContains('test_title_create', $content);
    }

    public function testPatch(): void
    {
        $randomQuestion = $this->em->getRepository(Question::class)->findOneBy([]);
        $this->client->request('PATCH', "/api/question/{$randomQuestion->getId()}", [], [], [], json_encode([
            'title'      => 'test_patch',
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_patch', $content);
    }

    public function testPut(): void
    {
        $randomQuestion = $this->em->getRepository(Question::class)->findOneBy([]);
        $this->client->request('PUT', "/api/question/{$randomQuestion->getId()}", [], [], [], json_encode([
            'title'         => 'test_title',
            'description'   => 'test_description'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_title', $content);
    }

    public function testDelete(): void
    {
        $randomQuestion = $this->em->getRepository(Question::class)->findOneBy([]);
        $this->client->request('DELETE', "/api/question/{$randomQuestion->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }
}
