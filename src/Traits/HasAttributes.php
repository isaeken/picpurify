<?php

namespace IsaEken\Picpurify\Traits;

use Illuminate\Support\Str;

trait HasAttributes
{
    /**
     * @var array $attributes
     */
    protected array $attributes = [];

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return HasAttributes
     */
    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return $this->$name;
    }

    /**
     * @param string $name
     * @param        $value
     */
    public function __set(string $name, $value): void
    {
        if (array_key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
            return;
        }

        $this->$name = $value;
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return $this|mixed
     */
    public function __call(string $name, array $arguments)
    {
        $_ = Str::of($name);
        if ($_->startsWith(['set', 'get'])) {
            $get = $_->startsWith('get');
            $_ = $_->substr(strlen('get'))->snake();

            if (array_key_exists($_->__toString(), $this->attributes)) {
                if ($get) {
                    return $this->attributes[$_->__toString()];
                }

                $this->attributes[$_->__toString()] = $arguments[0];
            }

            return $this;
        }

        return $this->$name($arguments);
    }
}
