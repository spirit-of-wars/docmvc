<?php

namespace DocMVC\Src;

use \Exception;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\TemplateProcessor;


abstract class Doc extends BaseDoc
{
    const TYPE_DOC = 'doc';
    const TYPE_DOCX = 'docx';

    protected $defaultExt = self::TYPE_DOCX;

    public function __construct(array $params)
    {
        parent::__construct($params);
    }

    protected function allowedExt()
    {
        return [self::TYPE_DOC, self::TYPE_DOCX];
    }

    protected function setFileExt()
    {
        return self::TYPE_DOCX;
    }

    protected function buildDoc()
    {
        $this->driver = $this->createDriver();
        $this->render();
        $this->tmpName = $this->getBasicTemplatePath() . uniqid() . '.' .$this->getfileExt();
        $this->saveDoc($this->tmpName);
        if(file_exists($this->tmpName)) {
            $this->content = file_get_contents($this->tmpName);
        } else {
            throw new Exception('Failed creation file: ' . $this->tmpName );
        }

    }

    protected function createDriver()
    {
        if(!$this->getTemplatePath()) {
            return new PHPWord();
        }
        return new TemplateProcessor($this->getTemplatePath());
    }

    protected function saveDoc($savePath)
    {
        if(!$this->getTemplatePath()) {
            $this->driver->save($savePath);
        } else {
            $this->driver->saveAs($savePath);
        }
    }

    protected function DLHeaders()
    {
        $fileName = $this->generateFileName();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
    }

    protected function DL()
    {
        echo $this->getContent();
    }
}