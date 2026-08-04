<?php

declare(strict_types=1);

namespace Domain\Team\Model;

use Domain\User\Model\UserCollection;

class Team
{
    private string $id;
    private string $name;
    private UserCollection $users;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->users = new UserCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsers(): UserCollection
    {
        return $this->users;
    }
}
