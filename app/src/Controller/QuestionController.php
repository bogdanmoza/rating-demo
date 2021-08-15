<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Form\ListType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as ViewResponse;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class QuestionController extends AbstractFOSRestController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get Questions",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\Question::class, groups={"non_sensitive_data"}))
     *     )
     * )
     * @OA\Tag(name="question")
     * @View(serializerGroups={"list"})
     * @Route("/api/question", name="question_list_all", methods={"GET"})
    */
    public function listAll(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $form = $this->createForm(ListType::class);
        $form->submit($request->query->all(), true);

        if ($form->isValid()) {
            return $this->view($em->getRepository(Question::class)->findBy(
                [],
                [
                    $form->get('orderBy')->getData() => $form->get('direction')->getData()
                ],
                $form->get('limit')->getData(),
                $form->get('offset')->getData()
            ));
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Get Question",
     *     @OA\Items(ref=@Model(type=App\Entity\Question::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="question")
     * @View(serializerGroups={"list"})
     * @Route("/api/question/{id}", name="question_list", methods={"GET"})
    */
    public function list(string $id, EntityManagerInterface $em): ViewResponse
    {
        $question = $em->getRepository(Question::class)->find($id);

        if ($question) {
            return $this->view($question);
        }

        return $this->view(null, 404);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create Question",
     *     @OA\Items(ref=@Model(type=App\Entity\Question::class, groups={"non_sensitive_data"}))
     * )
     * @View(serializerGroups={"list"})
     * @Route("/api/question", name="question_create", methods={"POST"})
    */
    public function create(Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($question);
            $em->flush();

            return $this->view($question, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Delete Question"
     * )
     * @OA\Tag(name="question")
     * @View(serializerGroups={"list"})
     * @Route("/api/question/{id}", name="question_delete", methods={"DELETE"})
    */
    public function delete(string $id, EntityManagerInterface $em): ViewResponse
    {
        $question = $em->getRepository(Question::class)->find($id);

        if ($question) {
            $em->remove($question);
            $em->flush();
            $em->clear(Question::class);

            return $this->view($question);
        }

        return $this->view(null, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Patch Question"
     * )
     * @OA\Tag(name="question")
     * @View(serializerGroups={"list"})
     * @Route("/api/question/{id}", name="question_patch", methods={"PATCH"})
    */
    public function patch(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $question = $em->getRepository(Question::class)->find($id);

        if (!$question) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(QuestionType::class, $question, [
            'required'  => false,
            'method'    => 'PATCH'
        ]);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $em->persist($question);
            $em->flush();

            return $this->view($question);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Put Question"
     * )
     * @OA\Tag(name="question")
     * @View(serializerGroups={"list"})
     * @Route("/api/question/{id}", name="question_put", methods={"PUT"})
    */
    public function put(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $question = $em->getRepository(Question::class)->find($id);

        if (!$question) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(QuestionType::class, $question, [
            'method' => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($question);
            $em->flush();

            return $this->view($question);
        }

        return $this->view($form, 400);
    }
}
