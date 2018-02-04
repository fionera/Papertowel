<?php

namespace templates\Base;

class Theme extends \Papertowel\Component\Theme\Theme
{

    /**
     * Theme constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->author = 'Fionera';
        $this->name = 'Base';
        $this->css = ['/public/css/base.css'];
        $this->javascript = ['/public/js/base.js'];
    }
}