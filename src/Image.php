<?php

namespace IsaEken\Picpurify;

use CURLFile;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use JetBrains\PhpStorm\Pure;

class Image
{
    /**
     * @param string $path
     * @return static
     * @throws FileNotFoundException
     */
    public static function createFromFile(string $path): static
    {
        $path = realpath($path);

        if (! file_exists($path)) {
            throw new FileNotFoundException;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mime = mime_content_type($path);

        return (new static)
            ->setRemote(false)
            ->setPath($path)
            ->setExtension($extension)
            ->setMime($mime);
    }

    /**
     * @param string $url
     * @return static
     */
    public static function createFromUrl(string $url): static
    {
        return (new static)
            ->setRemote(true)
            ->setUrl($url);
    }

    /**
     * @param string $path
     * @param string $url
     * @param string $mime
     * @param string $extension
     * @param bool   $remote
     */
    public function __construct(
        public string $path = '',
        public string $url = '',
        public string $mime = '',
        public string $extension = '',
        public bool $remote = false,
    )
    {
        // ...
    }

    /**
     * @return string
     */
    #[Pure] public function key(): string
    {
        return $this->isRemote() ? 'url_image' : 'file_image';
    }

    /**
     * @return CURLFile|string
     */
    #[Pure] public function parse(): CURLFile|string
    {
        if (! $this->isRemote()) {
            return curl_file_create($this->getPath(), $this->getMime());
        }

        return $this->getUrl();
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isRemote(): bool
    {
        return $this->remote;
    }

    /**
     * @param string $extension
     * @return Image
     */
    public function setExtension(string $extension): static
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @param string $mime
     * @return Image
     */
    public function setMime(string $mime): static
    {
        $this->mime = $mime;
        return $this;
    }

    /**
     * @param string $path
     * @return Image
     */
    public function setPath(string $path): static
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $url
     * @return Image
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param bool $remote
     * @return Image
     */
    public function setRemote(bool $remote): static
    {
        $this->remote = $remote;
        return $this;
    }
}
