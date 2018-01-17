<?php
/**
 * Coded by fionera.
 */

namespace plugins\ExamplePlugin;


use App\Component\Plugin\Plugin;

class ExamplePlugin extends Plugin
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return 'Tim Windelschmidt';
    }

    /**
     * @return float
     */
    public function getVersion(): double
    {
        return 1.00;
    }
}