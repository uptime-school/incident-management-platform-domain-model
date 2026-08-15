<?php

declare(strict_types=1);

namespace App\Domain\Permission\Model;

use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Permission extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column]
        private string $code,
        #[ORM\Column(type: 'text')]
        private string $description
    ) {
        parent::__construct(new Id($id));
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
