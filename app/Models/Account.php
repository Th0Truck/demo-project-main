<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $accountId
 * @property string $userId
 * @property float $balance
 * @method static Builder|Account query()
 */
class Account extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The primary key associated with the table.
     *
     * @var int
     */
    protected $primaryKey = 'id';

    protected $fillable = ['accountId', 'userId', 'balance'];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public $timestamps = false;

    /**
     * Increases the balance of the account by the specified amount.
     * 
     * @param float $amount
     */
    public function increaseBalance(float $amount): void
    {
        $currentBalance = $this->balance;
        $this->balance = $currentBalance + $amount;
        $this->save();
    }

    /**
     * Decreases the balance of the account by the specified amount.
     * 
     * @param float $amount
     * @throws Exception
     */
    public function decreaseBalance(float $amount): void
    {
        $currentBalance = $this->balance;
        if ($currentBalance < $amount) {
            throw new Exception("Insufficient funds");
        }
        $this->balance = $currentBalance - $amount;
        $this->save();
    }
}
