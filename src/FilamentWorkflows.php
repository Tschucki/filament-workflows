<?php

namespace Tschucki\FilamentWorkflows;

class FilamentWorkflows
{
    public function createAction($name)
    {
        return new Action($name);
    }
}
