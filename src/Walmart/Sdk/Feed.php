<?php
declare(strict_types=1);

namespace rollun\Walmart\Sdk;

/**
 * Class Feed
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Feed extends Base
{
    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getAFeedStatus
     *
     * @param string $feedId
     * @param bool   $includeDetails
     * @param int    $limit
     * @param int    $offset
     *
     * @return array
     */
    public function getFeedStatus(string $feedId, bool $includeDetails = true, int $limit = 20, int $offset = 0): array
    {
        $includeDetails = $includeDetails === true ? 'true' : 'false';

        return $this->request("feeds/$feedId?includeDetails=$includeDetails&limit=$limit&offset=$offset");
    }
}
