<?php

declare(strict_types=1);

namespace Shopsys\ApiBundle\Component\HeaderLinks;

use Shopsys\FrameworkBundle\Component\Paginator\PaginationResult;
use Symfony\Component\HttpFoundation\Request;

class HeaderLinksTransformer
{
    /**
     * @param \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult $paginationResult
     * @param string $baseUrl
     * @return \Shopsys\ApiBundle\Component\HeaderLinks\HeaderLinks
     */
    public function fromPaginationResult(PaginationResult $paginationResult, string $baseUrl): HeaderLinks
    {
        $links = new HeaderLinks();

        if (!$paginationResult->isFirst()) {
            $firstUrl = $this->changeParameter($baseUrl, 'page', '1');
            $previousUrl = $this->changeParameter($baseUrl, 'page', (string)$paginationResult->getPrevious());

            $links = $links
                ->add($firstUrl, 'first')
                ->add($previousUrl, 'prev');
        }

        if (!$paginationResult->isLast()) {
            $nextUrl = $this->changeParameter($baseUrl, 'page', (string)$paginationResult->getNext());
            $lastUrl = $this->changeParameter($baseUrl, 'page', (string)$paginationResult->getPageCount());

            $links = $links
                ->add($nextUrl, 'next')
                ->add($lastUrl, 'last');
        }

        return $links;
    }

    /**
     * @param string $baseUrl
     * @param string $parameter
     * @param string $value
     * @return string
     */
    protected function changeParameter(string $baseUrl, string $parameter, string $value): string
    {
        return Request::create($baseUrl, 'GET', [$parameter => $value])->getUri();
    }
}
