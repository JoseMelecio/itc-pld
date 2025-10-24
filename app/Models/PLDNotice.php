<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $route_param
 * @property string $name
 * @property string $spanish_name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereRouteParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereSpanishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PLDNotice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PLDNotice extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'pld_notice';

    protected $fillable = [
        'route_param',
        'name',
        'spanish_name',
        'template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class, 'pld_notice_custom_fields');
    }
}
