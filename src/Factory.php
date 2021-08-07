<?php

namespace IsaEken\Picpurify;

use IsaEken\Picpurify\Traits\HasAttributes;
use IsaEken\Picpurify\Traits\HasGuzzleClient;
use IsaEken\Picpurify\Traits\HasTasks;

abstract class Factory
{
    use HasGuzzleClient;
    use HasAttributes;
    use HasTasks;
}
