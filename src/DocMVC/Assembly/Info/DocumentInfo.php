<?php

namespace DocMVC\Assembly\Info;

class DocumentInfo
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
     * Document extension (without dot)
     *
     * @var string
     */
    private $documentExt;

    /**
     * Document name (with ext)
     *
     * @var string
     */
    private $documentName;

    /**
     * @var string Temporary document name (with path)
     */
    private $tmpDocumentPath;

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
     * Get document extension (without dot)
     *
     * @return string
     */
    public function getDocumentExt(): string
    {
        return $this->documentExt;
    }

    /**
     * @param $documentExt
     * @return $this
     */
    public function setDocumentExt($documentExt): self
    {
        $this->documentExt = $documentExt;

        return $this;
    }

    /**
     * Get temporary document name (with path)
     *
     * @return string
     */
    public function getTmpDocumentPath(): string
    {
        return $this->tmpDocumentPath;
    }

    /**
     * @param $tmpDocumentPath
     * @return $this
     */
    public function setTmpDocumentPath($tmpDocumentPath): self
    {
        $this->tmpDocumentPath = $tmpDocumentPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentName(): string
    {
        return $this->documentName;
    }

    /**
     * @param $documentName
     * @return $this
     */
    public function setDocumentName($documentName): self
    {
        $this->documentName = $documentName;

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