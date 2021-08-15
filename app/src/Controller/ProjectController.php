<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Form\ListType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as ViewResponse;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class ProjectController extends AbstractFOSRestController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get Projects",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=App\Entity\Project::class, groups={"non_sensitive_data"}))
     *     )
     * )
     * @OA\Tag(name="project")
     * @View(serializerGroups={"list"})
     * @Route("/api/project", name="project_list_all", methods={"GET"})
    */
    public function listAll(Request $request, EntityManagerInterface $em): ViewResponse
    {
        $form = $this->createForm(ListType::class);
        $form->submit($request->query->all(), true);

        if ($form->isValid()) {
            return $this->view($em->getRepository(Project::class)->findBy(
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
     *     description="Get Project",
     *     @OA\Items(ref=@Model(type=App\Entity\Project::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="project")
     * @View(serializerGroups={"list"})
     * @Route("/api/project/{id}", name="project_list", methods={"GET"})
    */
    public function list(string $id, EntityManagerInterface $em): ViewResponse
    {
        $project = $em->getRepository(Project::class)->find($id);

        if ($project) {
            return $this->view($project);
        }

        return $this->view(null, 404);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create Project",
     *     @OA\Items(ref=@Model(type=App\Entity\Project::class, groups={"non_sensitive_data"}))
     * )
     * @OA\Tag(name="project")
     * @View(serializerGroups={"list"})
     * @Route("/api/project", name="project_create", methods={"POST"})
    */
    public function create(Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->view($project, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Delete Project"
     * )
     * @OA\Tag(name="project")
     * @View(serializerGroups={"list"})
     * @Route("/api/project/{id}", name="project_delete", methods={"DELETE"})
    */
    public function delete(string $id, EntityManagerInterface $em): ViewResponse
    {
        $project = $em->getRepository(Project::class)->find($id);

        if ($project) {
            $em->remove($project);
            $em->flush();
            $em->clear(Project::class);

            return $this->view($project);
        }

        return $this->view(null, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Patch Project"
     * )
     * @OA\Tag(name="project")
     * @View(serializerGroups={"list"})
     * @Route("/api/project/{id}", name="project_patch", methods={"PATCH"})
    */
    public function patch(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $project = $em->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(ProjectType::class, $project, [
            'required'  => false,
            'method'    => 'PATCH'
        ]);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->view($project);
        }

        return $this->view($form, 400);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Put Project"
     * )
     * @OA\Tag(name="project")
     * @View(serializerGroups={"list"})
     * @Route("/api/project/{id}", name="project_put", methods={"PUT"})
    */
    public function put(string $id, Request $request, EntityManagerInterface $em, PasswordHasherFactoryInterface $encoderFactory): ViewResponse
    {
        $project = $em->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(ProjectType::class, $project, [
            'method' => 'PUT'
        ]);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->view($project);
        }

        return $this->view($form, 400);
    }
}
