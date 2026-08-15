<?php

declare(strict_types=1);

namespace App\Domain\PostmortemReport\Model;

use App\Domain\Common\Model\AbstractModel;
use App\Domain\Common\Model\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ReportFile extends AbstractModel
{
    public function __construct(
        string $id,
        #[ORM\Column]
        private readonly string $locationUrl,
        #[ORM\Column]
        private readonly string $contentType
    ) {
        parent::__construct(new Id($id));
    }

    public function getLocationUrl(): string
    {
        return $this->locationUrl;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }
}
