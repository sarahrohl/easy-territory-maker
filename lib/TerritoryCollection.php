<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 4/10/14
 * Time: 8:38 PM
 */

class TerritoryCollection {
	public $length = 0;
    public $collection = array();
	public $out = false;

    public function add($territory) {
        $this->collection[] = $territory;
	    $this->length++;
    }

	public function sort()
	{
		usort($this->collection, function (Territory $a, Territory $b) {
			return $b->out - $a->out;
		});
	}

	/**
	 * @return Territory
	 */
	public function mostRecent()
	{
		$this->sort();
		$mostRecent = end($this->collection);
		return $mostRecent;
	}
}