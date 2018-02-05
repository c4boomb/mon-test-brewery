<?php

namespace Lev\Brewery\Api;

/**
 * Interface ImportInterface
 *
 * @category  Lev
 * @package   Lev\Brewery\Api
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
interface ImportInterface
{
    /**
     * Imports batch of data
     *
     * @param $data
     */
    public function import($data);
}
