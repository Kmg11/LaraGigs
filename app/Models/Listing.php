<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    // * You can use this to mass assign fields with $fillable or Model::unguard() in AppServiceProvider.php
    // protected $fillable = [
    //     'title',
    //     'location',
    //     'website',
    //     'company',
    //     'email',
    //     'tags',
    //     'description',
    // ];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['tag'] ?? false) {
            $query->where('tags', 'like', '%' . $filters['tag'] . '%');
        }

        if ($filters['search'] ?? false) {
            $search = '%' . $filters['search'] . '%';

            $query
                ->where('title', 'like', $search)
                ->orWhere('description', 'like', $search)
                ->orWhere('company', 'like', $search)
                ->orWhere('location', 'like', $search)
                ->orWhere('tags', 'like', $search);
        }
    }

    // * Relationship to User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
