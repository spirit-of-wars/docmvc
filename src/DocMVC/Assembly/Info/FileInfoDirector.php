<?php

namespace DocMVC\Assembly\Info;

use DocMVC\Exception\Assembly\FileInfoBuilder\FileInfoBuilderExceptionInterface;

class FileInfoDirector
{
    /**
     * @param FileInfoBuilderInterface $fileInfoBuilder
     * @return FileInfoBuilderInterface
     *
     * @throws FileInfoBuilderExceptionInterface
     */
    public function buildFileInfo(FileInfoBuilderInterface $fileInfoBuilder): FileInfoBuilderInterface
    {
        $fileInfoBuilder->initParams();
        $fileInfoBuilder->initFileExt();
        $fileInfoBuilder->init();
        $fileInfoBuilder->initTmpFilePath();

        return $fileInfoBuilder;
    }
}