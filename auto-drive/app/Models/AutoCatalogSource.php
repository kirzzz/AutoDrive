<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 *
 * @property integer $idCatalog
 * @property integer $idSource
 * @property string $created_at
 * @property string $updated_at
 * @property AutoCatalog $autoCatalog
 */
class AutoCatalogSource extends Model
{
    protected $table = 'AutoCatalogSource';

    protected $fillable = ['idCatalog', 'idSource'];

    public function autoCatalog(): BelongsTo
    {
        return $this->belongsTo('App\Models\AutoCatalog', 'idCatalog');
    }
}
