<?php

declare(strict_types=1);

namespace Horde\Passwd;

use Horde\Core\Assets\ResponsiveAssets;
use Horde\Core\View\ResponsiveTemplateView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Responsive Password Controller
 *
 * Modern mobile-first password change interface.
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
class ResponsivePasswordController implements RequestHandlerInterface
{
    /**
     * Handle password change request
     *
     * @param ServerRequestInterface $request The request object
     *
     * @return ResponseInterface The response
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        global $registry, $session, $injector, $conf;

        // Get form variables
        $vars = $injector->getInstance('Horde_Variables');

        // Use existing Passwd_Basic for business logic
        $basic = new \Passwd_Basic($vars);

        // Build responsive assets
        $responsiveAssets = new ResponsiveAssets($registry);

        // Get backend info
        $backends = $injector->getInstance('Passwd_Factory_Driver')->backends;
        $backend_key = $vars->backend;
        if (!isset($backends[$backend_key])) {
            // Choose first available backend
            foreach ($backends as $k => $v) {
                if (substr($k, 0, 1) != '_') {
                    $backend_key = $k;
                    break;
                }
            }
        }

        // Determine if we should show backend selector
        $showlist = ($conf['backend']['backend_list'] == 'shown');

        // Build header text
        if ($showlist) {
            $header = _("Change your password");
        } else {
            $header = sprintf(
                _("Changing password for %s"),
                htmlspecialchars($backends[$backend_key]['name'])
            );
        }

        // Prepare view data
        $viewData = [
            // Topbar data
            'topbar' => [
                'appName' => _("Change Password"),
                'portalUrl' => (string) $registry->getServiceLink('portal')->setRaw(true),
                'logoutUrl' => (string) $registry->getServiceLink('logout')->setRaw(true),
                'userName' => $registry->getAuth(),
            ],

            // Asset URIs
            'hordeThemesUri' => $registry->get('themesuri', 'horde'),
            'hordeJsUri' => $registry->get('jsuri', 'horde'),
            'passwdThemesUri' => $registry->get('themesuri', 'passwd'),
            'passwdJsUri' => $registry->get('jsuri', 'passwd'),
            'registry' => $registry,

            // Form data
            'formInput' => \Horde_Util::formInput(),
            'url' => $vars->return_to ?: '',
            'userid' => $registry->getAuth() ?: '',
            'userChange' => $conf['user']['change'] ?? false,
            'showlist' => $showlist,
            'backends' => $backends,
            'backend' => $backend_key,
            'token' => $session->getToken(),
            'header' => $header,

            // Status messages (use responsive listener for proper CSS classes)
            'status' => $this->getStatusMessages(),
        ];

        // Render responsive template
        $templatePath = PASSWD_TEMPLATES . '/responsive.html.php';
        $view = new ResponsiveTemplateView($templatePath, $viewData);

        $streamFactory = $injector->getInstance('Psr\Http\Message\StreamFactoryInterface');
        $responseFactory = $injector->getInstance('Psr\Http\Message\ResponseFactoryInterface');
        return $responseFactory->createResponse(200)
            ->withBody($streamFactory->createStream($view->render()));
    }

    /**
     * Get status messages using responsive listener
     *
     * @return string HTML status messages
     */
    private function getStatusMessages(): string
    {
        $notification = $GLOBALS['notification'] ?? null;
        if (!$notification) {
            return '';
        }

        // Register our responsive listener if not already registered
        if (!$notification->getListener('responsive_status')) {
            $notification->attach(
                'responsive_status',
                null,
                '\\Horde\\Passwd\\Notification\\ResponsiveStatusListener'
            );
        }

        \Horde::startBuffer();
        $notification->notify(['listeners' => ['responsive_status']]);
        return \Horde::endBuffer();
    }
}
