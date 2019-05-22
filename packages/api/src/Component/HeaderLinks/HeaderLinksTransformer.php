<?php

declare(strict_types=1);

namespace Shopsys\ApiBundle\Component\HeaderLinks;

use GuzzleHttp\Psr7\Uri;
use Shopsys\FrameworkBundle\Component\Paginator\PaginationResult;

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
        $uri = new Uri($baseUrl);

        if (!$paginationResult->isFirst()) {
            $firstUrl = $uri->withQuery('page=1')->__toString();
            $previousUrl = $uri->withQuery('page=' . $paginationResult->getPrevious())->__toString();

            $links = $links
                ->add($firstUrl, 'first')
                ->add($previousUrl, 'prev');
        }

        if (!$paginationResult->isLast()) {
            $nextUrl = $uri->withQuery('page=' . $paginationResult->getNext())->__toString();
            $lastUrl = $uri->withQuery('page=' . $paginationResult->getPageCount())->__toString();

            $links = $links
                ->add($nextUrl, 'next')
                ->add($lastUrl, 'last');
        }

        return $links;
    }
}
