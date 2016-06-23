<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;

class Options
{
    /**
     * @Assert\GreaterThan(0, message = "Timeout must be greater than 0")
     */
    protected $timeout;

    /**
     * @Assert\GreaterThan(0, message = "Attempts must be greater than 0")
     */
    protected $attempts;

    public function getAttempts()
    {
        return $this->attempts;
    }

    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}