<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;


class DnsResolverSearch extends ConfigForm
{
    protected $search;

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }
}