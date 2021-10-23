<?php

namespace DocMVC\Assembly;

use DocMVC\Exception\Assembly\AssemblyFile\BuildFileException;
use DocMVC\Exception\Assembly\AssemblyFile\DownloadFileException;
use DocMVC\traits\RenderTrait;
use \Dompdf\Dompdf;

class PdfAssembly extends AbstractFileAssembly
{
    use RenderTrait;

    /**
     * Extension type
     *
     * @const string
     */
    const TYPE_PDF = 'pdf';

    /**
     * Object to work with file
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
     * Build file.
     * Create driver object, render content.
     *
     * @throws BuildFileException
     */
    public function buildFile(): void
    {
        try {
            $this->driver->setPaper('A4', 'portrait');
            $content = $this->render($this->getDriver(), $this->getFileInfo()->getModel(), $this->getFileInfo()->getViewPath(), $this->getFileInfo()->getParams());
            $this->driver->loadHtml($content);
            $this->driver->render();
        } catch (\Throwable $e) {
            throw new BuildFileException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Streams the PDF to the client.
     *
     * @throws DownloadFileException
     */
    public function download(): void
    {
        try {
            $this->driver->stream($this->getFileInfo()->getFileName(), [
                'Attachment' => true,
                'compression' => true
            ]);
        } catch (\Throwable $e) {
            throw new DownloadFileException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return DOMPDF
     */
    protected function createDriver(): object
    {
        return new DOMPDF();
    }
}