<?php

namespace Horde\Passwd\Config;

use Horde_Form;
use Horde_Config_Form;
use Horde;

class Form extends Horde_Config_Form
{
    public function __construct($vars, $app = 'passwd')
    {
        // custom content first
        // TODO:
        // Load xmlconfig
        parent::__construct($vars, $app);
    }
}
