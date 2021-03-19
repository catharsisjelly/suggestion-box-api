<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\DBAL\Connection;

class DatabaseContext implements Context
{
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     * @return void
     * @throws \Doctrine\DBAL\DBALException
     */
    public function bootstrap(BeforeScenarioScope $scope)
    {
        $this->truncateTables();
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\DBALException
     */
    public function truncateTables()
    {
        $tablesResult = $this->connection->fetchAll(
            "SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = :tableSchema
            AND table_name != 'migration_versions';",
            ['tableSchema' => $this->connection->getDatabase()]
        );
        $this->connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tablesResult as $row) {
            $this->connection->executeQuery(
                sprintf('TRUNCATE TABLE %s;', $row['table_name'])
            );
        }
        $this->connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
