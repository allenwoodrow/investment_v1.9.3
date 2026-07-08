<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlanDetail;
use InvalidArgumentException;

class Plan extends Model
{
    use HasFactory;
    protected $table = "plans";

    protected $fillable = [
        'name','description', 'investment_term','min_amount', 'active', 'return_percent'
    ];
    protected $appends = [
        'features'
    ];

    protected $casts = [
        'investment_term' => 'string',
    ];

    public function details() {
        return $this->hasMany(PlanDetail::class, 'plan_id');
    }

    public function getFeaturesAttribute() {
        return $this->details->toArray();
    }

    public function getInvestmentTermAttribute($value) {
        try {
            return $this->parseToDays((string) $value);
        } catch (InvalidArgumentException $e) {
            return $value;
        }
    }

    public function investmentTermInDays(): float
    {
        return $this->parseToDays((string) $this->getRawOriginal('investment_term'));
    }
    
    private function parseToDays(string $timeString): float
    {
        $timeString = strtolower(trim($timeString));

        if (is_numeric($timeString)) {
            return (float) $timeString;
        }

        $unitToDays = [
            'd' => 1,
            'day' => 1,
            'days' => 1,
            'h' => 1 / 24,
            'hr' => 1 / 24,
            'hrs' => 1 / 24,
            'hour' => 1 / 24,
            'hours' => 1 / 24,
            'm' => 1 / (24 * 60),
            'min' => 1 / (24 * 60),
            'mins' => 1 / (24 * 60),
            'minute' => 1 / (24 * 60),
            'minutes' => 1 / (24 * 60),
            'wk' => 7,
            'wks' => 7,
            'week' => 7,
            'weeks' => 7,
            'mo' => 30,
            'mos' => 30,
            'month' => 30,
            'months' => 30,
            'y' => 365,
            'yr' => 365,
            'yrs' => 365,
            'year' => 365,
            'years' => 365
        ];

        if (!preg_match('/^(\d+(?:\.\d+)?)\s*([a-z]+)$/', $timeString, $matches)) {
            throw new InvalidArgumentException("Invalid time string format.");
        }

        $value = (float) $matches[1];
        $unit = $matches[2];

        if (!isset($unitToDays[$unit])) {
            throw new InvalidArgumentException("Invalid time unit.");
        }

        return $value * $unitToDays[$unit];
    }

}
