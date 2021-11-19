<?php

namespace SpiritOfWars\DocMVC\Exception\FileOperations;

use SpiritOfWars\DocMVC\Exception\RuntimeException;

class FileNotExistedException extends RuntimeException implements FileOperationsExceptionInterface
{
}