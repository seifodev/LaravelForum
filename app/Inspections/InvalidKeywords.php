<?php

namespace App\Inspections;
use Exception;

class InvalidKeywords
{

    protected $invalidKeywords = [
        'A spam reply test',
    ];

    public function detect($body)
    {

        foreach($this->invalidKeywords as $keyword)
        {
            if(stripos($body, $keyword) !== false)
                throw new Exception('Spam detected');
        }
    }
}