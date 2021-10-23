<?php

namespace DocMVC\FileManager;

class AssembledFileProcessorConfig
{
    /**
     * Is save file (true) or remove, after destruct class (false)
     *
     * @var boolean
     */
    private $isSaveFile = false;

    /**
     * @return bool
     */
    public function getIsSaveFile(): bool
    {
        return $this->isSaveFile;
    }

    /**
     * @param bool $isSaveFile
     * @return $this
     */
    public function setIsSaveFile(bool $isSaveFile): self
    {
        $this->isSaveFile = $isSaveFile;

        return $this;
    }
}