<?php
namespace Qbus\DataConsent;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;


/**
 * IframePlaceholderEidController
 *
 * @author Benjamin Franzke <bfr@qbus.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class IframePlaceholderEidController
{
    /**
     * Retrieves the image and redirect to the url
     *
     * @param  ServerRequestInterface $request  the current request object
     * @param  ResponseInterface      $response the available response
     * @return ResponseInterface      the modified response
     */
    public function processRequest(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = $request->getQueryParams()['original_url'] ?? null;
        $lang = $request->getQueryParams()['lang'] ?? null;
        $templateProviderPackage = $request->getQueryParams()['pkg'] ?? 'data_consent';

        $escapedUrl = htmlspecialchars($url);
        $parsed  = parse_url($url);
        $escapedHost = htmlspecialchars($parsed['host']);
        $type = 'marketing';

        $title = 'iframe';
        if ($escapedHost === 'www.youtube.com') {
            $title = 'video';
        }

        $params = [
            'url' => $url,
            'host' => $parsed['host'],
            'type' => $type,
            'title' => $title,
            'lang' => $lang
        ];

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $template = 'EXT:' . $templateProviderPackage . '/Resources/Private/Templates/Iframe/Placeholder.html';
        // @todo PartialPaths
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($template));
        $view->assignMultiple($params);
        $result = $view->render();

        $response->getBody()->write($result);
        return $response;
    }
}
