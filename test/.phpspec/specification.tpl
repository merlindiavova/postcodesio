<?php

declare(strict_types=1);

namespace %namespace%;

use %subject%;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class %name% extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(%subject_class%::class);
    }
}
