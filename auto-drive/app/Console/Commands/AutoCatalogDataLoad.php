<?php

namespace App\Console\Commands;

use App\Contracts\AutoCatalogActionContract;
use App\Services\AutoCatalogService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AutoCatalogDataLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto-catalog:load {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected string $defaultFile = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AutoCatalogActionContract $action): int
    {
        if ($data = $action($this->argument('file') ?? $this->defaultFile)) {
            $this->info('Done...');
            $service = new AutoCatalogService();
            $service->handleUploadOffers($data);
            if (!empty($defectiveIds = $service->getDefectiveOffersId())) {
                foreach ($defectiveIds as $message) {
                    $this->error("Failed to create a position for offer with id {$message['id']}. " . (
                        $message['message'] ?? ''
                    ));
                }
            }
            $status = CommandAlias::SUCCESS;
        } else {
            $this->error('Something went wrong!');
            $status = CommandAlias::FAILURE;
        }

        return $status;
    }
}
