<?php

namespace Shopsys\FrameworkBundle\Model\Sitemap;

class SitemapFilePrefixer
{
    /**
     * @param int $domainId
     * @return string
     */
    public function getSitemapFilePrefixForDomain($domainId)
    {
        return 'domain_' . $domainId . '_sitemap';
    }
}
