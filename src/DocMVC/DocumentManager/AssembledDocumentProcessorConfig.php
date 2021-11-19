<?php

namespace SpiritOfWars\DocMVC\DocumentManager;

class AssembledDocumentProcessorConfig
{
    /**
     * allows rewrite existed document for save operations
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
     *
     * @return $this
     */
    public function setRewritableMode(bool $rewritableMode): self
    {
        $this->rewritableMode = $rewritableMode;

        return $this;
    }
}