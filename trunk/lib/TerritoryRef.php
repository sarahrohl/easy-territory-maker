<?php

class TerritoryRef
{
	public $locality;
	public $territory;
	public $congregation;

	public function __construct($territory, $locality, $congregation)
	{
		$this->territory = $territory;
		$this->locality = $locality;
		$this->congregation = $congregation;
	}
}