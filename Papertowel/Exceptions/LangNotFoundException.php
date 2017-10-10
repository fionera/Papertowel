<?php

namespace Papertowel\Exceptions;

use Exception;

class LangNotFoundException extends Exception {
    public function __construct(string $lang)
    {
        parent::__construct('The Requested Language - "' . $lang . '" - could not be found' ,404);
    }

}