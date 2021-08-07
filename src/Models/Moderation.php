<?php

namespace IsaEken\Picpurify\Models;

/**
 * @property string $name
 * @property float $score
 * @property float $time
 * @property bool $detection
 */
class Moderation extends Model
{
    /**
     * @var string[] $casts
     */
    public array $casts = [
        'name' => 'string',
        'score' => 'float',
        'time' => 'float',
        'detection' => 'bool',
    ];

    public static function make(array $values): static
    {
        $moderation = new static;

        foreach ($values as $key => $value) {
            $key = mb_strtolower($key);

            if ($key == 'confidence_score') {
                $moderation->setAttribute('score', (float) $value);
            }
            else if ($key == 'compute_time') {
                $moderation->setAttribute('time', (float) $value);
            }
            else if (str_ends_with($key, '_content')) {
                $moderation->setAttribute('name', explode('_', $key)[0]);
                $moderation->setAttribute('detection', (bool) $value);
            }
        }

        return $moderation;
    }
}
