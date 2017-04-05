<?php

namespace KikCMS\Controllers;


use KikCMS\Classes\Renderable\Renderable;
use KikCMS\DataTables\Pages;
use KikCMS\Models\Page;
use KikCMS\Services\DataTable\PageRearrangeService;

/**
 * @property PageRearrangeService $pageRearrangeService
 */
class PagesDataTableController extends DataTableController
{
    /**
     * @return string
     */
    public function treeOrderAction()
    {
        $pageId       = $this->request->getPost('pageId');
        $targetPageId = $this->request->getPost('targetPageId');
        $rearrange    = $this->request->getPost('position');

        $page       = Page::getById($pageId);
        $targetPage = Page::getById($targetPageId);

        $this->pageRearrangeService->rearrange($page, $targetPage, $rearrange);

        $dataTable = $this->getRenderable();

        return json_encode(['table' => $dataTable->renderTable()]);
    }

    /**
     * @inheritdoc
     */
    protected function getRenderable(): Renderable
    {
        /** @var Pages $dataTable */
        $dataTable = parent::getRenderable();

        if ($pageId = $dataTable->getFilters()->getEditId()) {
            $page = Page::getById($pageId);
            $dataTable->getFilters()->setPageType($page->type);
        }

        return $dataTable;
    }
}