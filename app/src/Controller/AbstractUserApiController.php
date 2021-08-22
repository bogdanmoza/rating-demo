<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use FOS\RestBundle\View\View as ViewResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;

abstract class AbstractUserApiController extends AbstractApiController
{
    /**
     * @View(serializerGroups={"list"})
    */
    public function create(Request $request, EntityManagerInterface $em, ?PasswordHasherFactoryInterface $encoderFactory = null): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $className = $this->getClass();
        $entity = new $className();
        $form = $this->createForm($this->getForm(), $entity, [
            'required' => true
        ]);
        $form->submit(json_decode($request->getContent(), true), true);
        if ($form->isValid()) {
            $entity->setPassword(
                $encoderFactory
                    ->getPasswordHasher($this->getClass())
                    ->hash($form->get('plainPassword')->getData())
            );

            $em->persist($entity);
            $em->flush();

            return $this->view($entity, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @View(serializerGroups={"list"})
    */
    public function update(string $id, Request $request, EntityManagerInterface $em, ?PasswordHasherFactoryInterface $encoderFactory = null): ViewResponse
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
        $entity->setPlainPassword($entity->getPassword());

        $form->submit(json_decode($request->getContent(), true), false);
        if ($form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $entity->setPassword(
                    $encoderFactory
                    ->getPasswordHasher($this->getClass())
                    ->hash($form->get('plainPassword')->getData())
                );
            }

            $em->persist($entity);
            $em->flush();

            return $this->view($entity);
        }

        return $this->view($form, 400);
    }

    /**
     * @View(serializerGroups={"list"})
    */
    public function replace(string $id, Request $request, EntityManagerInterface $em, ?PasswordHasherFactoryInterface $encoderFactory = null): ViewResponse
    {
        $this->denyAccessUnlessGranted($this->getSecurityAccess());

        $entity = $em->getRepository($this->getClass())->find($id);

        if (!$entity) {
            return $this->view(null, 400);
        }

        $form = $this->createForm($this->getForm(), $entity, [
            'required'  => true,
            'method'    => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true), true);

        if ($form->isValid()) {
            $entity->setPassword(
                $encoderFactory
                    ->getPasswordHasher($this->getClass())
                    ->hash($form->get('plainPassword')->getData())
            );
            $em->persist($entity);
            $em->flush();

            return $this->view($entity);
        }

        return $this->view($form, 400);
    }
}
