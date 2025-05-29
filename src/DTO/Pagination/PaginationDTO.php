<?php

namespace App\DTO\Pagination;
use Symfony\Component\Validator\Constraints as Assert;


class PaginationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        private readonly int $page = 1,
        #[Assert\NotBlank]
        #[Assert\Positive]
        private readonly int $limit = 10,
    ) {
    }
    public function getPage(): int
    {
        return $this->page;
    }
    public function getLimit(): int
    {
        return $this->limit;
    }

}
