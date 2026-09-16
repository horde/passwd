<?php

declare(strict_types=1);
/**
 * Passwd configuration class factory
 *
 * Creates instances of the PasswdConfig class.
 *
 * Old pattern: globals $conf; $somethingDetail = $conf['something']['detail']; *
 * New pattern: $config = $injector->get(PasswdConfig::class); $somethingDetail = $config->get('something.detail');
 *
 * Prefer DI over instantiating $config in your code.
 */

namespace Horde\Passwd;

use Horde\Core\Config\ConfigLoader;
use Horde\Injector\Injector;

class PasswdConfigFactory
{
    public function __construct(private Injector $injector) {}

    public function create(): PasswdConfig
    {
        $state = $this->injector->get(ConfigLoader::class)->load('passwd');
        return new PasswdConfig($state->toArray());
    }
}
