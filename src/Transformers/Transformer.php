<?php

namespace Bengr\Routing\Transformers;

use Illuminate\Support\Collection;

interface Transformer
{

    public function transform(Collection $routes): Collection;
}
