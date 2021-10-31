<?php

namespace DocMVC\FileManager;

use DocMVC\Assembly\AssemblyFileFactory;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Exception\Assembly\AssemblyFileFactory\AssemblyFileFactoryExceptionInterface;
use DocMVC\Exception\DocMVCException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\AssembledFileProcessorExceptionInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SetupCartridgeInterface $cartridge
     */
    public function __construct(SetupCartridgeInterface $cartridge, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
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
     * @throws DocMVCException
     */
    public function build(): AssembledFileProcessor
    {
        try {
            $fileAssembly = AssemblyFileFactory::createAssemblyFileByCartridge($this->currentCartridge);

            return new AssembledFileProcessor($fileAssembly, $this->processorConfig, $this->logger);
        } catch (AssemblyFileFactoryExceptionInterface|AssembledFileProcessorExceptionInterface $e) {
            $this->logger->error('DocMVC document build error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new DocMVCException($e->getMessage(), $e->getCode(), $e);
        }
    }
    /**
     * Enable rewrite existed file for save operations
     *
     * @param string $savePath
     *
     * @return self
     */
    public function rewritableModeOn(): self
    {
        $this->processorConfig->setRewritableMode(true);

        return $this;
    }

    /**
     * Disable rewrite existed file for save operations
     *
     * @return self
     */
    public function rewritableModeOff(): self
    {
        $this->processorConfig->setRewritableMode(false);

        return $this;
    }


}