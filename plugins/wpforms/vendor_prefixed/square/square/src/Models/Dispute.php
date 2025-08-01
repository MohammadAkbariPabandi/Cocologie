<?php

declare (strict_types=1);
namespace WPForms\Vendor\Square\Models;

use stdClass;
/**
 * Represents a [dispute](https://developer.squareup.com/docs/disputes-api/overview) a cardholder
 * initiated with their bank.
 */
class Dispute implements \JsonSerializable
{
    /**
     * @var array
     */
    private $disputeId = [];
    /**
     * @var string|null
     */
    private $id;
    /**
     * @var Money|null
     */
    private $amountMoney;
    /**
     * @var string|null
     */
    private $reason;
    /**
     * @var string|null
     */
    private $state;
    /**
     * @var array
     */
    private $dueAt = [];
    /**
     * @var DisputedPayment|null
     */
    private $disputedPayment;
    /**
     * @var array
     */
    private $evidenceIds = [];
    /**
     * @var string|null
     */
    private $cardBrand;
    /**
     * @var string|null
     */
    private $createdAt;
    /**
     * @var string|null
     */
    private $updatedAt;
    /**
     * @var array
     */
    private $brandDisputeId = [];
    /**
     * @var array
     */
    private $reportedDate = [];
    /**
     * @var array
     */
    private $reportedAt = [];
    /**
     * @var int|null
     */
    private $version;
    /**
     * @var array
     */
    private $locationId = [];
    /**
     * Returns Dispute Id.
     * The unique ID for this `Dispute`, generated by Square.
     */
    public function getDisputeId() : ?string
    {
        if (\count($this->disputeId) == 0) {
            return null;
        }
        return $this->disputeId['value'];
    }
    /**
     * Sets Dispute Id.
     * The unique ID for this `Dispute`, generated by Square.
     *
     * @maps dispute_id
     */
    public function setDisputeId(?string $disputeId) : void
    {
        $this->disputeId['value'] = $disputeId;
    }
    /**
     * Unsets Dispute Id.
     * The unique ID for this `Dispute`, generated by Square.
     */
    public function unsetDisputeId() : void
    {
        $this->disputeId = [];
    }
    /**
     * Returns Id.
     * The unique ID for this `Dispute`, generated by Square.
     */
    public function getId() : ?string
    {
        return $this->id;
    }
    /**
     * Sets Id.
     * The unique ID for this `Dispute`, generated by Square.
     *
     * @maps id
     */
    public function setId(?string $id) : void
    {
        $this->id = $id;
    }
    /**
     * Returns Amount Money.
     * Represents an amount of money. `Money` fields can be signed or unsigned.
     * Fields that do not explicitly define whether they are signed or unsigned are
     * considered unsigned and can only hold positive amounts. For signed fields, the
     * sign of the value indicates the purpose of the money transfer. See
     * [Working with Monetary Amounts](https://developer.squareup.com/docs/build-basics/working-with-
     * monetary-amounts)
     * for more information.
     */
    public function getAmountMoney() : ?Money
    {
        return $this->amountMoney;
    }
    /**
     * Sets Amount Money.
     * Represents an amount of money. `Money` fields can be signed or unsigned.
     * Fields that do not explicitly define whether they are signed or unsigned are
     * considered unsigned and can only hold positive amounts. For signed fields, the
     * sign of the value indicates the purpose of the money transfer. See
     * [Working with Monetary Amounts](https://developer.squareup.com/docs/build-basics/working-with-
     * monetary-amounts)
     * for more information.
     *
     * @maps amount_money
     */
    public function setAmountMoney(?Money $amountMoney) : void
    {
        $this->amountMoney = $amountMoney;
    }
    /**
     * Returns Reason.
     * The list of possible reasons why a cardholder might initiate a
     * dispute with their bank.
     */
    public function getReason() : ?string
    {
        return $this->reason;
    }
    /**
     * Sets Reason.
     * The list of possible reasons why a cardholder might initiate a
     * dispute with their bank.
     *
     * @maps reason
     */
    public function setReason(?string $reason) : void
    {
        $this->reason = $reason;
    }
    /**
     * Returns State.
     * The list of possible dispute states.
     */
    public function getState() : ?string
    {
        return $this->state;
    }
    /**
     * Sets State.
     * The list of possible dispute states.
     *
     * @maps state
     */
    public function setState(?string $state) : void
    {
        $this->state = $state;
    }
    /**
     * Returns Due At.
     * The deadline by which the seller must respond to the dispute, in [RFC 3339 format](https://developer.
     * squareup.com/docs/build-basics/common-data-types/working-with-dates).
     */
    public function getDueAt() : ?string
    {
        if (\count($this->dueAt) == 0) {
            return null;
        }
        return $this->dueAt['value'];
    }
    /**
     * Sets Due At.
     * The deadline by which the seller must respond to the dispute, in [RFC 3339 format](https://developer.
     * squareup.com/docs/build-basics/common-data-types/working-with-dates).
     *
     * @maps due_at
     */
    public function setDueAt(?string $dueAt) : void
    {
        $this->dueAt['value'] = $dueAt;
    }
    /**
     * Unsets Due At.
     * The deadline by which the seller must respond to the dispute, in [RFC 3339 format](https://developer.
     * squareup.com/docs/build-basics/common-data-types/working-with-dates).
     */
    public function unsetDueAt() : void
    {
        $this->dueAt = [];
    }
    /**
     * Returns Disputed Payment.
     * The payment the cardholder disputed.
     */
    public function getDisputedPayment() : ?DisputedPayment
    {
        return $this->disputedPayment;
    }
    /**
     * Sets Disputed Payment.
     * The payment the cardholder disputed.
     *
     * @maps disputed_payment
     */
    public function setDisputedPayment(?DisputedPayment $disputedPayment) : void
    {
        $this->disputedPayment = $disputedPayment;
    }
    /**
     * Returns Evidence Ids.
     * The IDs of the evidence associated with the dispute.
     *
     * @return string[]|null
     */
    public function getEvidenceIds() : ?array
    {
        if (\count($this->evidenceIds) == 0) {
            return null;
        }
        return $this->evidenceIds['value'];
    }
    /**
     * Sets Evidence Ids.
     * The IDs of the evidence associated with the dispute.
     *
     * @maps evidence_ids
     *
     * @param string[]|null $evidenceIds
     */
    public function setEvidenceIds(?array $evidenceIds) : void
    {
        $this->evidenceIds['value'] = $evidenceIds;
    }
    /**
     * Unsets Evidence Ids.
     * The IDs of the evidence associated with the dispute.
     */
    public function unsetEvidenceIds() : void
    {
        $this->evidenceIds = [];
    }
    /**
     * Returns Card Brand.
     * Indicates a card's brand, such as `VISA` or `MASTERCARD`.
     */
    public function getCardBrand() : ?string
    {
        return $this->cardBrand;
    }
    /**
     * Sets Card Brand.
     * Indicates a card's brand, such as `VISA` or `MASTERCARD`.
     *
     * @maps card_brand
     */
    public function setCardBrand(?string $cardBrand) : void
    {
        $this->cardBrand = $cardBrand;
    }
    /**
     * Returns Created At.
     * The timestamp when the dispute was created, in RFC 3339 format.
     */
    public function getCreatedAt() : ?string
    {
        return $this->createdAt;
    }
    /**
     * Sets Created At.
     * The timestamp when the dispute was created, in RFC 3339 format.
     *
     * @maps created_at
     */
    public function setCreatedAt(?string $createdAt) : void
    {
        $this->createdAt = $createdAt;
    }
    /**
     * Returns Updated At.
     * The timestamp when the dispute was last updated, in RFC 3339 format.
     */
    public function getUpdatedAt() : ?string
    {
        return $this->updatedAt;
    }
    /**
     * Sets Updated At.
     * The timestamp when the dispute was last updated, in RFC 3339 format.
     *
     * @maps updated_at
     */
    public function setUpdatedAt(?string $updatedAt) : void
    {
        $this->updatedAt = $updatedAt;
    }
    /**
     * Returns Brand Dispute Id.
     * The ID of the dispute in the card brand system, generated by the card brand.
     */
    public function getBrandDisputeId() : ?string
    {
        if (\count($this->brandDisputeId) == 0) {
            return null;
        }
        return $this->brandDisputeId['value'];
    }
    /**
     * Sets Brand Dispute Id.
     * The ID of the dispute in the card brand system, generated by the card brand.
     *
     * @maps brand_dispute_id
     */
    public function setBrandDisputeId(?string $brandDisputeId) : void
    {
        $this->brandDisputeId['value'] = $brandDisputeId;
    }
    /**
     * Unsets Brand Dispute Id.
     * The ID of the dispute in the card brand system, generated by the card brand.
     */
    public function unsetBrandDisputeId() : void
    {
        $this->brandDisputeId = [];
    }
    /**
     * Returns Reported Date.
     * The timestamp when the dispute was reported, in RFC 3339 format.
     */
    public function getReportedDate() : ?string
    {
        if (\count($this->reportedDate) == 0) {
            return null;
        }
        return $this->reportedDate['value'];
    }
    /**
     * Sets Reported Date.
     * The timestamp when the dispute was reported, in RFC 3339 format.
     *
     * @maps reported_date
     */
    public function setReportedDate(?string $reportedDate) : void
    {
        $this->reportedDate['value'] = $reportedDate;
    }
    /**
     * Unsets Reported Date.
     * The timestamp when the dispute was reported, in RFC 3339 format.
     */
    public function unsetReportedDate() : void
    {
        $this->reportedDate = [];
    }
    /**
     * Returns Reported At.
     * The timestamp when the dispute was reported, in RFC 3339 format.
     */
    public function getReportedAt() : ?string
    {
        if (\count($this->reportedAt) == 0) {
            return null;
        }
        return $this->reportedAt['value'];
    }
    /**
     * Sets Reported At.
     * The timestamp when the dispute was reported, in RFC 3339 format.
     *
     * @maps reported_at
     */
    public function setReportedAt(?string $reportedAt) : void
    {
        $this->reportedAt['value'] = $reportedAt;
    }
    /**
     * Unsets Reported At.
     * The timestamp when the dispute was reported, in RFC 3339 format.
     */
    public function unsetReportedAt() : void
    {
        $this->reportedAt = [];
    }
    /**
     * Returns Version.
     * The current version of the `Dispute`.
     */
    public function getVersion() : ?int
    {
        return $this->version;
    }
    /**
     * Sets Version.
     * The current version of the `Dispute`.
     *
     * @maps version
     */
    public function setVersion(?int $version) : void
    {
        $this->version = $version;
    }
    /**
     * Returns Location Id.
     * The ID of the location where the dispute originated.
     */
    public function getLocationId() : ?string
    {
        if (\count($this->locationId) == 0) {
            return null;
        }
        return $this->locationId['value'];
    }
    /**
     * Sets Location Id.
     * The ID of the location where the dispute originated.
     *
     * @maps location_id
     */
    public function setLocationId(?string $locationId) : void
    {
        $this->locationId['value'] = $locationId;
    }
    /**
     * Unsets Location Id.
     * The ID of the location where the dispute originated.
     */
    public function unsetLocationId() : void
    {
        $this->locationId = [];
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
        if (!empty($this->disputeId)) {
            $json['dispute_id'] = $this->disputeId['value'];
        }
        if (isset($this->id)) {
            $json['id'] = $this->id;
        }
        if (isset($this->amountMoney)) {
            $json['amount_money'] = $this->amountMoney;
        }
        if (isset($this->reason)) {
            $json['reason'] = $this->reason;
        }
        if (isset($this->state)) {
            $json['state'] = $this->state;
        }
        if (!empty($this->dueAt)) {
            $json['due_at'] = $this->dueAt['value'];
        }
        if (isset($this->disputedPayment)) {
            $json['disputed_payment'] = $this->disputedPayment;
        }
        if (!empty($this->evidenceIds)) {
            $json['evidence_ids'] = $this->evidenceIds['value'];
        }
        if (isset($this->cardBrand)) {
            $json['card_brand'] = $this->cardBrand;
        }
        if (isset($this->createdAt)) {
            $json['created_at'] = $this->createdAt;
        }
        if (isset($this->updatedAt)) {
            $json['updated_at'] = $this->updatedAt;
        }
        if (!empty($this->brandDisputeId)) {
            $json['brand_dispute_id'] = $this->brandDisputeId['value'];
        }
        if (!empty($this->reportedDate)) {
            $json['reported_date'] = $this->reportedDate['value'];
        }
        if (!empty($this->reportedAt)) {
            $json['reported_at'] = $this->reportedAt['value'];
        }
        if (isset($this->version)) {
            $json['version'] = $this->version;
        }
        if (!empty($this->locationId)) {
            $json['location_id'] = $this->locationId['value'];
        }
        $json = \array_filter($json, function ($val) {
            return $val !== null;
        });
        return !$asArrayWhenEmpty && empty($json) ? new stdClass() : $json;
    }
}
