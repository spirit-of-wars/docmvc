<?php

namespace DocMVC\Assembly\Info;

interface FileInfoBuilderInterface
{
    public function initParams(): void;
    public function initFileExt(): void;
    public function init(): void;
    public function initTmpFilePath(): void;
}