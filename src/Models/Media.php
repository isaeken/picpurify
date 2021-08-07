<?php

namespace IsaEken\Picpurify\Models;

class Media extends Model
{
    public array $casts = [
        'url_image' => 'string',
        'file_image' => 'string',
        'media_id' => 'string',
        'origin_id' => 'string',
        'reference_id' => 'string',
    ];
}
