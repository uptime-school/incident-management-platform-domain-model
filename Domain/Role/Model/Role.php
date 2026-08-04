<?php

declare(strict_types=1);

namespace Domain\Role\Model;

use Domain\Permission\Model\PermissionCollection;
use Domain\User\Model\UserCollection;

class Role
{
    private string $name;
    private string $description;
    private UserCollection $users;
    private PermissionCollection $permissions;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
        $this->users = new UserCollection();
        $this->permissions = new PermissionCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUsers(): UserCollection
    {
        return $this->users;
    }

    public function getPermissions(): PermissionCollection
    {
        return $this->permissions;
    }
}
