<?php

namespace Shopsys\ApiBundle\Controller\V1;

use DateTimeInterface;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Product\Product;

class ApiProductTranslator
{
    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @return array
     */
    public function translate(Product $product): array
    {
        $names = $this->translateNames($product);
        $shortDescriptions = $this->translateShortDescriptions($product);
        $longDescriptions = $this->translateLongDescriptions($product);

        return [
            'uuid' => $product->getUuid(),
            'name' => $names,
            'hidden' => $product->isHidden(),
            'sellingDenied' => $product->getCalculatedSellingDenied(),
            'sellingFrom' => $this->formatDateTime($product->getSellingFrom()),
            'sellingTo' => $this->formatDateTime($product->getSellingTo()),
            'catnum' => $product->getCatnum(),
            'ean' => $product->getEan(),
            'partno' => $product->getPartno(),
            'shortDescription' => $shortDescriptions,
            'longDescription' => $longDescriptions,
        ];
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @return array
     */
    protected function translateNames(Product $product): array
    {
        $result = [];
        foreach ($this->domain->getAllLocales() as $locale) {
            $result[$locale] = $product->getName($locale);
        }
        return $result;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @return array
     */
    protected function translateShortDescriptions(Product $product): array
    {
        $result = [];
        foreach ($this->domain->getAllIds() as $domainId) {
            $result[$domainId] = $product->getShortDescription($domainId);
        }
        return $result;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @return array
     */
    protected function translateLongDescriptions(Product $product): array
    {
        $result = [];
        foreach ($this->domain->getAllIds() as $domainId) {
            $result[$domainId] = $product->getDescription($domainId);
        }
        return $result;
    }

    /**
     * @param \DateTimeInterface|null $dateTime
     * @return string|null
     */
    protected function formatDateTime(?DateTimeInterface $dateTime): ?string
    {
        if ($dateTime === null) {
            return null;
        }

        return $dateTime->format(DateTimeInterface::ISO8601);
    }
}
