<?php

namespace App\Tests;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Entity\Member;

class MemberControllerTest extends AbstractTestCase
{
    use RefreshDatabaseTrait;

    public function testListAll(): void
    {
        $this->client->request('GET', '/api/member', ['limit' => 10]);

        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual(10, \count($content));
        $this->assertIsArray($content);
    }

    public function testList(): void
    {
        $randomMember = $this->em->getRepository(Member::class)->findOneBy([]);
        $this->client->request('GET', "/api/member/{$randomMember->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/api/member', [], [], [], json_encode([
            'username'      => 'test_member@gmail.com',
            'name'          => 'Name',
            'plainPassword' => 'random'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertIsArray($content);
        $this->assertContains('test_member@gmail.com', $content);
    }

    public function testPatch(): void
    {
        $randomMember = $this->em->getRepository(Member::class)->findOneBy([]);
        $this->client->request('PATCH', "/api/member/{$randomMember->getId()}", [], [], [], json_encode([
            'username'      => 'test_patch_member@gmail.com',
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_patch_member@gmail.com', $content);
    }

    public function testPut(): void
    {
        $randomMember = $this->em->getRepository(Member::class)->findOneBy([]);
        $this->client->request('PUT', "/api/member/{$randomMember->getId()}", [], [], [], json_encode([
            'username'      => 'test_put_member@gmail.com',
            'name'          => 'New Name',
            'plainPassword' => 'random'
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_put_member@gmail.com', $content);
    }

    public function testDelete(): void
    {
        $randomMember = $this->em->getRepository(Member::class)->findOneBy([]);
        $this->client->request('DELETE', "/api/member/{$randomMember->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }
}
