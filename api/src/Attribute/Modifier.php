<?php

namespace App\Attribute;

use ReflectionClass;

/*
 * ToDO add parameters to Attributes
 */

abstract class Modifier
{
    /**
     * process $data for attributes from App\Attribute
     * return assoc array
     *      [attribute_name] => [
     *                              [field_from_$data] =>[result_from_execution_function],
     *                              [field_from_$data] =>[result_from_execution_function],
     *                          ],
     *      [attribute_name] => [
     *                              [field_from_$data] =>[result_from_execution_function],
     *                              [field_from_$data] =>[result_from_execution_function],
     *                          ],
     * @param mixed $data
     * @return array
     * @throws \ReflectionException
     */
    public static function process(mixed $data)
    {
        $attributesNames = self::collectAttributesNames(__DIR__);
        $attributesNames = array_filter(
            $attributesNames,
            function ($item) {
                return (new ReflectionClass($item))->isInstantiable();
            });

        $extractedAttributesData = self::extractAttributesData($data);

        $result = [];
        foreach ($extractedAttributesData as $attributesData) {
            foreach ($attributesData['attributes'] as $attribute) {
                if (in_array($attribute['name'], $attributesNames)) {
                    $attr = new $attribute['name'];
                    $result[$attribute['name']][$attributesData['field']] = $attr->execute();
                }
            }
        }
        return $result;
    }

    private static function collectAttributesNames(string $path, string $nameSpace = 'App\Attribute\\'): array
    {
        $attributes = [];
        foreach (scandir($path) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_dir($path . DIRECTORY_SEPARATOR . $item)) {
                $attributes = array_merge($attributes, self::collectAttributesNames($path . DIRECTORY_SEPARATOR . $item, $nameSpace . "$item\\"));
            } else {
                $attributes[] = $nameSpace . strtok($item, '.');
            }
        }
        return $attributes;
    }

    private static function extractAttributesData(mixed $from): array
    {
        $fields = [];
        if (is_object($from)) {
            $reflect = new ReflectionClass($from);
            foreach ($reflect->getProperties() as $property) {
                $accessible = true;
                if ($property->isProtected() || $property->isPrivate()) {
                    $property->setAccessible(true);
                    $accessible = false;
                }

                $field['field'] = $property->getName();
                $field['attributes'] = [];
                foreach ($property->getAttributes() as $attribute) {
                    $attr['name'] = $attribute->getName();
                    $attr['args'] = $attribute->getArguments();
                    $field['attributes'][] = $attr;
                }
                $fields[] = $field;

                if (!$accessible) {
                    $property->setAccessible(false);
                }
            }
        }
        return $fields;
    }

    abstract public function execute(mixed $value = null): mixed;
}
