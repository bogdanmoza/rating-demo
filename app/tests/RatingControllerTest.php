<?php

namespace App\Tests;

use App\Entity\Project;
use App\Entity\Question;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use App\Entity\Rating;

class RatingControllerTest extends AbstractTestCase
{
    use RefreshDatabaseTrait;

    public function testListAll(): void
    {
        $this->client->request('GET', '/api/rating', ['limit' => 10]);

        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual(10, \count($content));
        $this->assertIsArray($content);
    }

    public function testList(): void
    {
        $randomRating = $this->em->getRepository(Rating::class)->findOneBy([]);
        $this->client->request('GET', "/api/rating/{$randomRating->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/api/rating', [], [], [], json_encode([
            'score'     => 4,
            'comment'   => 'test_comment',
            'project'   => $this->em->getRepository(Project::class)->findOneBy([])->getId(),
            'ratingQuestions' => [
                [
                    'score' => 3,
                    'question' => $this->em->getRepository(Question::class)->findOneBy([])->getId()
                ]
            ]
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertIsArray($content);
        $this->assertContains('test_comment', $content);
    }

    public function testPatch(): void
    {
        $randomRating = $this->em->getRepository(Rating::class)->findOneBy([]);
        $this->client->request('PATCH', "/api/rating/{$randomRating->getId()}", [], [], [], json_encode([
            'comment' => 'test_patch',
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_patch', $content);
    }

    public function testPut(): void
    {
        $randomRating = $this->em->getRepository(Rating::class)->findOneBy([]);
        $this->client->request('PUT', "/api/rating/{$randomRating->getId()}", [], [], [], json_encode([
            'score'     => 4,
            'comment'   => 'test_put_comment',
            'project'   => $this->em->getRepository(Project::class)->findOneBy([])->getId(),
            'ratingQuestions' => [
                [
                    'score' => 3,
                    'question' => $this->em->getRepository(Question::class)->findOneBy([])->getId()
                ]
            ]
        ]));
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
        $this->assertContains('test_put_comment', $content);
    }

    public function testDelete(): void
    {
        $randomRating = $this->em->getRepository(Rating::class)->findOneBy([]);
        $this->client->request('DELETE', "/api/rating/{$randomRating->getId()}");
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertIsArray($content);
    }
}
