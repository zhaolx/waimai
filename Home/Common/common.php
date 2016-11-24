<?php
function click()
{
    $Click = M('Click');
    $time = $Click->where('id=1')->getField('time');
    if ($time == date('Y-m-d')) {
        $Click->where('id=1')->setInc('today', 1);
    } else {
        $today = $Click->where('id=1')->getField('today');
        $Click->where('id=1')->setField('yesterday', $today);
        $Click->where('id=1')->setField('today', 1);
        $Click->where('id=1')->setField('time', date('Y-m-d'));
    }
    $Click->where('id=1')->setInc('allclick', 1);
}