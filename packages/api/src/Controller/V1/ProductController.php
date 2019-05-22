<?php

namespace Shopsys\ApiBundle\Controller\V1;

use FOS\RestBundle\Controller\AbstractFOSRestController;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Shopsys\ApiBundle\Component\HeaderLinks\HeaderLinks;
use Shopsys\FrameworkBundle\Model\Product\ProductFacade;
use Symfony\Component\HttpFoundation\Response;

/**
 * @experimental
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductFacade
     */
    private $productFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductFacade $productFacade
     */
    public function __construct(ProductFacade $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * Retrieves an Product resource
     * @Get("/products/{uuid}")
     * @param string $uuid
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductAction(string $uuid): Response
    {
        $product = $this->productFacade->getByUuid($uuid);

        return $this->handleView(View::create($product, 200));
    }

    /**
     * Retrieves an multiple Product resources
     * @Get("/products")
     * @QueryParam(name="page", requirements="\d+", default=1)
     * @QueryParam(name="uuids", map=true, requirements="[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}", allowBlank=false)
     * @param \FOS\RestBundle\Request\ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductsAction(ParamFetcher $paramFetcher): Response
    {
        $products = $this->productFacade->getById(1);

        $page = (int)$paramFetcher->get('page');
        $filterUuids = $paramFetcher->get('uuids');
        $isFilterUuid = is_array($filterUuids); // if there are no uuids, $filterUuids === ''

        $links = (new HeaderLinks())
            ->add('http://localhost:8000/api/v1/products?page=2', 'next')
            ->add('http://localhost:8000/api/v1/products?page=30', 'last');

        $view = View::create($products, 200, ['Link', $links->format()]);

        return $this->handleView($view);
    }
}
