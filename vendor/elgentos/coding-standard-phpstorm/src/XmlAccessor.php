<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm;

use InvalidArgumentException;
use SimpleXMLElement;

class XmlAccessor implements XmlAccessorInterface
{
    /**
     * Get a child node, create it when it does not exist.
     *
     * @param SimpleXMLElement $element
     * @param string           $name
     * @param array            $attributes
     *
     * @return SimpleXMLElement
     */
    public function getChild(
        SimpleXMLElement $element,
        string $name,
        array $attributes = []
    ): SimpleXMLElement {
        $xpath = $name . $this->getAttributesXpath($attributes);

        $result = $element->xpath($xpath);
        if (empty($result)) {
            $node = $element->addChild($name);
            $this->setAttributes($node, $attributes);
        } else {
            $node = reset($result);
        }

        return $node;
    }

    /**
     * Get a descendant, create it when it does not exist.
     *
     * @param SimpleXMLElement $element
     * @param array            $path
     *
     * @return SimpleXMLElement
     * @throws InvalidArgumentException When the descendant path is invalid.
     */
    public function getDescendant(
        SimpleXMLElement $element,
        array $path
    ): SimpleXMLElement {
        foreach ($path as $childProperties) {
            if (
                !is_array($childProperties)
                || empty($childProperties)
            ) {
                throw new InvalidArgumentException('Invalid descendant path');
            }

            $name       = array_shift($childProperties);
            $attributes = count($childProperties)
                ? array_shift($childProperties)
                : [];

            $element = $this->getChild($element, $name, $attributes);
        }

        return $element;
    }

    /**
     * Set the attributes of a node.
     *
     * @param SimpleXMLElement $element
     * @param array            $attributes
     *
     * @return void
     */
    public function setAttributes(
        SimpleXMLElement $element,
        array $attributes
    ): void {
        $storage = $element->attributes();
        foreach ($attributes as $key => $value) {
            if (isset($storage->{$key})) {
                $storage->{$key} = $value;
            } else {
                $element->addAttribute($key, $value);
            }
        }
    }

    /**
     * Get an xpath for attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    private function getAttributesXpath(array $attributes): string
    {
        $xpath = '';
        if (!empty($attributes)) {
            $parts = [];
            foreach ($attributes as $key => $value) {
                $parts[] = '@' . $key . '="' . $value . '"';
            }

            $xpath .= '[' . implode(' and ', $parts) . ']';
        }

        return $xpath;
    }
}
