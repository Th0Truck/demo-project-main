<?php

namespace App\Services;

use App\Http\Resources\UserDto;
use App\Models\User;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class UserService
{
    public function __construct(
        private AccountService $accountService,
    ) {}

    /**
     * Adds a new user with the provided details and creates an account for them.
     * @param string $fullName
     * @param string $email
     * @param string $merchantId
     * @return UserDto
     * 
     * @throws Exception
     */
    public function addUser(string $fullName, string $email, string $merchantId): UserDto {
        $user = $this->createUser($fullName, $email, $merchantId);
        $this->accountService->addAccountForUser($user->userId);

        return $this->mapToDto($user);
    }

    /**
     * Creates a new user with the given details.
     * 
     * @return User
     * @throws Exception
     */
    private function createUser(string $fullName, string $email, string $merchantId): User
    {
        $user = new User([
            'userId' => Uuid::uuid4(),
            'fullName' => $fullName,
            'email' => $email,
            'merchantId' => $merchantId,
        ]);

        $user->save();

        return $user;
    }

    /**
     * Retrieves a user by their ID and maps it to a UserDto.
     * 
     * @throws Exception
     */
    public function getUser(string $userId): UserDto
    {
        $user = $this->findUserById($userId);

        return $this->mapToDto($user);
    }

    /**
     * Find the user by their ID.
     * 
     * @return User
     * @throws Exception
     */
    public function findUserById(string $userId): User {
        $user = User::query()->where('userId', $userId)->first();

        if (is_null($user)) {
            throw new Exception("User with id " . $userId . " not found");
        }

        return $user;
    }

    /**
     * Maps a User model to a UserDto.
     * 
     * @return UserDto
     */
    private function mapToDto(User $user): UserDto
    {
        return new UserDto(
            $user->userId,
            $user->fullName,
            $user->email
        );
    }
}
