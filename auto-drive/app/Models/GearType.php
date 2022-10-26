<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property AutoCatalog[] $autoCatalogs
 */
class GearType extends Model
{

    protected $table = 'GearType';

    protected $keyType = 'integer';

    protected $fillable = ['name'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function autoCatalogs()
    {
        return $this->hasMany('App\Models\AutoCatalog', 'idGearType');
    }
}
