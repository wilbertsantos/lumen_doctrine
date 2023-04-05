<?php

namespace App\Console\Commands;

use App\Importers\CustomerImporter;
use Illuminate\Console\Command;
use Doctrine\ORM\EntityManagerInterface;

class ImportCustomersCommand extends Command
{
    protected $signature = 'import:customers';

    protected $description = 'Import customers from the API';

    private $customerImporter;

public function __construct()
    {
        parent::__construct();
    }

  
    public function handle(EntityManagerInterface $eM)
    {
        $importer = new CustomerImporter($eM);

        $data = $importer->getData();

        $importer->import($data);
        
        $this->info('Customers imported successfully!');
    }

}
