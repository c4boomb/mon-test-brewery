<?php

namespace Lev\Brewery\Api;

interface ImportInterface {
    /**
     * @param $data
     * @return ImportInterface
     */
    public function import($data) : ImportInterface;
}
