<?php

namespace DocMVC\Assembly\Info;

interface DocumentInfoBuilderInterface
{
    public function initParams(): void;
    public function initDocumentExt(): void;
    public function init(): void;
    public function initTmpDocumentPath(): void;
}