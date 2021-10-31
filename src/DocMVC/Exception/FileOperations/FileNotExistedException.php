<?php

namespace DocMVC\Exception\FileOperations;

use DocMVC\Exception\RuntimeException;

class FileNotExistedException extends RuntimeException implements FileOperationsExceptionInterface
{
}