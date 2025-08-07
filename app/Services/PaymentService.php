<?php

namespace App\Services;

use App\Http\Resources\MerchantDto;
use App\Http\Resources\PaymentDto;
use App\Http\Resources\UserDto;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

readonly class PaymentService
{
    public function __construct(
        private UserService $userService,
        private MerchantService $merchantService,
        private AccountService $accountService,
    ) {}

    /**
     * Adds a new payment for a user to a merchant.
     * @param PaymentDto $paymentDto
     * 
     * @return PaymentDto
     * @throws Exception
     */
    public function addPayment(PaymentDto $paymentDto): PaymentDto {
        $user = $this->userService->getUser($paymentDto->getUserId());
        $merchant = $this->merchantService->getMerchant($paymentDto->getMerchantId());
        $account = $this->accountService->getAccountForUser($paymentDto->getUserId());

        if ($account->getBalance() < $paymentDto->getAmount()) {
            throw new Exception("insufficient funds");
        }

        $this->accountService->decreaseBalance($account->getAccountId(), $paymentDto->getAmount());
        $payment = $this->toPayment($paymentDto, $user, $merchant);
        $payment->save();

        return $this->paymentToPaymentDto($payment);
    }

    /**
     * Converts a Payment model to a PaymentDto.
     * @param Payment $payment
     * 
     * @return PaymentDto
     * @throws Exception
     */
    private function paymentToPaymentDto(Payment $payment): PaymentDto {
        return new PaymentDto(
            $payment->paymentId,
            $payment->userId,
            $payment->merchantId,
            $payment->amount,
        );
    }

    /**
     * Converts a PaymentDto to a Payment model.
     * @param PaymentDto $paymentDto
     * @param UserDto $userDto
     * @param MerchantDto $merchantDto
     * 
     * @return Payment
     */
    private function toPayment(
        PaymentDto $paymentDto,
        UserDto $userDto,
        MerchantDto $merchantDto
    ): Payment {
        return new Payment([
            'paymentId' => Uuid::uuid4(),
            'userId' => $userDto->getUserId(),
            'merchantId' => $merchantDto->getMerchantId(),
            'amount' => $paymentDto->getAmount(),
        ]);
    }
}
