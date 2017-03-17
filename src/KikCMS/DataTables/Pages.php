<?php

namespace KikCMS\DataTables;


use KikCMS\Classes\DataTable\DataTable;
use KikCMS\Classes\Renderable\Filters;
use KikCMS\Forms\MenuForm;
use KikCMS\Forms\PageForm;
use KikCMS\Models\Page;
use KikCMS\Models\PageLanguage;
use KikCMS\Services\DataTable\PageRearrangeService;
use KikCMS\Services\DataTable\PagesDataTableFilters;
use Phalcon\Mvc\Model\Query\Builder;

/**
 * @property PageRearrangeService $pageRearrangeService
 */
class Pages extends DataTable
{
    /** @inheritdoc */
    protected $jsClass = 'PagesDataTable';

    /** @inheritdoc */
    protected $searchableFields = ['name'];

    /** @inheritdoc */
    protected $orderableFields = ['id' => 'p.id'];

    /** @inheritdoc */
    protected $preLoadWysiwygJs = true;

    /** @inheritdoc */
    public $indexView = 'datatables/page/index';

    /** @inheritdoc */
    public $tableView = 'datatables/page/table';

    protected function addAssets()
    {
        parent::addAssets();

        $this->view->assets->addCss('cmsassets/css/pagesDataTable.css');
        $this->view->assets->addJs('cmsassets/js/pagesDataTable.js');
        $this->view->assets->addJs('cmsassets/js/datatable/sortControl.js');
        $this->view->assets->addJs('cmsassets/js/treeSortControl.js');
    }

    /**
     * @inheritdoc
     */
    public function delete(array $ids)
    {
        $deletedPages = Page::getByIdList($ids);

        parent::delete($ids);

        foreach ($deletedPages as $page) {
            $this->pageRearrangeService->updateLeftSiblingsOrder($page);
        }

        $this->pageRearrangeService->updateNestedSet();
    }

    /**
     * @inheritdoc
     */
    public function getEmptyFilters(): Filters
    {
        return new PagesDataTableFilters();
    }

    /**
     * @return PagesDataTableFilters|Filters
     */
    public function getFilters(): Filters
    {
        return parent::getFilters();
    }

    /**
     * @inheritdoc
     */
    public function getLabels(): string
    {
        switch ($this->getFilters()->getPageType()) {
            case Page::TYPE_MENU:
                return 'dataTables.menus';
            break;

            case Page::TYPE_LINK:
                return 'dataTables.links';
            break;

            case Page::TYPE_ALIAS:
                return 'dataTables.aliases';
            break;
        }

        return 'dataTables.pages';
    }

    /**
     * @inheritdoc
     */
    public function getModel(): string
    {
        return Page::class;
    }

    /**
     * @inheritdoc
     */
    public function getFormClass(): string
    {
        switch ($this->getFilters()->getPageType()) {
            case Page::TYPE_MENU:
                return MenuForm::class;
            break;
        }

        return PageForm::class;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultQuery()
    {
        $defaultQuery = new Builder();
        $defaultQuery->from(['p' => $this->getModel()]);
        $defaultQuery->leftJoin(PageLanguage::class, 'p.id = pl.page_id', 'pl');
        $defaultQuery->orderBy('IFNULL(p.lft, 99999 + IFNULL(p.display_order, 99999 + p.id)) asc');
        $defaultQuery->columns([
            'pl.name', 'p.id', 'p.display_order', 'p.level', 'p.lft', 'p.rgt', 'p.type', 'p.parent_id',
            'p.menu_max_level'
        ]);

        return $defaultQuery;
    }

    /**
     * @inheritdoc
     */
    protected function initialize()
    {
        $this->setFieldFormatting('name', [$this, 'formatName']);
    }

    /**
     * @param $value
     * @return string
     */
    protected function formatName($value)
    {
        // disable dragging / tree structure when sorting or searching
        if ($this->filters->getSearch() || $this->filters->getSortColumn()) {
            return $value;
        }

        return '<span class="name">' . $value . '</span>';
    }
}