<?php

namespace App\Models;


use App\Events\SpecificationSaving;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @property integer $id
 * @property string $generation
 * @property integer $year
 * @property integer $run
 * @property string $color
 * @property string $bodyType
 * @property string $created_at
 * @property string $updated_at
 * @property AutoCatalog[] $autoCatalogs
 */
class Specification extends Model
{

    protected $table = 'Specification';

    protected $keyType = 'integer';

    protected $fillable = ['generation', 'year', 'run', 'color', 'bodyType'];

    public array $validationRules = [
        'generation' => 'required|max:255',
        'year' => 'required|max:11',
        'run' => 'required|max:11',
        'color' => 'required|max:255',
        'bodyType' => 'required|max:255',
    ];


    protected $dispatchesEvents = [
        'saving' => SpecificationSaving::class,
    ];

    /**
     * @throws ValidationException
     */
    public function validate() {
        Validator::make($this->toArray(), $this->validationRules)->validate();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function autoCatalogs()
    {
        return $this->hasMany('App\Models\AutoCatalog', 'idSpecification');
    }
}
