<?php

namespace DocMVC\Assembly\Info;

class FileInfo
{

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
     * File extension (without dot)
     *
     * @var string
     */
    private $fileExt;

    /**
     * File name (with ext)
     *
     * @var string
     */
    private $fileName;

    /**
     * @var string Temporary document filename (with path)
     */
    private $tmpFilePath;

    /**
     * Data array for construct class and then use it in model
     *
     * @var array
     */
    private $params = array();

    /**
     * Get data array for use in view
     *
     * @return array
     */
    public function getModel(): array
    {
        return $this->model;
    }

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get full path to view file (with name)
     *
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * @param $viewPath
     * @return $this
     */
    public function setViewPath($viewPath): self
    {
        $this->viewPath = $viewPath;

        return $this;
    }

    /**
     * Get full path to template file (with name)
     *
     * @return string|null
     */
    public function getTemplatePath(): ?string
    {
        return $this->templatePath;
    }

    /**
     * @param $templatePath
     * @return $this
     */
    public function setTemplatePath($templatePath): self
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    /**
     * Get file extension (without dot)
     *
     * @return string
     */
    public function getFileExt(): string
    {
        return $this->fileExt;
    }

    /**
     * @param $fileExt
     * @return $this
     */
    public function setFileExt($fileExt): self
    {
        $this->fileExt = $fileExt;

        return $this;
    }

    /**
     * Get temporary document filename (with path)
     *
     * @return string
     */
    public function getTmpFilePath(): string
    {
        return $this->tmpFilePath;
    }

    /**
     * @param $tmpFilePath
     * @return $this
     */
    public function setTmpFilePath($tmpFilePath): self
    {
        $this->tmpFilePath = $tmpFilePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param $fileName
     * @return $this
     */
    public function setFileName($fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }
}