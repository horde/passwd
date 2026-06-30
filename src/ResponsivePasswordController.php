<?php

declare(strict_types=1);

namespace Horde\Passwd;

use Horde\Core\PageOutput\PageContent;
use Horde\Core\PageOutput\ResponsiveChromeRenderer;
use Horde\Core\View\ResponsiveTemplateView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Horde\Util\Util;
use Horde;

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
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        global $registry, $session, $injector, $conf;

        $vars = $injector->getInstance('Horde_Variables');

        $backends = $injector->getInstance('Passwd_Factory_Driver')->backends;
        $backend_key = $vars->backend;
        if (!isset($backends[$backend_key])) {
            foreach ($backends as $k => $v) {
                if (substr($k, 0, 1) != '_') {
                    $backend_key = $k;
                    break;
                }
            }
        }

        $showlist = ($conf['backend']['backend_list'] == 'shown');

        if ($showlist) {
            $header = _("Change your password");
        } else {
            $header = sprintf(
                _("Changing password for %s"),
                htmlspecialchars($backends[$backend_key]['name'])
            );
        }

        $viewData = [
            'formInput' => \Util::formInput(),
            'url' => $vars->return_to ?: '',
            'userid' => $registry->getAuth() ?: '',
            'userChange' => $conf['user']['change'] ?? false,
            'showlist' => $showlist,
            'backends' => $backends,
            'backend' => $backend_key,
            'token' => $session->getToken(),
            'header' => $header,
            'status' => $this->getStatusMessages(),
        ];

        $templatePath = PASSWD_TEMPLATES . '/responsive-body.html.php';
        $view = new ResponsiveTemplateView($templatePath, $viewData);
        $bodyHtml = $view->render();

        $chromeRenderer = $injector->get(ResponsiveChromeRenderer::class);
        $pageContent = new PageContent(
            title: _("Change Password") . ' - Horde',
            bodyHtml: $bodyHtml,
            app: 'passwd',
            jsFiles: ['passwd-responsive.js'],
        );

        $pageHtml = $chromeRenderer->renderPage($pageContent, $request);

        $streamFactory = $injector->getInstance('Psr\Http\Message\StreamFactoryInterface');
        $responseFactory = $injector->getInstance('Psr\Http\Message\ResponseFactoryInterface');

        return $responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody($streamFactory->createStream($pageHtml));
    }

    private function getStatusMessages(): string
    {
        $notification = $GLOBALS['notification'] ?? null;
        if (!$notification) {
            return '';
        }

        if (!$notification->getListener('responsive_status')) {
            $notification->attach(
                'responsive_status',
                null,
                '\\Horde\\Passwd\\Notification\\ResponsiveStatusListener'
            );
        }

        Horde::startBuffer();
        $notification->notify(['listeners' => ['responsive_status']]);
        return Horde::endBuffer();
    }
}
