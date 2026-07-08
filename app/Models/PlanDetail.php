<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;

class PlanDetail extends Model
{
    use HasFactory;

    protected $table = 'plan_details';
    protected $fillable = [
        'plan_id', 'feature'
    ];

    public function plan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
