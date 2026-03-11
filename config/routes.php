<?php
/**
 * Passwd routes configuration.
 *
 * Copyright 2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 */

use Horde\Passwd\ResponsivePasswordController;

// Responsive password change (smartmobile/mobile-first view)
$mapper->connect(
    'ResponsivePassword',
    'smartmobile.php',
    [
        'controller' => ResponsivePasswordController::class,
        'HordeAuthType' => 'authenticate',
        'stack' => [],
    ]
);

// Also map /responsive for direct access
$mapper->connect(
    'ResponsivePasswordDirect',
    'responsive',
    [
        'controller' => ResponsivePasswordController::class,
        'HordeAuthType' => 'authenticate',
        'stack' => [],
    ]
);
