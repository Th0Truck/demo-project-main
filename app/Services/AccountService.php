<?php

namespace App\Services;

use App\Http\Resources\AccountDto;
use App\Models\Account;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class AccountService {

    /**
     * Adds a new account for a user with an initial balance of 0.
     * @param string $userId
     * 
     * @return AccountDto
     * @throws Exception
     */
    public function addAccountForUser(string $userId): AccountDto {
        $account = $this->createAccount($userId);

        return $this->accountToAccountDto($account);
    }

    /**
     * Creates a new account for a user with an initial balance of 0.
     * 
     * @param string $userId
     * @return Account
     * @throws Exception
     */
    private function createAccount(string $userId): Account {
        $account = new Account([
            'accountId' => Uuid::uuid4(),
            'userId' => $userId,
            'balance' => 0.0,
        ]);

        $account->save();
        return $account;
    }

    /**
     * Retrieves an account for a user and maps it to an AccountDto.
     * 
     * @param string $userId
     * @return AccountDto
     * @throws Exception
     */
    public function getAccountForUser(string $userId): AccountDto {
        $account = $this->getAccountByUserId($userId);

        return $this->accountToAccountDto($account);
    }

    /**
     * Increases the balance of the account by the specified amount.
     * 
     * @param string $accountId
     * @throws Exception
     */
    public function decreaseBalance(string $accountId, float $amount): AccountDto {
        $account = $this->getAccountById($accountId);
        $account->decreaseBalance($amount);

        return $this->accountToAccountDto($account);
    }

    /**
     * Increases the balance of the account by the specified amount.
     * @param string $accountId
     * 
     * @param float $amount
     * @throws Exception
     */
    public function increaseBalance(string $accountId, float $amount): AccountDto {
        $account = $this->getAccountById($accountId);
        $account->increaseBalance($amount);

        return $this->accountToAccountDto($account);
    }

    /**
     * Maps an Account model to an AccountDto.
     * 
     * @param Account $account
     * @return AccountDto
     */
    private function accountToAccountDto(Account $account): AccountDto {
        return new AccountDto($account->accountId, $account->userId, $account->balance);
    }

    /**
     * Retrieves an account by its ID.
     * @param string $accountId
     * 
     * @return Account
     * @throws Exception
     */
    private function getAccountById(string $accountId): Account {
        $account = Account::query()->where('accountId', $accountId)->first();

        if (is_null($account)) {
            throw new \Exception("Account with id " . $accountId . " not found");
        }

        return $account;
    }

    /**
     * Retrieves an account by the user ID.
     * @param string $userId
     * 
     * @return Account
     * @throws Exception
     */
    private function getAccountByUserId(string $userId): Account {
        $account = Account::query()->where('userId', $userId)->first();

        if (is_null($account)) {
            throw new Exception("Account not found for user " . $userId);
        }

        return $account;
    }
}
