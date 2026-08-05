<?php

declare(strict_types=1);

namespace App\Domain\Common\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class AbstractModel
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'string', length: 36)]
    private readonly string $idValue;

    protected Id $id;

    public function __construct(Id $id)
    {
        $this->id = $id;
        $this->idValue = $id->getValue();
    }

    public function getId(): ?string
    {
        return $this->id->getValue();
    }

    #[ORM\PostLoad]
    public function hydrateId(): void
    {
        $this->id = new Id($this->idValue);
    }
}
