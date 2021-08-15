<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Vico;
use App\Form\VicoType;
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

class VicoController extends AbstractFOSRestController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get Vicos",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\Vico::class, groups={"non_sensitive_data"}))
     *     )
     * )
     * @OA\Tag(name="vico")
     * @View(serializerGroups={"list"})
     * @Route("/api/vico", name="vico_list_all", methods={"GET"})
    */
    public function listAll(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $form = $this->createForm(ListType::class);
        $form->submit($request->query->all(), true);

        if ($form->isValid()) {
            return $this->view($em->getRepository(Vico::class)->findBy(
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
     *     description="Get Vico",
     *     @OA\Items(ref=@Model(type=App\Entity\Vico::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="vico")
     * @View(serializerGroups={"list"})
     * @Route("/api/vico/{id}", name="vico_list", methods={"GET"})
    */
    public function list(string $id, EntityManagerInterface $em): ViewResponse
    {
        $vico = $em->getRepository(Vico::class)->find($id);

        if ($vico) {
            return $this->view($vico);
        }

        return $this->view(null, 404);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create Vico",
     *     @OA\Items(ref=@Model(type=App\Entity\Vico::class, groups={"non_sensitive_data"}))
     * )
     * @View(serializerGroups={"list"})
     * @OA\Tag(name="vico")
     * @Route("/api/vico", name="vico_create", methods={"POST"})
    */
    public function create(Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $vico = new Vico();
        $form = $this->createForm(VicoType::class, $vico);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($vico);
            $em->flush();

            return $this->view($vico, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Delete Vico"
     * )
     * @OA\Tag(name="vico")
     * @View(serializerGroups={"list"})
     * @Route("/api/vico/{id}", name="vico_delete", methods={"DELETE"})
    */
    public function delete(string $id, EntityManagerInterface $em): ViewResponse
    {
        $vico = $em->getRepository(Vico::class)->find($id);
        if ($vico) {
            $em->remove($vico);
            $em->flush();
            $em->clear(Vico::class);

            return $this->view($vico);
        }

        return $this->view(null, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Patch Vico"
     * )
     * @OA\Tag(name="vico")
     * @View(serializerGroups={"list"})
     * @Route("/api/vico/{id}", name="vico_patch", methods={"PATCH"})
    */
    public function patch(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $vico = $em->getRepository(Vico::class)->find($id);

        if (!$vico) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(VicoType::class, $vico, [
            'required'  => false,
            'method'    => 'PATCH'
        ]);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $em->persist($vico);
            $em->flush();

            return $this->view($vico);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Put Vico"
     * )
     * @OA\Tag(name="vico")
     * @View(serializerGroups={"list"})
     * @Route("/api/vico/{id}", name="vico_put", methods={"PUT"})
    */
    public function put(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $vico = $em->getRepository(Vico::class)->find($id);

        if (!$vico) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(VicoType::class, $vico, [
            'method' => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($vico);
            $em->flush();

            return $this->view($vico);
        }

        return $this->view($form, 400);
    }
}
