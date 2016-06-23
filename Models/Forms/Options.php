<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;


class Options
{
    protected $timeout;

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