<?php

declare (strict_types=1);
namespace WPForms\Vendor\Square\Models;

use stdClass;
/**
 * Defines the fields included in the response body from the
 * [BulkDeleteCustomers]($e/Customers/BulkDeleteCustomers) endpoint.
 */
class BulkDeleteCustomersResponse implements \JsonSerializable
{
    /**
     * @var array<string,DeleteCustomerResponse>|null
     */
    private $responses;
    /**
     * @var Error[]|null
     */
    private $errors;
    /**
     * Returns Responses.
     * A map of responses that correspond to individual delete requests, represented by
     * key-value pairs.
     *
     * Each key is the customer ID that was specified for a delete request and each value
     * is the corresponding response.
     * If the request succeeds, the value is an empty object (`{ }`).
     * If the request fails, the value contains any errors that occurred during the request.
     *
     * @return array<string,DeleteCustomerResponse>|null
     */
    public function getResponses() : ?array
    {
        return $this->responses;
    }
    /**
     * Sets Responses.
     * A map of responses that correspond to individual delete requests, represented by
     * key-value pairs.
     *
     * Each key is the customer ID that was specified for a delete request and each value
     * is the corresponding response.
     * If the request succeeds, the value is an empty object (`{ }`).
     * If the request fails, the value contains any errors that occurred during the request.
     *
     * @maps responses
     *
     * @param array<string,DeleteCustomerResponse>|null $responses
     */
    public function setResponses(?array $responses) : void
    {
        $this->responses = $responses;
    }
    /**
     * Returns Errors.
     * Any top-level errors that prevented the bulk operation from running.
     *
     * @return Error[]|null
     */
    public function getErrors() : ?array
    {
        return $this->errors;
    }
    /**
     * Sets Errors.
     * Any top-level errors that prevented the bulk operation from running.
     *
     * @maps errors
     *
     * @param Error[]|null $errors
     */
    public function setErrors(?array $errors) : void
    {
        $this->errors = $errors;
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
        if (isset($this->responses)) {
            $json['responses'] = $this->responses;
        }
        if (isset($this->errors)) {
            $json['errors'] = $this->errors;
        }
        $json = \array_filter($json, function ($val) {
            return $val !== null;
        });
        return !$asArrayWhenEmpty && empty($json) ? new stdClass() : $json;
    }
}
