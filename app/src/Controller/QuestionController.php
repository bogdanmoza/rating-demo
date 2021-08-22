<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;

class QuestionController extends AbstractApiController
{
    public function getClass(): string
    {
        return Question::class;
    }

    public function getForm(): string
    {
        return QuestionType::class;
    }

    public function getSecurityAccess(): string
    {
        return 'ROLE_CREATOR';
    }
}
