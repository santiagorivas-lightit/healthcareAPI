<?php

declare(strict_types=1);

namespace Lightit\Clinics\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Lightit\Doctors\Domain\Models\Doctor;

class Clinic extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsToMany<Doctor, $this, Pivot>
     */
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class);
    }
}
