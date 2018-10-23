<?php


namespace KikCMS\Controllers;


use KikCMS\Config\MenuConfig;
use KikCMS\Services\CacheService;
use Phalcon\Http\ResponseInterface;

/**
 * @property CacheService $cacheService
 */
class CacheController extends BaseCmsController
{
    /**
     * Display control panel for APCu cache
     */
    public function managerAction()
    {
        $this->view->title            = 'Cache beheer';
        $this->view->selectedMenuItem = MenuConfig::MENU_ITEM_SETTINGS;
        $this->view->cacheNodeMap     = $this->cacheService->getCacheNodeMap();

        $this->view->pick('cms/cacheManager');
    }

    /**
     * @return ResponseInterface
     */
    public function emptyByKeyAction(): ResponseInterface
    {
        $key = $this->request->get('key');

        $this->cacheService->clear($key);

        return $this->response->redirect($this->url->get('cacheManager'));
    }
}