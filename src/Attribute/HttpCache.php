<?php

namespace App\Attribute;
use Attribute;


#[Attribute(Attribute::TARGET_METHOD)]
class HttpCache
{
    public function __construct(
        public ?int $maxage = null,
        public ?int $smaxage = null,
        public bool $public = true
    ) {
    }
}
