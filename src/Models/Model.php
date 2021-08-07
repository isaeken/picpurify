<?php

namespace IsaEken\Picpurify\Models;

use Illuminate\Support\Str;
use InvalidArgumentException;

class Model
{
    protected array $casts = [];

    protected array $attributes = [];

    protected array $custom_setters = [];

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes) || array_key_exists($name, $this->casts);
    }

    public function setAttribute(string $name, mixed $value): static
    {
        if (array_key_exists($name, $this->custom_setters) && is_callable($this->custom_setters[$name])) {
            $value = $this->custom_setters[$name]($value);
        }

        if (array_key_exists($name, $this->casts)) {
            $casts = [
                'string', 'array', 'object', 'bool', 'boolean', 'callable', 'countable', 'dir', 'double', 'executable',
                'float', 'file', 'finite', 'int', 'integer', 'iterable', 'link', 'long', 'nan', 'null', 'numeric',
            ];
            $cast = $this->casts[$name];

            if (in_array($cast, $casts)) {
                if (!('is_' . $cast)($value)) {
                    throw new InvalidArgumentException;
                }

                $this->attributes[$name] = $value;
                return $this;
            }

            if ($value instanceof $cast) {
                $this->attributes[$name] = $value;
                return $this;
            }

            return $this;
        }

        $this->attributes[$name] = $value;
        return $this;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return $default;
    }

    public function fill(array $attributes): static
    {
        foreach ($attributes as $name => $value) {
            $name = Str::of($name)->snake()->__toString();
            $this->setAttribute($name, $value);
        }

        return $this;
    }

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function __get(string $name)
    {
        if ($this->hasAttribute($name)) {
            return $this->getAttribute($name);
        }

        return $this->$name;
    }

    public function __set(string $name, $value): void
    {
        if ($this->hasAttribute($name)) {
            $this->setAttribute($name, $value);
            return;
        }

        $this->$name = $value;
    }

    public function __call(string $name, array $arguments)
    {
        $name = Str::of($name);
        if ($name != 'setAttribute' && ($name->startsWith('set') || $name->startsWith('get')) && $name->endsWith('Attribute')) {
            $set = $name->startsWith('set');
            $name = $name->substr(strlen('set'))->substr(0, -strlen('attribute'))->snake()->lower();

            if ($set) {
                return $this->setAttribute($name, $arguments[0]);
            }

            return $this->getAttribute($name);
        }

        $name = $name->__toString();
        return $this->$name($arguments);
    }
}
