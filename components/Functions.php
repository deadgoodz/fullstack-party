<?php

namespace app\components;

use DateTime;

class Functions
{
    public function getTimeAgo($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function getActiveState($get, $state)
    {
        $result = '';

        if (!empty($get)) {
            if ($get['state'] == $state) {
                $result = 'active';
            }
        } else {
            if ($state == 'open') {
                $result = 'active';
            }
        }

        return $result;
    }

    public function getStateCounts($issues)
    {
        $result['open'] = 0;
        $result['closed'] = 0;

        if (isset($issues['open'])) {
            $result['open'] = count($issues['open']);
        }

        if (isset($issues['closed'])) {
            $result['closed'] = count($issues['closed']);
        }
    }
}