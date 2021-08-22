<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;

class RatingController extends AbstractApiController
{
    public function getClass(): string
    {
        return Rating::class;
    }

    public function getForm(): string
    {
        return RatingType::class;
    }

    public function getSecurityAccess(): string
    {
        return 'ROLE_CLIENT';
    }
}
