<?php

declare(strict_types=1);

namespace App\Domain\Service\Model;

use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Service extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column]
        private string $name,
        #[ORM\Column(type: 'text')]
        private string $description
    ) {
        parent::__construct(new Id($id));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}