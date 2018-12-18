<?php

namespace DocMVC\Src;

use Exception;

abstract class BaseDoc
{

    private $fileExt;
    protected $fileNameForUser;
    protected $tmpName;
    protected $driver;
    protected $params;

    private $model;
    private $viewPath;
    private $templatePath;

    protected $isSaveFile = false;
    /**
     * Содержимое документа
     * @var string
     */
    protected $content;

    public function __construct(array $params)
    {


        try {
            $this->initFileExt();
            $this->initParams($params);
            $this->init();
            $this->buildDoc();
        } catch (\Exception $e) {
            $this->removeFile();
            throw new \Exception($e->getMessage());
        }
    }

    public function __destruct()
    {
        if (!$this->isSaveFile) {
            $this->removeFile();
        }
    }

    public function getViewPath()
    {
        return $this->viewPath;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getFileExt()
    {
        return $this->fileExt;
    }

    private function initFileExt()
    {
        $fileExt = $this->setFileExt();
        if(!in_array($fileExt, $this->allowedExt())) {
            throw new Exception('File Extension ' . $fileExt . 'is not allowed');
        }
        $this->fileExt = $fileExt;

        //@todo здесь прыгаем между наборов заголовков ^^
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTmpName()
    {
        return $this->tmpName;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function removeFile()
    {
        if(file_exists($this->tmpName)) {
            unlink($this->tmpName);
        }
    }

    public function download()
    {
        $this->DLHeaders();
        $this->DL();
        exit();
    }

    protected function getCurrPath()
    {
        $class_info = new \ReflectionClass($this);
        $fileName = preg_replace('/\\\\/','/',$class_info->getFileName());
        $fileNameArr = explode('/',$fileName);
        array_pop($fileNameArr);
        $currPath = implode('/',$fileNameArr);
        return $currPath;
    }

    protected function getBasicViewPath()
    {
        return $this->getCurrPath() . '/view/';
    }

    protected function getBasicTemplatePath()
    {
        return $this->getCurrPath() . '/template/';
    }

    protected abstract function DLHeaders();
    protected abstract function DL();
    protected abstract function buildDoc();
    protected abstract function allowedExt();

    protected abstract function setView();
    protected abstract function setFileExt();
    protected abstract function setModel();
    protected abstract function setTemplate();
    protected abstract function setRequiredParams();

    protected function init()
    {
        $this->model = $this->setModel();
        if(!$this->model) {
            throw new \Exception('Model data is not found');
        }
        $this->viewPath = $this->setView() ? $this->getBasicViewPath() . $this->setView() : null;
        $this->templatePath = $this->setTemplate() ? $this->getBasicTemplatePath() . $this->setTemplate() : null;

        if($this->viewPath && !file_exists($this->viewPath)) {
            throw new \Exception('View file is not found: ' . $this->setView());
        }
        if($this->templatePath && !file_exists($this->templatePath)) {
            throw new \Exception('Template file is not found: ' . $this->setTemplate());
        }

    }

    protected function initParams($params)
    {
        $requiredParams = $this->setRequiredParams() ?: [];
        foreach($requiredParams as $paramName) {
            if(!array_key_exists($paramName, $params)) {
                throw new \Exception('Required params "' . $paramName . '" is not set');
            }
        }
        $this->params = $params;
    }

    protected function render(array $params = [])
    {
        $arr = [
            'model' => $this->model,
            'driver' => $this->driver
        ];
        $arr = array_merge($arr, $params);
        extract($arr, EXTR_SKIP);

        ob_start();
        try {
            include $this->viewPath;
            echo $this->viewPath;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    protected function generateFileName()
    {
        $fileNameForUser = $this->fileNameForUser ?: time();
        return $fileNameForUser . '.' . $this->fileExt;
    }

}