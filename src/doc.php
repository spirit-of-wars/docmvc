<?php

namespace DocMVC\Src;

use \Exception;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\TemplateProcessor;


abstract class Doc extends BaseDoc
{
    /**
     * Extension types
     *
     * @const string
     */
    const TYPE_DOC = 'doc';
    const TYPE_DOCX = 'docx';

    /**
     * Object to work with file
     *
     * @var TemplateProcessor|PhpWord
     */
    protected $driver;

    /**
     * Default file extension
     *
     * @var string
     */
    protected $defaultExt = self::TYPE_DOCX;

    /**
     * Get allowed extensions
     *
     * @return array
     */
    protected function allowedExt()
    {
        return [self::TYPE_DOC, self::TYPE_DOCX];
    }

    /**
     * Implement abstract method from parent class.
     * Specify the file extension.
     * Can be redefined in child class.
     * Must match one of the values in method allowedExt.
     *
     * @return string
     */
    protected function setupFileExt()
    {
        return $this->defaultExt;
    }

    /**
     * Build file.
     * Create driver object, render content and save file.
     *
     * @throws Exception
     */
    protected function buildDoc()
    {
        $this->driver = $this->createDriver();
        $this->render();
        $this->tmpName = $this->getCurrPath() . uniqid() . '.' .$this->getfileExt();

        $this->saveDoc($this->tmpName);
        if(file_exists($this->tmpName)) {
            $this->content = file_get_contents($this->tmpName);
        } else {
            throw new Exception('Failed creation file: ' . $this->tmpName );
        }

    }

    /**
     * Create driver object for work with file
     *
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     *
     * @return mixed
     */
    protected function createDriver()
    {
        if(!$this->getTemplatePath()) {
            return new PHPWord();
        }
        return new TemplateProcessor($this->getTemplatePath());
    }

    /**
     * Save file to path
     *
     * @throws Exception
     */
    protected function saveDoc($savePath)
    {
        if(!$this->getTemplatePath()) {
            $this->driver->save($savePath);
        } else {
            $this->driver->saveAs($savePath);
        }
    }

    /**
     * Generate headers for download file
     */
    protected function DLHeaders()
    {
        $fileName = $this->generateFileName();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
    }

    /**
     * Echo file content
     */
    protected function DL()
    {
        echo $this->getContent();
    }
}