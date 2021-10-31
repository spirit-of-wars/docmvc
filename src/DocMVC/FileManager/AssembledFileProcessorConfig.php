<?php

namespace DocMVC\FileManager;

class AssembledFileProcessorConfig
{
    /**
     * allows rewrite existed file for save operations
     *
     * @var boolean
     */
    private $rewritableMode = false;

    /**
     * @return bool
     */
    public function getRewritableMode(): bool
    {
        return $this->rewritableMode;
    }

    /**
     * @param bool $rewritableMode
     * @return $this
     */
    public function setRewritableMode(bool $rewritableMode): self
    {
        $this->rewritableMode = $rewritableMode;

        return $this;
    }
}