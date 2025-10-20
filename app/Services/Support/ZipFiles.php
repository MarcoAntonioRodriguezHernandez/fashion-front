<?php

namespace App\Services\Support;

use ZipArchive;

class ZipFiles
{
    /**
     * @var array
     */
    protected array $files;
    /**
     * @var string
     */
    protected string $defaultExtension;

    public static function make(array $files, string $defaultExtension = null): self
    {
        return new static($files, $defaultExtension);
    }

    public function __construct(array $files, string $defaultExtension = null)
    {
        $this->files = $files;
        $this->defaultExtension = $defaultExtension ?? 'dat';
    }

    /**
     * Add files to the zip
     */
    public function addFiles(array $files): self
    {
        $this->files = array_merge($this->files, $files);

        return $this;
    }

    /**
     * Add a file to the zip
     */
    public function addFile(string $name, mixed $file): self
    {
        return $this->addFiles([$name => $file]);
    }

    /**
     * Get the zip file with the given path and files
     */
    public function get(string $path, callable $getContent = null)
    {
        $zipContainer = $this->createZipFile($path);

        $this->zipFiles($this->files, $zipContainer, $getContent ?? fn($file) => $file->getContents());

        $zipContainer->close();

        return $zipContainer;
    }

    /**
     * Set the default extension for the files
     */
    public function extension(string $extension): self
    {
        $this->defaultExtension = $extension;

        return $this;
    }

    /**
     * Create a new zip file
     * 
     * @param string $path
     * 
     * @return ZipArchive
     */
    protected function createZipFile(string $path): ZipArchive
    {
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);

        return $zip;
    }

    /**
     * Zip the PDF files
     * 
     * @param array $files
     * @param string $zipPath
     * 
     * @return ZipArchive
     */
    protected function zipFiles(array $files, ZipArchive $zipContainer, callable $getContent)
    {
        foreach ($files as $fileName => $file) {
            // If name already has extension, use it
            $fileName = pathinfo($fileName, PATHINFO_EXTENSION) ? $fileName : $fileName . '.' . $this->defaultExtension;

            $zipContainer->addFromString($fileName, $getContent($file));
        }

        return $zipContainer;
    }
}
