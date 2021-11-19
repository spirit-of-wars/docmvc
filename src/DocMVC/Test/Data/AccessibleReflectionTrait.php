<?php

namespace DocMVC\Test\Data;

trait AccessibleReflectionTrait
{
    /**
     * @param $object
     * @param $methodName
     * @param array $parameters
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}