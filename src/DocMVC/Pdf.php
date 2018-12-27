<?php

namespace DocMVC;

use Exception;
use \Dompdf\Dompdf;

abstract class Pdf extends BaseDoc
{
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
    protected function allowedExt()
    {
        return [self::TYPE_PDF];
    }

    /**
     * Implement abstract method from parent class.
     * Specify the file extension.
     *
     * @return string
     */
    protected function setupFileExt()
    {
        return self::TYPE_PDF;
    }

    /**
     * Build file.
     * Create driver object, render content.
     *
     * @throws Exception
     */
    protected function buildDoc()
    {
        $this->driver = new DOMPDF();
        $this->driver->setPaper('A4', 'portrait');
        $content = $this->render();
        $this->driver->loadHtml($content);
        $this->driver->render();
    }

    protected function DLHeaders()
    {

    }

    /**
     * Streams the PDF to the client.
     */
    protected function DL()
    {
        $this->driver->stream($this->generateFileName(), [
            'Attachment' => true,
            'compression' => true
        ]);
    }
}