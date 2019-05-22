<?php

declare(strict_types=1);

namespace Tests\FrameworkBundle\Unit\Component\Paginator;

use PHPUnit\Framework\TestCase;
use Shopsys\FrameworkBundle\Component\Paginator\PaginationResult;

class PaginationResultTest extends TestCase
{
    public function getTestPageCountData()
    {
        return [
            [1, 10, 40, [], 4],
            [1, 10, 41, [], 5],
            [1, 10, 49, [], 5],
            [1, 10, 50, [], 5],
            [1, 10, 51, [], 6],
            [1, 10, 5, [], 1],
            [1, 0, 0, [], 0],
            [1, null, 5, [], 1],
            [1, null, 0, [], 0],
        ];
    }

    /**
     * @dataProvider getTestPageCountData
     * @param mixed $page
     * @param mixed $pageSize
     * @param mixed $totalCount
     * @param mixed $results
     * @param mixed $expectedPageCount
     */
    public function testGetPageCount($page, $pageSize, $totalCount, $results, $expectedPageCount)
    {
        $paginationResult = new PaginationResult($page, $pageSize, $totalCount, $results);

        $this->assertSame($expectedPageCount, $paginationResult->getPageCount());
    }

    public function getTestIsFirstData()
    {
        yield [1, 10, 20, true];
        yield [2, 10, 20, false];
        yield [1, null, 20, true];
    }

    /**
     * @dataProvider getTestIsFirstData
     * @param int $page
     * @param int|null $pageSize
     * @param int $totalCount
     * @param bool $expectedIsFirst
     */
    public function testIsFirst(int $page, ?int $pageSize, int $totalCount, bool $expectedIsFirst)
    {
        $paginationResult = new PaginationResult($page, $pageSize, $totalCount, []);

        $this->assertSame($expectedIsFirst, $paginationResult->isFirst());
    }

    public function getTestIsLastData()
    {
        yield [1, 10, 20, false];
        yield [2, 10, 20, true];
        yield [1, 10, 21, false];
        yield [2, 10, 21, false];
        yield [3, 10, 21, true];
        yield [1, null, 20, true];
    }

    /**
     * @dataProvider getTestIsLastData
     * @param int $page
     * @param int|null $pageSize
     * @param int $totalCount
     * @param bool $expectedIsLast
     */
    public function testIsLast(int $page, ?int $pageSize, int $totalCount, bool $expectedIsLast)
    {
        $paginationResult = new PaginationResult($page, $pageSize, $totalCount, []);

        $this->assertSame($expectedIsLast, $paginationResult->isLast());
    }

    public function getTestGetPreviousData()
    {
        yield [1, 10, 20, null];
        yield [2, 10, 20, 1];
        yield [3, 10, 21, 2];
        yield [1, null, 20, null];
    }

    /**
     * @dataProvider getTestGetPreviousData
     * @param int $page
     * @param int|null $pageSize
     * @param int $totalCount
     * @param int|null $expectedPrevious
     */
    public function testGetPrevious(int $page, ?int $pageSize, int $totalCount, ?int $expectedPrevious)
    {
        $paginationResult = new PaginationResult($page, $pageSize, $totalCount, []);

        $this->assertSame($expectedPrevious, $paginationResult->getPrevious());
    }

    public function getTestGetNextData()
    {
        yield [1, 10, 20, 2];
        yield [2, 10, 20, null];
        yield [2, 10, 21, 3];
        yield [3, 10, 21, null];
        yield [1, null, 20, null];
    }

    /**
     * @dataProvider getTestGetNextData
     * @param int $page
     * @param int|null $pageSize
     * @param int $totalCount
     * @param int|null $expectedNext
     */
    public function testGetNext(int $page, ?int $pageSize, int $totalCount, ?int $expectedNext)
    {
        $paginationResult = new PaginationResult($page, $pageSize, $totalCount, []);

        $this->assertSame($expectedNext, $paginationResult->getNext());
    }
}
