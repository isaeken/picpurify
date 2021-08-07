<?php

namespace IsaEken\Picpurify\Traits;

use GuzzleHttp\Client;
use JetBrains\PhpStorm\Pure;

trait HasGuzzleClient
{
    /**
     * @var array $options
     */
    protected array $options = [];

    /**
     * @var Client|null $client
     */
    private Client|null $client = null;

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return $this
     */
    public function setOption(string $name, mixed $value): static
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * @param string     $name
     * @param mixed|null $default
     * @return mixed
     */
    #[Pure] public function getOption(string $name, mixed $default = null): mixed
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        }

        return $default;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function fillOptions(array $options): static
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client ?? $this->makeClient();
    }

    /**
     * @return Client
     */
    public function makeClient(): Client
    {
        return $this->client = new Client($this->options);
    }
}
