<?php

declare(strict_types=1);

namespace Lightit\Doctors\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Lightit\Clinics\Domain\Models\Clinic;

class Doctor extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsToMany<Clinic, $this, Pivot>
     */
    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class)->withTimestamps();
    }
}
