<?php

namespace Pics\Models;

class Comment
{
	public $id;
	public $content;
	public $picture;
	public $parent;
	public $byuser;
	public $posted;
	public $edited;
	public $children = false;

	public function postedTimePassed() {
                $diff = time() - strtotime($this->posted);
                if ($diff == 0) {
                        return 'just now';
                }

                $intervals = array(
                        1                   => array('year',    31556926),
                        $diff < 31556926    => array('month',   2628000),
                        $diff < 2629744     => array('week',    604800),
                        $diff < 604800      => array('day',     86400),
                        $diff < 86400       => array('hour',    3600),
                        $diff < 3600        => array('minute',  60),
                        $diff < 60          => array('second',  1)
                );

                $value = floor($diff/$intervals[1][1]);
                return $value . ' ' . $intervals[1][0].($value > 1 ? 's' : '') . ' ago';
        }

}
