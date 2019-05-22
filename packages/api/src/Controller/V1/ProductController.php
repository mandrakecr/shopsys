<?php

namespace Shopsys\ApiBundle\Controller\V1;

use FOS\RestBundle\Controller\AbstractFOSRestController;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
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
}
