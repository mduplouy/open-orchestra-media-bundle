<?php

namespace PHPOrchestra\ModelBundle\Model;

/**
 * Interface BlockInterface
 */
Interface BlockInterface
{
    /**
     * Set component
     *
     * @param string $component
     *
     * @return self
     */
    public function setComponent($component);

    /**
     * Get component
     *
     * @return string $component
     */
    public function getComponent();

    /**
     * Set attributes
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes);

    /**
     * Get attributes
     *
     * @return mixed $attributes
     */
    public function getAttributes();
}
 