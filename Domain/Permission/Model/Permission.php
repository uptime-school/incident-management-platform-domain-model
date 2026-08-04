<?php

declare(strict_types=1);

namespace Domain\Permission\Model;

use Domain\Role\Model\RoleCollection;

class Permission
{
    private string $code;
    private string $description;
    private RoleCollection $roles;

    public function __construct(string $code, string $description)
    {
        $this->code = $code;
        $this->description = $description;
        $this->roles = new RoleCollection();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRoles(): RoleCollection
    {
        return $this->roles;
    }
}
