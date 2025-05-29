<?php

namespace App\DTO\Pagination;

class PaginationResponseDTO
{
    public function __construct(
        private readonly int $page,
        private readonly int $limit,
        private readonly int $total,
        private readonly int $pages,
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

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPages(): int
    {
        return $this->pages;
    }
}
