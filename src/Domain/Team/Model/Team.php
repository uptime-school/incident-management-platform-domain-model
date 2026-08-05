<?php

declare(strict_types=1);

namespace App\Domain\Team\Model;

use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Team extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column]
        private readonly string $name
    ) {
        parent::__construct(new Id($id));
    }

    public function getName(): string
    {
        return $this->name;
    }
}
