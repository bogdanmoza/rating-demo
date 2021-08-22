<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;

class MemberController extends AbstractUserApiController
{
    public function getClass(): string
    {
        return Member::class;
    }

    public function getForm(): string
    {
        return MemberType::class;
    }

    public function getSecurityAccess(): string
    {
        return 'ROLE_CREATOR';
    }
}
