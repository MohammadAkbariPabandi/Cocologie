<?php

declare (strict_types=1);
namespace WPForms\Vendor\Square\Models;

use stdClass;
class SearchTerminalCheckoutsRequest implements \JsonSerializable
{
    /**
     * @var TerminalCheckoutQuery|null
     */
    private $query;
    /**
     * @var string|null
     */
    private $cursor;
    /**
     * @var int|null
     */
    private $limit;
    /**
     * Returns Query.
     */
    public function getQuery() : ?TerminalCheckoutQuery
    {
        return $this->query;
    }
    /**
     * Sets Query.
     *
     * @maps query
     */
    public function setQuery(?TerminalCheckoutQuery $query) : void
    {
        $this->query = $query;
    }
    /**
     * Returns Cursor.
     * A pagination cursor returned by a previous call to this endpoint.
     * Provide this cursor to retrieve the next set of results for the original query.
     * See [Pagination](https://developer.squareup.com/docs/build-basics/common-api-patterns/pagination)
     * for more information.
     */
    public function getCursor() : ?string
    {
        return $this->cursor;
    }
    /**
     * Sets Cursor.
     * A pagination cursor returned by a previous call to this endpoint.
     * Provide this cursor to retrieve the next set of results for the original query.
     * See [Pagination](https://developer.squareup.com/docs/build-basics/common-api-patterns/pagination)
     * for more information.
     *
     * @maps cursor
     */
    public function setCursor(?string $cursor) : void
    {
        $this->cursor = $cursor;
    }
    /**
     * Returns Limit.
     * Limits the number of results returned for a single request.
     */
    public function getLimit() : ?int
    {
        return $this->limit;
    }
    /**
     * Sets Limit.
     * Limits the number of results returned for a single request.
     *
     * @maps limit
     */
    public function setLimit(?int $limit) : void
    {
        $this->limit = $limit;
    }
    /**
     * Encode this object to JSON
     *
     * @param bool $asArrayWhenEmpty Whether to serialize this model as an array whenever no fields
     *        are set. (default: false)
     *
     * @return array|stdClass
     */
    #[\ReturnTypeWillChange] // @phan-suppress-current-line PhanUndeclaredClassAttribute for (php < 8.1)
    public function jsonSerialize(bool $asArrayWhenEmpty = \false)
    {
        $json = [];
        if (isset($this->query)) {
            $json['query'] = $this->query;
        }
        if (isset($this->cursor)) {
            $json['cursor'] = $this->cursor;
        }
        if (isset($this->limit)) {
            $json['limit'] = $this->limit;
        }
        $json = \array_filter($json, function ($val) {
            return $val !== null;
        });
        return !$asArrayWhenEmpty && empty($json) ? new stdClass() : $json;
    }
}
