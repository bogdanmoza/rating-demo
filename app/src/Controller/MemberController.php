<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
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

class MemberController extends AbstractFOSRestController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get Members",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\Member::class, groups={"non_sensitive_data"}))
     *     )
     * )
     * @OA\Tag(name="member")
     * @View(serializerGroups={"list"})
     * @Route("/api/member", name="member_list_all", methods={"GET"})
    */
    public function listAll(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $form = $this->createForm(ListType::class);
        $form->submit($request->query->all(), true);

        if ($form->isValid()) {
            return $this->view($em->getRepository(Member::class)->findBy(
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
     *     description="Get Member",
     *     @OA\Items(ref=@Model(type=App\Entity\Member::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="member")
     * @View(serializerGroups={"list"})
     * @Route("/api/member/{id}", name="member_list", methods={"GET"})
    */
    public function list(string $id, EntityManagerInterface $em): ViewResponse
    {
        $member = $em->getRepository(Member::class)->find($id);

        if ($member) {
            return $this->view($member);
        }

        return $this->view(null, 404);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create Member",
     *     @OA\Items(ref=@Model(type=App\Entity\Member::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="member")
     * @View(serializerGroups={"list"})
     * @Route("/api/member", name="member_create", methods={"POST"})
    */
    public function create(Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $member->setPassword(
                $encoderFactory
                    ->getPasswordHasher(Member::class)
                    ->hash($form->get('plainPassword')->getData())
            );

            $em->persist($member);
            $em->flush();

            return $this->view($member, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Delete Member"
     * )
     * @OA\Tag(name="member")
     * @View(serializerGroups={"list"})
     * @Route("/api/member/{id}", name="member_delete", methods={"DELETE"})
    */
    public function delete(string $id, EntityManagerInterface $em): ViewResponse
    {
        $member = $em->getRepository(Member::class)->find($id);

        if ($member) {
            $em->remove($member);
            $em->flush();
            $em->clear(Member::class);

            return $this->view($member);
        }

        return $this->view(null, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Patch Member"
     * )
     * @OA\Tag(name="member")
     * @View(serializerGroups={"list"})
     * @Route("/api/member/{id}", name="member_patch", methods={"PATCH"})
    */
    public function patch(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $member = $em->getRepository(Member::class)->find($id);

        if (!$member) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(MemberType::class, $member, [
            'required'  => false,
            'method'    => 'PATCH'
        ]);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $member->setPassword(
                    $encoderFactory
                    ->getPasswordHasher(Member::class)
                    ->hash($form->get('plainPassword')->getData())
                );
            }

            $em->persist($member);
            $em->flush();

            return $this->view($member);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Put Member"
     * )
     * @OA\Tag(name="member")
     * @View(serializerGroups={"list"})
     * @Route("/api/member/{id}", name="member_put", methods={"PUT"})
    */
    public function put(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $member = $em->getRepository(Member::class)->find($id);

        if (!$member) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(MemberType::class, $member, [
            'method' => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $member->setPassword(
                $encoderFactory
                    ->getPasswordHasher(Member::class)
                    ->hash($form->get('plainPassword')->getData())
            );
            $em->persist($member);
            $em->flush();

            return $this->view($member);
        }

        return $this->view($form, 400);
    }
}
