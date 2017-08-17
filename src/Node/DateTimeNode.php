<?php

declare(strict_types=1);

namespace Linio\Component\Input\Node;

use Linio\Component\Input\Transformer\DateTimeTransformer;

class DateTimeNode extends BaseNode
{
    public function __construct()
    {
        $this->type = 'datetime';
        $this->transformer = new DateTimeTransformer();
    }
}
