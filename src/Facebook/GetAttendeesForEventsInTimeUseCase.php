<?php

namespace Hack\Facebook;

class GetAttendeesForEventsInTimeUseCase
{
    private $eventsFetcher;
    private $attendeesCounter;

    public function __construct(
        EventsFetcher $eventsFetcher,
        AttendeesCounter $attendeesCounter
    ){
        $this->eventsFetcher = $eventsFetcher;
        $this->attendeesCounter = $attendeesCounter;
    }

    public function __invoke($timestamp)
    {
        $events = $this->eventsFetcher->fetchEvents($timestamp);
        return $this->attendeesCounter->count($events);
    }
}
