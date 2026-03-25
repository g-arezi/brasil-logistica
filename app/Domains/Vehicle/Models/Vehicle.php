<?php

declare(strict_types=1);

namespace App\Domains\Vehicle\Models;

use App\Domains\User\Models\User;
use App\Domains\Vehicle\Enums\VehicleType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $company_id
 * @property VehicleType $type
 * @property string $plate
 * @property int $capacity_kg
 */
class Vehicle extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'type',
        'plate',
        'capacity_kg',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => VehicleType::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
