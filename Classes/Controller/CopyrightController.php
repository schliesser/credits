<?php

declare(strict_types=1);

namespace Schliesser\Credits\Controller;

use Psr\Http\Message\ResponseInterface;
use Schliesser\Credits\Domain\Repository\CopyrightRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class CopyrightController extends ActionController
{
    public function __construct(
        protected readonly CopyrightRepository $copyrightRepository
    ) {}

    public function imagesAction(): ResponseInterface
    {
        $site = $this->request->getAttribute('site');
        if ($site instanceof Site) {
            $this->view->assign('images', $this->copyrightRepository->findBySite($site));
        }
        return $this->htmlResponse();
    }
}
