<?php

declare(strict_types=1);

namespace App\Domain\User\Model;

use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column]
        private readonly string $name,
        #[ORM\Embedded(class: EmailAddress::class)]
        private readonly EmailAddress $email
    ) {
        parent::__construct(new Id($id));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }
}
