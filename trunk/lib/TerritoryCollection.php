<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 4/10/14
 * Time: 8:38 PM
 */

class TerritoryCollection {
    public $collection = array();

    public function add($territory) {
        $this->collection[] = $territory;
    }
} 