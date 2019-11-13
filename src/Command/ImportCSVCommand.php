<?php

namespace App\Command;

use App\Service\ProductDataService;
use App\Service\Utils\ProductImportHelper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportCSVCommand extends Command
{   
    /**
     * Default command name used in the terminal
     */
    protected static $defaultName = 'ImportCSV';

    /**
     * @var ProductDataService
     */
    private $productService;

    /**
     * @var ProductImportHelper
     */
    private $productImportHelper;
   
    /**
     * constructor
     * 
     */
    public function __construct(
        ProductDataService $productService,
        ProductImportHelper $productImportHelper
    )
    {
        parent::__construct();
        $this->productService = $productService;
        $this->productImportHelper = $productImportHelper;
    }

    /**
     * Indicate arguments and options available to this command
     * 
     * @param void
     */
    protected function configure()
    {
        $this
            ->setDescription('To import, type "ImportCSV <csvfile>". For test mode,  "ImportCSV --testonly csvdump <csvfile>"')
            ->addArgument(
                'csvfile', 
                InputArgument::REQUIRED, 
                'CSV dump containing inventory records.'
            )
            // To enable test mode, we need to add the 'testonly' flag
            ->addOption('testonly', null, InputOption::VALUE_OPTIONAL, 'Toggle test-only mode. Skips adding to DB.')
        ;
    }

    /**
     *  All logic will be placed here. Running the command will "execute"
     *  the code inside this function.
     * 
     * @param void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Makes CLI prettier (colors, whatnot)
        $io = new SymfonyStyle($input, $output);

        // Get the argument from the command line
        $arg_csvdump = $input->getArgument('csvfile');

        // CSV processing is delegated to a helper class
        $processed_payload = $this->productImportHelper->processCSV($arg_csvdump);

        // Displays results
        $io->note(sprintf("Import Results:"));
        $io->note(
            sprintf(
                "Detected encoding : %s", 
                $this->productImportHelper->getFileEncoding()
            )
        );

        $io->note(
            sprintf(
                "Valid rows (for import): %d", 
                $this->productImportHelper->getValidRowCount()
            )
        );

        // Imported
        $io->table(
            $this->productImportHelper->getHeaderLabels(),
            $processed_payload
        );

        // IF testonly flag is on, disable DB inserts
        if ($input->getOption('testonly')) {
            $io->note(sprintf('Running this command in test mode only.'));
        } else {
            // Perform DB inserts separately to monitor errors
            $add_success = 0;
            $add_fail = 0;

            $io->note(sprintf('Inserting into DB...'));

            // DB inserts are delegated to a service
            foreach ($processed_payload as $product){
                $id = $this->productService->addProduct($product);

                // Keeps count of DB insert errors to be displayed later
                if($id !== false){
                    $add_success++;
                } else {
                    $add_fail++;
                }
            }

            // Displays results
            $io->success(
                sprintf(
                    "TOTAL SUCCESSFUL DB OPERATIONS: %d", 
                    $add_success
                )
            );

            $io->warning(
                sprintf(
                    "TOTAL FAILED DB OPERATIONS: %d", 
                    $add_fail
                )
            );
            
        }

        // Re-display table of ignored records
        $io->note(
            sprintf(
                "TOTAL RECORDS NOT IMPORTED: %d", 
                $this->productImportHelper->getInvalidRowCount()
            )
        );
         $io->table(
            $this->productImportHelper->getHeaderLabels(),
            $this->productImportHelper->getIgnoredRows()
        );

        return 0;
    }
}
