<?php

namespace SS6\ShopBundle\Tests\Unit\Model\Grid;

use PHPUnit_Framework_TestCase;
use SS6\ShopBundle\Component\Paginator\PaginationResult;
use SS6\ShopBundle\Model\Grid\DataSourceInterface;
use SS6\ShopBundle\Model\Grid\Grid;
use SS6\ShopBundle\Model\Grid\GridView;
use SS6\ShopBundle\Model\Grid\Ordering\GridOrderingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Twig_Environment;

class GridTest extends PHPUnit_Framework_TestCase {

	public function testGetParametersFromRequest() {
		$getParameters = [
			Grid::GET_PARAMETER => [
				'gridId' => [
					'limit' => '100',
					'page' => '3',
					'order' => '-name',
				],
			],
		];

		$request = new Request($getParameters);
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);

		$this->assertSame('gridId', $grid->getId());
		$this->assertSame(100, $grid->getLimit());
		$this->assertSame(3, $grid->getPage());
		$this->assertSame('name', $grid->getOrderSourceColumnName());
		$this->assertSame('desc', $grid->getOrderDirection());
	}

	public function testAddColumn() {
		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);
		$grid->addColumn('columnId1', 'sourceColumnName1', 'title1', true)->setClassAttribute('classAttribute');
		$grid->addColumn('columnId2', 'sourceColumnName2', 'title2', false);
		$columns = $grid->getColumns();

		$this->assertCount(2, $columns);
		$column2 = array_pop($columns);
		/* @var $column2 \SS6\ShopBundle\Model\Grid\Column */
		$column1 = array_pop($columns);
		/* @var $column1 \SS6\ShopBundle\Model\Grid\Column */

		$this->assertSame('columnId1', $column1->getId());
		$this->assertSame('sourceColumnName1', $column1->getSourceColumnName());
		$this->assertSame('title1', $column1->getTitle());
		$this->assertSame(true, $column1->isSortable());
		$this->assertSame('classAttribute', $column1->getClassAttribute());

		$this->assertSame('columnId2', $column2->getId());
		$this->assertSame('sourceColumnName2', $column2->getSourceColumnName());
		$this->assertSame('title2', $column2->getTitle());
		$this->assertSame(false, $column2->isSortable());
		$this->assertSame('', $column2->getClassAttribute());
	}

	public function testAddColumnDuplicateId() {
		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);
		$grid->addColumn('columnId1', 'sourceColumnName1', 'title1');

		$this->setExpectedException(\SS6\ShopBundle\Model\Grid\Exception\DuplicateColumnIdException::class);
		$grid->addColumn('columnId1', 'sourceColumnName2', 'title2');
	}

	public function testAllowPaging() {
		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);
		$grid->allowPaging();
		$this->assertTrue($grid->isAllowedPaging());
	}

	public function testAllowPagingDefaultDisable() {
		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);
		$this->assertFalse($grid->isAllowedPaging());
	}

	public function testSetDefaultOrder() {
		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);

		$grid->setDefaultOrder('columnId1', DataSourceInterface::ORDER_DESC);
		$this->assertSame('-columnId1', $grid->getOrderSourceColumnNameWithDirection());

		$grid->setDefaultOrder('columnId2', DataSourceInterface::ORDER_ASC);
		$this->assertSame('columnId2', $grid->getOrderSourceColumnNameWithDirection());
	}

	public function testSetDefaultOrderWithRequest() {
		$getParameters = [
			Grid::GET_PARAMETER => [
				'gridId' => [
					'order' => '-request',
				],
			],
		];

		$request = new Request($getParameters);
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);

		$grid->setDefaultOrder('default', DataSourceInterface::ORDER_ASC);
		$this->assertSame('-request', $grid->getOrderSourceColumnNameWithDirection());
	}

	public function testCreateView() {
		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);
		$dataSourceMock = $this->getMockBuilder(DataSourceInterface::class)
			->setMethods(['getTotalRowsCount', 'getPaginatedRows'])
			->getMockForAbstractClass();
		$dataSourceMock->expects($this->never())->method('getTotalRowsCount');
		$dataSourceMock->expects($this->once())->method('getPaginatedRows')
			->will($this->returnValue(new PaginationResult(1, 1, 0, [])));

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);
		$gridView = $grid->createView();

		$this->assertInstanceOf(GridView::class, $gridView);
	}

	public function testCreateViewWithPaging() {
		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);
		$dataSourceMock = $this->getMockBuilder(DataSourceInterface::class)
			->setMethods(['getTotalRowsCount', 'getPaginatedRows'])
			->getMockForAbstractClass();
		$dataSourceMock->expects($this->once())->method('getTotalRowsCount')->will($this->returnValue(0));
		$dataSourceMock->expects($this->once())->method('getPaginatedRows')
			->will($this->returnValue(new PaginationResult(1, 1, 0, [])));

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);
		$grid->allowPaging();
		$gridView = $grid->createView();

		$this->assertInstanceOf(GridView::class, $gridView);
	}

	public function testEnableDragAndDrop() {
		$entityClass = 'Path\To\Entity\Class';

		$request = new Request();
		$requestStack = new RequestStack();
		$requestStack->push($request);

		$twigMock = $this->getMock(Twig_Environment::class);
		$routerMock = $this->getMock(Router::class, [], [], '', false);
		$dataSourceMock = $this->getMock(DataSourceInterface::class);
		$gridOrderingServiceMock = $this->getMock(GridOrderingService::class);

		$grid = new Grid('gridId', $dataSourceMock, $requestStack, $routerMock, $twigMock, $gridOrderingServiceMock);

		$this->assertFalse($grid->isDragAndDrop());
		$grid->enableDragAndDrop($entityClass);
		$this->assertTrue($grid->isDragAndDrop());
	}

}