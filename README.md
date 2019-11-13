
# CSV Importer for Wren

#### Machines specs:

Ubuntu 18.04
- Running under Windows Subsystem for Linux (WSL) on Windows 10

PHP 7.2.24-0ubuntu0.18.04.1 (cli)

Symfony 4.3

MySQL 5.7

Composer (1.9.1)

#### To install locally:

1. Setup your LAMP stack.
2. Once running, clone this repository.
3. Install using Composer
4. Setup your DB connection in the .env file (follow the general steps)

#### Setting up your test MySQL table:

1. Given you already have superuser access to your DB, simply run the SQL dump to create the DB and table.

#### To run the console command:

1. Go to the directory where you cloned the repository
2. Go inside the data/ folder.
3. Type './bin/console ImportCSV --help' to display the configuration screen.
4. To run the script, './bin/console ImportCSV stock.csv'. It should display the results of the import and DB operations.
5. To test run, './bin/console ImportCSV --test-only csvfile stock.csv'. It normally runs except it doesn't add the records to the DB.

#### DISCLAIMER:

1. This project is a work in progress (WIP)
2. More features may be added from time to time.

For questions, please send me a message: miko.suria@gmail.com 
