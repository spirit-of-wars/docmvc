<?php

namespace SpiritOfWars\DocMVC\Cartridge;

interface SetupCartridgeInterface extends CartridgeInterface
{
    /**
     * @return array
     */
    public function setupModel();

    /**
     * Specify the document extension.
     * Must match one of the values in method allowedExt from related assembly class.
     *
     * @return string|null
     */
    public function setupDocumentExt();

    /**
     * @return array
     */
    public function setupRequiredParams();

    /**
     * @return string
     */
    public function setupView();

    /**
     * Setup document name without extension
     *
     * @return string|null
     */
    public function setupDocumentName();

}