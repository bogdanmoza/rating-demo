<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;

class ProjectController extends AbstractApiController
{
    public function getClass(): string
    {
        return Project::class;
    }

    public function getForm(): string
    {
        return ProjectType::class;
    }

    public function getSecurityAccess(): string
    {
        return 'ROLE_CREATOR';
    }
}
