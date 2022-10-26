<?php

namespace App\Services;

use App\Models\AutoCatalog;
use App\Models\AutoCatalogSource;
use App\Models\AutoModel;
use App\Models\EngineType;
use App\Models\GearType;
use App\Models\Mark;
use App\Models\Specification;
use App\Models\Transmission;
use http\Exception\RuntimeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AutoCatalogService
{
    protected array $defectiveOffersId = [];

    public function handleUploadOffers(array $offers): void
    {
        $notDeleteIdSources = [];
        foreach ($offers as $offer) {
            $this->updateOffer($offer);
            try {
                DB::beginTransaction();
                $errorMessage = $this->updateOffer($offer);
                if (!empty($errorMessage)) {
                    $this->defectiveOffersId[] = [
                        'id' => $offer['id'] ?? 'Id is missing',
                        'message' => $errorMessage
                    ];
                    DB::rollback();
                } else {
                    $notDeleteIdSources[] = $offer['id'];
                    DB::commit();
                }
            } catch (Throwable $e) {
                $this->defectiveOffersId[] = [
                    'id' => $offer['id'] ?? 'Id is missing',
                    'message' => $e->getMessage()
                ];
                DB::rollback();
            }
        }

        $sourceToDelete = AutoCatalogSource::whereNotIn('idSource', $notDeleteIdSources)->get();
        if (!empty($sourceToDelete)) {
            $catalogIdsToDelete = [];
            foreach ($sourceToDelete as $source) {
                $catalogIdsToDelete[] = $source->idCatalog;
                $source::where('idCatalog', $source->idCatalog)->delete();
            }
            Log::debug('$catalogIdsToDelete'.var_export($catalogIdsToDelete,true));
            AutoCatalog::destroy($catalogIdsToDelete);
        }
    }

    public function updateOffer(array $offer): false|string
    {
        $mark = Mark::query()->firstOrCreate([
            'name' => $offer['mark']
        ]);
        $model = AutoModel::query()->firstOrCreate([
            'name' => $offer['model']
        ]);
        $engineType = EngineType::query()->firstOrCreate([
            'name' => $offer['engine-type']
        ]);
        $transmission = Transmission::query()->firstOrCreate([
            'name' => $offer['transmission']
        ]);
        $gearType = GearType::query()->firstOrCreate([
            'name' => $offer['gear-type']
        ]);

        if (!isset($mark->id, $model->id, $engineType->id, $transmission->id, $gearType->id)) {
            throw new RuntimeException("Something went wrong");
        }

        $specificationData = [
            'generation' => $offer['generation'],
            'year' => (int) $offer['year'],
            'run' => (int) $offer['run'],
            'color' => $offer['color'],
            'bodyType' => $offer['body-type'],
        ];

        $isNew = false;
        if (
            $source = AutoCatalogSource::query()
                ->with(['autoCatalog'])
                ->where('idSource', $offer['id'])
                ->first()
        ) {
            Specification::query()
                ->where(['id' => $source->autoCatalog->idSpecification])
                ->update($specificationData);
            $autoCatalog = $source->autoCatalog;
        } else {
            try {
                $specification = Specification::create($specificationData);
            } catch (Throwable $e) {
                return $e->getMessage();
            }
            $autoCatalog = new AutoCatalog;
            $autoCatalog->idSpecification = $specification->id;
            $isNew = true;
        }
        $autoCatalog->idMark = $mark->id;
        $autoCatalog->idModel = $model->id;
        $autoCatalog->idEngineType = $engineType->id;
        $autoCatalog->idTransmission = $transmission->id;
        $autoCatalog->idGearType = $gearType->id;
        $autoCatalog->idGeneration = $offer['generation_id'];
        $autoCatalog->save();

        if ($isNew) {
            $this->createRelationToSource($offer['id'], $autoCatalog->id);
        }

        return false;
    }

    public function getDefectiveOffersId (): array
    {
        return $this->defectiveOffersId;
    }

    protected function createRelationToSource(int $idSource, int $idOffer): void
    {
        $autoCatalogSource = new AutoCatalogSource;
        $autoCatalogSource->idSource = $idSource;
        $autoCatalogSource->idCatalog = $idOffer;
        $autoCatalogSource->save();
    }
}
