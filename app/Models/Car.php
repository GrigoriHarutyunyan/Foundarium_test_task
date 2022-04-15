<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $data)
 * @method static where(string $string, $id)
 * @method static findOrFail($id)
 */
class Car extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'brand',
        'model',
        'user_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
