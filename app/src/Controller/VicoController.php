<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Vico;
use App\Form\VicoType;

class VicoController extends AbstractApiController
{
    public function getClass(): string
    {
        return Vico::class;
    }

    public function getForm(): string
    {
        return VicoType::class;
    }

    public function getSecurityAccess(): string
    {
        return 'ROLE_CREATOR';
    }
}
