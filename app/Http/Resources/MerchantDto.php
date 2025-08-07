<?php

namespace App\Http\Resources;

class MerchantDto
{
    private ?string $merchantId;

    private ?string $name;

    public function __construct(
        ?string $merchantId = null,
        ?string $name = null
    ) {
        $this->merchantId = $merchantId;
        $this->name = $name;
    }

    /**
     * Converts the MerchantDto to a response array.
     * 
     * @return array
     */
    public function toResponse(): array {
        return [
            'merchant_id' => $this->merchantId,
            'name' => $this->name,
        ];
    }

    /**
     * Getters and Setters
     */
    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    public function setMerchantId(?string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }


}
