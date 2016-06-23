<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;


class Clock extends ConfigForm
{
    protected $timezoneUtcOffset;

    protected $timezoneName;
    
    public function getTimezoneUtcOffset()
    {
        return $this->timezoneUtcOffset;
    }

    public function setTimezoneUtcOffset($timezoneUtcOffset)
    {
        $this->timezoneUtcOffset = $timezoneUtcOffset;
    }

    public function getTimezoneName()
    {
        return $this->timezoneName;
    }

    public function setTimezoneName($timezoneName)
    {
        $this->timezoneName = $timezoneName;
    }
    
    public static function createFromXml($clockXml)
    {
        $clock = new static();

        $clock->setTimezoneUtcOffset(Clock::getNodeValue($clockXml->{'timezone-utc-offset'}));
        $clock->setTimezoneName(Clock::getNodeValue($clockXml->{'timezone-name'}));

        return $clock;
    }
}



