<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 4/10/14
 * Time: 8:33 PM
 */

class Territory {
    public $territory;
    public $publisher;
    public $out;
    public $in;

    public function __construct($row)
    {
        $this->territory = $row->territory . '';
        $this->publisher = $row->publisher . '';
        $this->out = strtotime($row->out . '');
        $in = $row->in . '';

        if (!empty($in)) {
            $this->in = strtotime($in);
        }
    }
} 