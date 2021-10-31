<?php

namespace DocMVC\Assembly;

use DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use DocMVC\Exception\Assembly\AssemblyDocument\DownloadDocumentException;
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
     * Object to work with document
     *
     * @var Dompdf
     */
    protected $driver;

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
    public function buildDocument(): void
    {
        try {
            $this->driver->setPaper('A4', 'portrait');
            $content = $this->documentRenderer->renderFromView($this->getDriver(), $this->getDocumentInfo()->getModel(), $this->getDocumentInfo()->getViewPath(), $this->getDocumentInfo()->getParams());
            $this->driver->loadHtml($content);
            $this->driver->render();
            $content = $this->driver->output();
            $this->saveToTmpDocument($content);
        } catch (\Throwable $e) {
            throw new BuildDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Streams the PDF to the client.
     *
     * @throws DownloadDocumentException
     */
    public function download(): void
    {
        try {
            $this->driver->stream($this->getDocumentInfo()->getDocumentName(), [
                'Attachment' => true,
                'compression' => true
            ]);
        } catch (\Throwable $e) {
            throw new DownloadDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return DOMPDF
     */
    protected function createDriver(): object
    {
        return new DOMPDF();
    }

    private function saveToTmpDocument($content)
    {
        file_put_contents($this->getDocumentInfo()->getTmpDocumentPath(), $content);
    }
}