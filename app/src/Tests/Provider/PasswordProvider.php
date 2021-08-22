<?php

declare(strict_types=1);

namespace App\Tests\Provider;

use Faker\Generator;
use Faker\Provider\Base;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class PasswordProvider extends Base
{
    private PasswordHasherFactoryInterface $encoderFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(Generator $generator, PasswordHasherFactoryInterface $encoderFactory)
    {
        parent::__construct($generator);
        $this->encoderFactory = $encoderFactory;
    }

    public function passwordHasher(string $userClass, string $plainPassword): string
    {
        return $this->generator->parse(
            $this->encoderFactory->getPasswordHasher($userClass)->hash($plainPassword)
        );
    }
}
