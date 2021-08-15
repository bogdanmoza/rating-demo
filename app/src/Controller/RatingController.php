<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
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

class RatingController extends AbstractFOSRestController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get Ratings",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\Rating::class, groups={"non_sensitive_data"}))
     *     )
     * )
     * @OA\Tag(name="rating")
     * @View(serializerGroups={"list", "rating_list"})
     * @Route("/api/rating", name="rating_list_all", methods={"GET"})
    */
    public function listAll(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $form = $this->createForm(ListType::class);
        $form->submit($request->query->all(), true);

        if ($form->isValid()) {
            return $this->view($em->getRepository(Rating::class)->findBy(
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
     *     description="Get Rating",
     *     @OA\Items(ref=@Model(type=App\Entity\Rating::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="rating")
     * @View(serializerGroups={"list", "rating_list"})
     * @Route("/api/rating/{id}", name="rating_list", methods={"GET"})
    */
    public function list(string $id, EntityManagerInterface $em): ViewResponse
    {
        $rating = $em->getRepository(Rating::class)->find($id);

        if ($rating) {
            return $this->view($rating);
        }

        return $this->view(null, 404);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create Rating",
     *     @OA\Items(ref=@Model(type=App\Entity\Rating::class, groups={"non_sensitive_data"}))
     * )
     * @View(serializerGroups={"list", "rating_list"})
     * @OA\Tag(name="rating")
     * @Route("/api/rating", name="rating_create", methods={"POST"})
    */
    public function create(Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($rating);
            $em->flush();

            return $this->view($rating, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Delete Rating"
     * )
     * @OA\Tag(name="rating")
     * @View(serializerGroups={"list", "rating_list"})
     * @Route("/api/rating/{id}", name="rating_delete", methods={"DELETE"})
    */
    public function delete(string $id, EntityManagerInterface $em): ViewResponse
    {
        $rating = $em->getRepository(Rating::class)->find($id);

        if ($rating) {
            $em->remove($rating);
            $em->flush();
            $em->clear(Rating::class);

            return $this->view($rating);
        }

        return $this->view(null, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Patch Rating"
     * )
     * @OA\Tag(name="rating")
     * @View(serializerGroups={"list", "rating_list"})
     * @Route("/api/rating/{id}", name="rating_patch", methods={"PATCH"})
    */
    public function patch(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $rating = $em->getRepository(Rating::class)->find($id);

        if (!$rating) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(RatingType::class, $rating, [
            'required'  => false,
            'method'    => 'PATCH'
        ]);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $em->persist($rating);
            $em->flush();

            return $this->view($rating);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Put Rating"
     * )
     * @OA\Tag(name="rating")
     * @View(serializerGroups={"list", "rating_list"})
     * @Route("/api/rating/{id}", name="rating_put", methods={"PUT"})
    */
    public function put(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $rating = $em->getRepository(Rating::class)->find($id);

        if (!$rating) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(RatingType::class, $rating, [
            'method' => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($rating);
            $em->flush();

            return $this->view($rating);
        }

        return $this->view($form, 400);
    }
}
