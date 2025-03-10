<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Follower extends Model
{
    use HasFactory;


    public function user():HasMany
    {
        return $this->HasMany(User::class, 'follower_id');
    }


    protected $fillable = [
        'follower_id',
        'user_id',
     ];
}
