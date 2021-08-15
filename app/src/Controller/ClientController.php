<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
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

class ClientController extends AbstractFOSRestController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get Clients",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\Client::class, groups={"non_sensitive_data"}))
     *     )
     * )
     * @OA\Tag(name="client")
     * @View(serializerGroups={"list"})
     * @Route("/api/client", name="client_list_all", methods={"GET"})
    */
    public function listAll(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $form = $this->createForm(ListType::class);
        $form->submit($request->query->all(), true);

        if ($form->isValid()) {
            return $this->view($em->getRepository(Client::class)->findBy(
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
     *     description="Get Client",
     *     @OA\Items(ref=@Model(type=App\Entity\Client::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="client")
     * @View(serializerGroups={"list"})
     * @Route("/api/client/{id}", name="client_list", methods={"GET"})
    */
    public function list(string $id, EntityManagerInterface $em): ViewResponse
    {
        $client = $em->getRepository(Client::class)->find($id);

        if ($client) {
            return $this->view($client);
        }

        return $this->view(null, 404);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create Client",
     *     @OA\Items(ref=@Model(type=App\Entity\Client::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="client")
     * @View(serializerGroups={"list"})
     * @Route("/api/client", name="client_create", methods={"POST"})
    */
    public function create(Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client, [
            'required' => true
        ]);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $client->setPassword(
                $encoderFactory
                    ->getPasswordHasher(Client::class)
                    ->hash($form->get('plainPassword')->getData())
            );

            $em->persist($client);
            $em->flush();

            return $this->view($client, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Delete Client"
     * )
     * @OA\Tag(name="client")
     * @View(serializerGroups={"list"})
     * @Route("/api/client/{id}", name="client_delete", methods={"DELETE"})
    */
    public function delete(string $id, EntityManagerInterface $em): ViewResponse
    {
        $client = $em->getRepository(Client::class)->find($id);

        if ($client) {
            $em->remove($client);
            $em->flush();
            $em->clear(Client::class);

            return $this->view($client);
        }

        return $this->view(null, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Patch Client"
     * )
     * @OA\Tag(name="client")
     * @View(serializerGroups={"list"})
     * @Route("/api/client/{id}", name="client_patch", methods={"PATCH"})
    */
    public function patch(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $client = $em->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(ClientType::class, $client, [
            'required'  => false,
            'method'    => 'PATCH'
        ]);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $client->setPassword(
                    $encoderFactory
                    ->getPasswordHasher(Client::class)
                    ->hash($form->get('plainPassword')->getData())
                );
            }

            $em->persist($client);
            $em->flush();

            return $this->view($client);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Put Client"
     * )
     * @OA\Tag(name="client")
     * @View(serializerGroups={"list"})
     * @Route("/api/client/{id}", name="client_put", methods={"PUT"})
    */
    public function put(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $client = $em->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(ClientType::class, $client, [
            'method' => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $client->setPassword(
                $encoderFactory
                    ->getPasswordHasher(Client::class)
                    ->hash($form->get('plainPassword')->getData())
            );
            $em->persist($client);
            $em->flush();

            return $this->view($client);
        }

        return $this->view($form, 400);
    }
}
