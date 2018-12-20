<?php

namespace DocMVC\Src;

use \Exception;

abstract class BaseDoc
{
    /**
     * File extension (without dot)
     *
     * @var string
     */
    private $fileExt;

    /**
     * File name for download
     *
     * @var string
     */
    protected $fileNameForUser;

    /**
     * @var string Temporary document filename (with path)
     */
    protected $tmpName;

    /**
     * Object to work with file
     *
     * @var mixed
     */
    protected $driver;

    /**
     * Data array for construct class and then use it in model
     *
     * @var array
     */
    protected $params;

    /**
     * Data array for use in view
     *
     * @var array
     */
    private $model;

    /**
     * Full path to view file (with name)
     *
     * @var string
     */
    private $viewPath;

    /**
     * Full path to template file (with name)
     *
     * @var string
     */
    private $templatePath;

    /**
     * Is save file (true) or remove, after destruct class (false)
     *
     * @var boolean
     */
    protected $isSaveFile = false;

    /**
     * Document content
     *
     * @var string
     */
    protected $content;

    /**
     * Prepare file for next working
     *
     * @param array
     *
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        try {
            $this->initFileExt();
            $this->initParams($params);
            $this->init();
            $this->buildDoc();
        } catch (Exception $e) {
            $this->removeFile();
            throw new Exception($e->getMessage());
        }
    }

    public function __destruct()
    {
        if (!$this->isSaveFile) {
            $this->removeFile();
        }
    }

    /**
     * Get full path to view file (with name)
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Get full path to template file (with name)
     *
     * @return string|null
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Get data array for use in view
     *
     * @return array
     */
    public function getModel()
    {
        return $this->model;
    }


    /**
     * Get file extension (without dot)
     *
     * @return string
     */
    public function getFileExt()
    {
        return $this->fileExt;
    }

    /**
     * Get object to work with file
     *
     * @return mixed
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Get document content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get temporary document filename (with path)
     *
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * Set document content
     *
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Remove file for tmpName
     */
    public function removeFile()
    {
        if(file_exists($this->tmpName)) {
            unlink($this->tmpName);
        }
    }

    /**
     * Download file (generate headers then echo file content)
     */
    public function download()
    {
        $this->DLHeaders();
        $this->DL();
        exit();
    }

    /**
     * Get folder path of class instance
     *
     * @throws \ReflectionException
     *
     * @return string
     */
    protected function getCurrPath()
    {
        $class_info = new \ReflectionClass($this);
        $fileName = preg_replace('/\\\\/','/',$class_info->getFileName());
        $fileNameArr = explode('/',$fileName);
        array_pop($fileNameArr);
        $currPath = implode('/',$fileNameArr);

        return $currPath;
    }

    /**
     * Get full path to class instance view folder
     *
     * @throws \ReflectionException
     *
     * @return string
     */
    protected function getBasicViewPath()
    {
        return $this->getCurrPath() . '/view/';
    }

    /**
     * Get full path to class instance template folder
     *
     * @throws \ReflectionException
     *
     * @return string
     */
    protected function getBasicTemplatePath()
    {
        return $this->getCurrPath() . '/template/';
    }

    /**
     * These methods must be implemented in child class
     */
    protected abstract function DLHeaders();
    protected abstract function DL();
    protected abstract function buildDoc();
    protected abstract function allowedExt();
    protected abstract function setupFileExt();

    /**
     * These methods must be implemented in result class instances
     */
    protected abstract function setupView();
    protected abstract function setupModel();
    protected abstract function setupTemplate();
    protected abstract function setupRequiredParams();

    /**
     * Init model data, viewPath, templatePath (if exist)
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    protected function init()
    {
        $this->model = $this->setupModel();
        if(!$this->model) {
            throw new \Exception('Model data is not found');
        }
        $this->viewPath = $this->setupView() ? $this->getBasicViewPath() . $this->setupView() : null;
        $this->templatePath = $this->setupTemplate() ? $this->getBasicTemplatePath() . $this->setupTemplate() : null;

        if($this->viewPath && !file_exists($this->viewPath)) {
            throw new \Exception('View file is not found: ' . $this->setupView());
        }
        if($this->templatePath && !file_exists($this->templatePath)) {
            throw new \Exception('Template file is not found: ' . $this->setupTemplate());
        }

    }

    /**
     * Init params
     * Check params to equal in method setupRequiredParams
     *
     * @throws \Exception
     */
    protected function initParams($params)
    {
        $requiredParams = $this->setupRequiredParams() ?: [];
        foreach($requiredParams as $paramName) {
            if(!array_key_exists($paramName, $params)) {
                throw new \Exception('Required params "' . $paramName . '" is not set');
            }
        }
        $this->params = $params;
    }

    /**
     * Render file content from view file
     *
     * @throws \Exception
     *
     * @return string
     */
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

    /**
     * Generate file name with extension for download
     * If prop fileNameForUser is empty, name is set as time()
     *
     * @return string
     */
    protected function generateFileName()
    {
        $fileNameForUser = $this->fileNameForUser ?: time();
        return $fileNameForUser . '.' . $this->fileExt;
    }

    /**
     * Init file extension
     * Set fileExt, check for allowed in child class method allowedExt
     *
     * @throws \Exception
     */
    private function initFileExt()
    {
        $fileExt = $this->setupFileExt();
        if(!in_array($fileExt, $this->allowedExt())) {
            throw new Exception('File Extension ' . $fileExt . 'is not allowed');
        }
        $this->fileExt = $fileExt;

    }
}