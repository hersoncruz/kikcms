<?php

namespace unit\Classes\DataTable;


use Exception;
use Helpers\DataTables\TestSelectDataTable;
use KikCMS\Classes\DataTable\SelectDataTableFilters;
use Phalcon\Mvc\Model\Query\Builder;
use PHPUnit\Framework\TestCase;

class SelectDataTableTest extends TestCase
{
    public function testSetQueryToShowSelectionFirst()
    {
        $filters = (new SelectDataTableFilters())->setSelectedValues([])->setLanguageCode('nl');

        $selectDataTable = new TestSelectDataTable($filters);

        // test no selected values
        $query = (new Builder)->columns(['col1']);

        $selectDataTable->setQueryToShowSelectionFirst($query);

        $this->assertEquals('0 AS dataTableSelectIds', $query->getColumns()[1]);

        // test selected values
        $selectDataTable->getFilters()->setSelectedValues([1]);

        $query = (new Builder)->columns(['col1']);

        $selectDataTable->setQueryToShowSelectionFirst($query);

        $this->assertEquals('IF(a.id IN(1), 1, 0) AS dataTableSelectIds', $query->getColumns()[1]);

        // test order by
        $query->orderBy('col1 ASC');

        $selectDataTable->setQueryToShowSelectionFirst($query);

        $this->assertStringStartsWith('dataTableSelectIds DESC', $query->getOrderBy());

        // test order by array
        $query->orderBy(['col1 ASC']);

        $selectDataTable->setQueryToShowSelectionFirst($query);

        $this->assertEquals('dataTableSelectIds DESC', $query->getOrderBy()[0]);

        // test exception
        $query = (new Builder);

        $this->expectException(Exception::class);

        $selectDataTable->setQueryToShowSelectionFirst($query);
    }
}