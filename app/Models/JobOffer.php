<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'occupation_id',
        'due_date',
        'description',
    ];

    public function scopeOpenData(Builder $query)
    {
        $query->where('status', true)
            ->where('due_date', '>=', now());

        return $query;
    }

    public function scopeSearch(Builder $query, $params)
    {
        if (!empty($params['occupation'])) {
            $query->where('occupation_id', $params['occupation']);
        }

        return $query;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function jobOfferViews()
    {
        return $this->hasMany(JobOfferView::class);
    }
}
