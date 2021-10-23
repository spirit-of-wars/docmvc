<?php

namespace DocMVC\FileManager;

use DocMVC\Assembly\AssemblyFileFactory;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Exception\Assembly\AssemblyFileFactory\AssemblyFileFactoryExceptionInterface;
use DocMVC\Exception\DocMVCException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\AssembledFileProcessorInterface;
use \Exception;

class FileManager
{
    /**
     * @var SetupCartridgeInterface
     */
    private $currentCartridge;

    /**
     * @var AssembledFileProcessorConfig
     */
    private $processorConfig;

    /**
     * @param SetupCartridgeInterface $cartridge
     */
    public function __construct(SetupCartridgeInterface $cartridge)
    {
        $this->load($cartridge);
    }

    /**
     * @param SetupCartridgeInterface $cartridge
     */
    public function load(SetupCartridgeInterface $cartridge): self
    {
        $this->currentCartridge = $cartridge;
        $this->processorConfig = new AssembledFileProcessorConfig();

        return $this;
    }

    /**
     * @return AssembledFileProcessor
     * @throws Exception
     */
    public function build(): AssembledFileProcessor
    {
        try {
            $fileAssembly = AssemblyFileFactory::createAssemblyFileByCartridge($this->currentCartridge);

            return new AssembledFileProcessor($fileAssembly, $this->processorConfig);
        } catch (AssemblyFileFactoryExceptionInterface|AssembledFileProcessorInterface $e) {
            throw new DocMVCException($e->getMessage(), $e->getCode(), $e);
        }
    }
    /**
     * Enable save file on server after work
     *
     * @param string $savePath
     *
     * @return self
     */
    public function saveOn(): self
    {
        $this->processorConfig->setIsSaveFile(true);

        return $this;
    }

    /**
     * Disable save file on server after work
     *
     * @return self
     */
    public function saveOff(): self
    {
        $this->processorConfig->setIsSaveFile(false);

        return $this;
    }


}