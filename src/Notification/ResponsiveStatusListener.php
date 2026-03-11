<?php

declare(strict_types=1);

namespace Horde\Passwd\Notification;

use Horde_Notification_Listener_Status;

/**
 * Responsive Status Notification Listener
 *
 * Enhanced status listener that adds CSS classes for message types.
 * Extends the standard Horde status listener to add type classes to <li> elements.
 *
 * Copyright 2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @category  Horde
 * @copyright 2026 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Passwd
 */
class ResponsiveStatusListener extends Horde_Notification_Listener_Status
{
    /**
     * Constructor
     *
     * @param mixed $params Optional parameters (for compatibility)
     */
    public function __construct($params = null)
    {
        parent::__construct();
        // Add support for horde.* notification types (error, success, warning, etc.)
        $this->addType('horde.*', 'Horde_Notification_Event_Status');
    }

    /**
     * Outputs the status messages with proper CSS classes
     *
     * @param array $events   The list of events to handle
     * @param array $options  An array of options (not used)
     */
    public function notify($events, $options = [])
    {
        if (!count($events)) {
            return;
        }

        echo '<ul class="notices">';

        foreach ($events as $event) {
            // Get the event type and convert to CSS class
            $type = isset($event->type) ? htmlspecialchars($event->type) : 'horde-message';

            echo '<li class="' . $type . '">' . $event . '</li>';
        }

        echo '</ul>';
    }
}
