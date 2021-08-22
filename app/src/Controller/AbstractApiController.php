<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ListType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as ViewResponse;

abstract class AbstractApiController extends AbstractFOSRestController
{
    abstract public function getClass(): string;
    abstract public function getForm(): string;
    abstract public function getSecurityAccess(): string;

    /**
     * @View(serializerGroups={"list"})
    */
    public function listAll(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $form = $this->createForm(ListType::class);
        $form->submit($request->query->all(), true);

        if ($form->isValid()) {
            return $this->view($em->getRepository($this->getClass())->findBy(
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
     * @View(serializerGroups={"list"})
    */
    public function list(string $id, EntityManagerInterface $em): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $entity = $em->getRepository($this->getClass())->find($id);

        if ($entity) {
            return $this->view($entity);
        }

        return $this->view(null, 404);
    }

    /**
     * @View(serializerGroups={"list"})
    */
    public function create(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $className = $this->getClass();
        $entity = new $className();
        $form = $this->createForm($this->getForm(), $entity, [
            'required' => true
        ]);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->view($entity, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @View(serializerGroups={"list"})
    */
    public function delete(string $id, EntityManagerInterface $em): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $entity = $em->getRepository($this->getClass())->find($id);

        if ($entity) {
            $em->remove($entity);
            $em->flush();
            $em->clear($this->getClass());

            return $this->view($entity);
        }

        return $this->view(null, 400);
    }

    /**
     * @View(serializerGroups={"list"})
    */
    public function update(string $id, Request $request, EntityManagerInterface $em): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $entity = $em->getRepository($this->getClass())->find($id);

        if (!$entity) {
            return $this->view(null, 400);
        }

        $form = $this->createForm($this->getForm(), $entity, [
            'required'  => false,
            'method'    => 'PATCH'
        ]);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->view($entity);
        }

        return $this->view($form, 400);
    }

    /**
     * @View(serializerGroups={"list"})
    */
    public function replace(string $id, Request $request, EntityManagerInterface $em): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $entity = $em->getRepository($this->getClass())->find($id);

        if (!$entity) {
            return $this->view(null, 400);
        }

        $form = $this->createForm($this->getForm(), $entity, [
            'method' => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->view($entity);
        }

        return $this->view($form, 400);
    }
}
