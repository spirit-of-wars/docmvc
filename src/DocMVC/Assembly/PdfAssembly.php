<?php

namespace SpiritOfWars\DocMVC\Assembly;

use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\PdfAssemblyResult;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use \Dompdf\Dompdf;

class PdfAssembly extends AbstractDocumentAssembly
{

    /**
     * Extension type
     *
     * @const string
     */
    public const TYPE_PDF = 'pdf';

    /**
     * Get allowed extensions
     *
     * @return array
     */
    public static function allowedExt(): array
    {
        return [self::TYPE_PDF];
    }

    /**
     * @return string
     */
    public static function defaultExt(): string
    {
        return self::TYPE_PDF;
    }

    /**
     * Build document.
     * Create driver object, render content.
     *
     * @throws BuildDocumentException
     */
    public function buildDocument(): DocumentAssemblyResultInterface
    {
        try {
            $driver = $this->createDriver();
            $driver->setPaper('A4', 'portrait');
            $viewContent = $this->renderFromView($driver, $this->documentInfo);
            $driver->loadHtml($viewContent);
            $driver->render();
            $content = $driver->output();
            $this->saveToTmpDocument($content);

            return new PdfAssemblyResult($this->documentInfo, $driver);
        } catch (\Throwable $e) {
            throw new BuildDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return DOMPDF
     */
    protected function createDriver(): object
    {
        return new DOMPDF();
    }

    /**
     * @param string $content
     */
    private function saveToTmpDocument(string $content)
    {
        if (!file_put_contents($this->documentInfo->getTmpDocumentPath(), $content)) {
            throw new BuildDocumentException(sprintf("Save content to tmp '%s' file has been failed", $this->documentInfo->getTmpDocumentPath()));
        }
    }
}