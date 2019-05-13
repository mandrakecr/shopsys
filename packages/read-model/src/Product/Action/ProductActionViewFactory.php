<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Product\Action;

use Shopsys\FrameworkBundle\Model\Product\Product;

/**
 * @experimental
 */
class ProductActionViewFactory
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @param string $absoluteUrl
     * @return \Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface
     */
    public function createFromProduct(Product $product, string $absoluteUrl): ProductActionViewInterface
    {
        return new ProductActionView(
            $product->getId(),
            $product->isSellingDenied(),
            $product->isMainVariant(),
            $absoluteUrl
        );
    }
}
