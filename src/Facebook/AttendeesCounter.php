<?php

namespace Hack\Facebook;

class AttendeesCounter
{
    public function count(array $events, bool $includeMaybe = true)
    {
        $sum = 0;
        foreach ($events as $event) {
            $sum += $event->stats->attending;
            if ($includeMaybe) {
                $sum += $event->stats->maybe;
            }
        }
        return $sum;
    }
}
