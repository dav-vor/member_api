<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'members';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static $rules = [
        'email' => 'unique:members'
    ];

    public function memberTags(): BelongsToMany
    {
        return $this->belongsToMany(MemberTag::class);
    }

    /**
     * @param  \DateTime  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}
