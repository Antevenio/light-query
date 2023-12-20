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
    public function query($query)
    {
        $explainPlan = $this->queryWeight->getExecutionPlan($query);
        if ($explainPlan->getComputableRows() > $this->maxRowsToCompute) {
            throw (new HeavyQueryException())->setExplainPlan($explainPlan);
        } else {
            return ($this->pdo->query($query, \PDO::FETCH_ASSOC));
        }
    }
}
