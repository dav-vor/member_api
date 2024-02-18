<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MemberTag extends Model
{
    use HasFactory;

    protected $table = 'member_tags';
    protected $fillable = [
        'name'
    ];

    public function member(): BelongsToMany
    {
        return $this->belongsToMany(Member::class);
    }
}
