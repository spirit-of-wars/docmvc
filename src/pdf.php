<?php

namespace DocMVC\Src;

use \Dompdf\Dompdf;

abstract class PDF extends BaseDoc
{
    const TYPE_PDF = 'pdf';

    public function __construct(array $params)
    {
        parent::__construct($params);
    }

    protected function allowedExt()
    {
        return [self::TYPE_PDF];
    }

    protected function setFileExt()
    {
        return self::TYPE_PDF;
    }

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

    protected function DL()
    {
        $this->driver->stream($this->generateFileName(), [
            'Attachment' => true,
            'compression' => true
        ]);
    }
}