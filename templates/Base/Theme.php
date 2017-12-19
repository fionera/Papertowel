<?php

namespace templates\Base;

class Theme extends \App\Components\Theme\Theme {
    
    /**
     * Theme constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->author = 'Fionera';
        $this->name = 'Base';
    }
}