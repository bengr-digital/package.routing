<?php

namespace Bengr\Routing\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Prefix implements Attribute
{
    public function __construct(
        public string $prefix
    ) {
    }
}
