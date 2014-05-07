<?php

namespace Brammm\CommonBundle\Util;

interface CanonicalizerInterface
{
    /**
     * Returns a canonicalized version of a string
     *
     * @param $string
     *
     * @return string
     */
    public function canonicalize($string);
} 