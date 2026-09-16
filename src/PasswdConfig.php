<?php

declare(strict_types=1);
/**
 * Passwd configuration class
 *
 * Provides access to the Passwd configuration settings.
 *
 * Old pattern: globals $conf; $somethingDetail = $conf['something']['detail']; *
 * New pattern: $config = $injector->get(PasswdConfig::class); $somethingDetail = $config->get('something.detail');
 *
 * Prefer DI over instantiating $config in your code.
 */

namespace Horde\Passwd;

use Horde\Core\Config\State;
use Horde\Injector\Attribute\Factory;

#[Factory(factory: PasswdConfigFactory::class, method: 'create')]
class PasswdConfig extends State {}
