<?php

namespace App\Tests;

use App\Entity\Member;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Entity\Project;
use App\Entity\Vico;

class ProjectControllerTest extends AbstractTestCase
{
    use RefreshDatabaseTrait;

    public function testListAll(): void
    {
        $this->client->request('GET', '/api/project', ['limit' => 10]);

        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual(10, \count($content));
        $this->assertIsArray($content);
    }

    public function testList(): void
    {
        $randomProject = $this->em->getRepository(Project::class)->findOneBy([]);
        $this->client->request('GET', "/api/project/{$randomProject->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/api/project', [], [], [], json_encode([
            'vico'      => $this->em->getRepository(Vico::class)->findOneBy([])->getId(),
            'member'    => $this->em->getRepository(Member::class)->findOneBy([])->getId(),
            'title'     => 'test_create'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertIsArray($content);
        $this->assertContains('test_create', $content);
    }

    public function testPatch(): void
    {
        $randomProject = $this->em->getRepository(Project::class)->findOneBy([]);
        $this->client->request('PATCH', "/api/project/{$randomProject->getId()}", [], [], [], json_encode([
            'title'     => 'test_patch'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_patch', $content);
    }

    public function testPut(): void
    {
        $randomProject = $this->em->getRepository(Project::class)->findOneBy([]);
        $this->client->request('PUT', "/api/project/{$randomProject->getId()}", [], [], [], json_encode([
            'vico'      => $this->em->getRepository(Vico::class)->findOneBy([])->getId(),
            'member'    => $this->em->getRepository(Member::class)->findOneBy([])->getId(),
            'title'     => 'test_put'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_put', $content);
    }

    public function testDelete(): void
    {
        $randomProject = $this->em->getRepository(Project::class)->findOneBy([]);
        $this->client->request('DELETE', "/api/project/{$randomProject->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }
}
