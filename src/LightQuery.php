<?php

namespace LightQuery;
use QueryWeight\QueryWeight;
use PDO;

class LightQuery
{
    /**
     * @var PDO
     */
    protected $pdo;
    /**
     * @var QueryWeight
     */
    protected $queryWeight;
    protected $maxRowsToCompute;

    public function __construct(PDO $pdo, QueryWeight $queryWeight, $maxRowsToCompute)
    {
        $this->pdo = $pdo;
        $this->queryWeight = $queryWeight;
        $this->maxRowsToCompute = $maxRowsToCompute;
    }

    /**
     * @param $query
     * @return false|\PDOStatement
     * @throws HeavyQueryException
     * @throws \QueryWeight\UnexplainableQueryException
     */
    public function query($query) {
        $rowsToCompute = $this->queryWeight->getQueryWeight($query);
        if ($rowsToCompute > $this->maxRowsToCompute) {
            throw new HeavyQueryException();
        } else {
            return ($this->pdo->query($query, \PDO::FETCH_ASSOC));
        }
    }
}
