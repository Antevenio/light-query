<?php

namespace LightQuery;

use PDO;

class LightQuery
{
    protected $pdo;
    protected $maxRowsToCompute;

    public function __construct( \PDO $pdo, $maxRowsToCompute )
    {
        $this->pdo = $pdo;
        $this->maxRowsToCompute = $maxRowsToCompute;
    }

    public function query($query) {
        $rowsToCompute = $this->getQueryWeight($query);
        if ($rowsToCompute > $this->maxRowsToCompute) {
            throw new HeavyQueryException();
        } else {
            return ($this->pdo->query($query, \PDO::FETCH_ASSOC));
        }
    }

    public function getQueryWeight($query)
    {
        if(!$this->isExplainable($query)) {
            throw new InvalidQueryException();
        }
        $explain = "EXPLAIN " . $query;
        $statement = $this->pdo->query( $explain );
        $rows = $statement->fetchAll();

        $total = 1;
        foreach( $rows as $row )
        {
            $total *= $row['rows'];
        }
        return ($total);
    }

    protected function isExplainable($query)
    {
        return (preg_match('/^\s*SELECT\s+/i',$query));
    }
}