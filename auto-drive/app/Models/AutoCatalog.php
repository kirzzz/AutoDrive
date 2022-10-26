<?php

namespace App\Models;

use App\Events\AutoCatalogSaving;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


/**
 * @mixin Builder
 *
 * @property integer $id
 * @property integer $idMark
 * @property integer $idModel
 * @property integer $idSpecification
 * @property integer $idEngineType
 * @property integer $idTransmission
 * @property integer $idGearType
 * @property integer $idGeneration
 * @property string $created_at
 * @property string $updated_at
 * @property EngineType $engineType
 * @property GearType $gearType
 * @property Mark $mark
 * @property AutoModel $model
 * @property Specification $specification
 * @property Transmission $transmission
 */
class AutoCatalog extends Model
{

    protected $table = 'AutoCatalog';

    protected $keyType = 'integer';

    protected $fillable = [
        'idMark',
        'idModel',
        'idSpecification',
        'idEngineType',
        'idTransmission',
        'idGearType',
        'idGeneration',
    ];

    public array $validationRules = [
        'idModel' => 'required',
        'idSpecification' => 'required',
        'idEngineType' => 'required',
        'idTransmission' => 'required',
        'idGearType' => 'required',
        'idGeneration' => 'required',
    ];

    protected $dispatchesEvents = [
        'saving' => AutoCatalogSaving::class,
    ];

    /**
     * @throws ValidationException
     */
    public function validate() {
        Validator::make($this->toArray(), $this->validationRules)->validate();
    }

    public function engineType(): BelongsTo
    {
        return $this->belongsTo('App\Models\EngineType', 'idEngineType');
    }

    public function gearType(): BelongsTo
    {
        return $this->belongsTo('App\Models\GearType', 'idGearType');
    }

    public function mark(): BelongsTo
    {
        return $this->belongsTo('App\Models\Mark', 'idMark');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo('App\Models\AutoModel', 'idModel');
    }

    public function specification(): BelongsTo
    {
        return $this->belongsTo('App\Models\Specification', 'idSpecification');
    }

    public function transmission(): BelongsTo
    {
        return $this->belongsTo('App\Models\Transmission', 'idTransmission');
    }
}
