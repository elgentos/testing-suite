<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm;

use InvalidArgumentException;
use SimpleXMLElement;

interface XmlAccessorInterface
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
    ): SimpleXMLElement;

    /**
     * Get a descendant, create it when it does not exist.
     *
     * @param SimpleXMLElement $element
     * @param array            $path
     *
     * @return SimpleXMLElement
     *
     * @throws InvalidArgumentException When the descendant path is invalid.
     */
    public function getDescendant(
        SimpleXMLElement $element,
        array $path
    ): SimpleXMLElement;

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
    ): void;
}
