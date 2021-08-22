<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;

class ClientController extends AbstractUserApiController
{
    public function getClass(): string
    {
        return Client::class;
    }

    public function getForm(): string
    {
        return ClientType::class;
    }

    public function getSecurityAccess(): string
    {
        return 'ROLE_ADMIN';
    }
}
