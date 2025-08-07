<?php

namespace App\Services;

use App\Http\Resources\MerchantDto;
use App\Models\Merchant;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class MerchantService
{

    /**
     * Constructs a new MerchantService instance.
     * @param UserService $userService
     * @param AccountService $accountService
     * 
     * @return MerchantDto
     */
    public function addMerchant(string $name): MerchantDto {
        $merchant = $this->createMerchant($name);

        return $this->merchantToMerchantDto($merchant);
    }

    /**
     * Creates a new merchant with the given name.
     * 
     * @return Merchant
     * @throws Exception
     */
    private function createMerchant(string $name): Merchant {
        $merchant = new Merchant([
            'merchantId' => Uuid::uuid4(),
            'name' => $name,
        ]);

        $merchant->save();

        return $merchant;
    }

    /**
     * Retrieves a merchant by their ID and maps it to a MerchantDto.
     * 
     * @throws Exception
     */
    public function getMerchant(string $merchantId): MerchantDto {
        $merchant = Merchant::query()->where('merchantId', $merchantId)->first();

        if (is_null($merchant)) {
            throw new Exception("Merchant with id " . $merchantId . " not found");
        }

        return $this->merchantToMerchantDto($merchant);
    }

    /**
     * Maps a Merchant model to a MerchantDto.
     * 
     * @return MerchantDto
     */
    private function merchantToMerchantDto(Merchant $merchant): MerchantDto {
        return new MerchantDto($merchant->merchantId, $merchant->name);
    }
}

