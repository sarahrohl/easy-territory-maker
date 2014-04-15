<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 4/10/14
 * Time: 8:33 PM
 */

class Territory {
    public $territory;
    public $locality = '';
    public $publisher = '';
    public $congregation = '';
    public $out;
    public $in;
	public $idealReturnDate;

    public function __construct($row = null)
    {
        if ($row != null) {
            $this->territory = $row->territory . '';
            $this->publisher = $row->publisher . '';

            //out
            $out = $row->out . '';
            if (!empty($out)) {
                $this->out = DateTime::createFromFormat('!d/m/Y', $out)->getTimestamp();
            }

            //ideal return date
            $this->idealReturnDate = strtotime(date("Y-m-d", $this->out) . " +4 month");

            //in
            $in = $row->in . '';

            if (!empty($in)) {
                $this->in = DateTime::createFromFormat('!d/m/Y', $in)->getTimestamp();
            }
        }
    }
} 