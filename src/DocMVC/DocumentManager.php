<?php

namespace SpiritOfWars\DocMVC;

use SpiritOfWars\DocMVC\Assembly\DocumentAssemblyFactory;
use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;
use SpiritOfWars\DocMVC\DocumentManager\AssembledDocumentProcessor;
use SpiritOfWars\DocMVC\DocumentManager\AssembledDocumentProcessorConfig;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentFactoryExceptionInterface;
use SpiritOfWars\DocMVC\Exception\DocMVCException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class DocumentManager
{
    /**
     * @var SetupCartridgeInterface
     */
    private $currentCartridge;

    /**
     * @var AssembledDocumentProcessorConfig
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
        $this->processorConfig = new AssembledDocumentProcessorConfig();

        return $this;
    }

    /**
     * @return AssembledDocumentProcessor
     * @throws DocMVCException
     */
    public function build(): AssembledDocumentProcessor
    {
        try {
            $documentAssembly = DocumentAssemblyFactory::createAssemblyDocumentByCartridge($this->currentCartridge);
            $documentAssemblyResult = $documentAssembly->buildDocument();

            return new AssembledDocumentProcessor($documentAssemblyResult, $this->processorConfig, $this->logger);
        } catch (AssemblyDocumentFactoryExceptionInterface $e) {
            $this->logger->error('DocMVC document build error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new DocMVCException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Enable rewrite existed document for save operations
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
     * Disable rewrite existed document for save operations
     *
     * @return self
     */
    public function rewritableModeOff(): self
    {
        $this->processorConfig->setRewritableMode(false);

        return $this;
    }
}