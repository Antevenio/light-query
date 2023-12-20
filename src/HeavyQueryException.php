<?php
namespace LightQuery;

class HeavyQueryException extends \Exception
{
    private $explainPlan;

    /**
     * @return mixed
     */
    public function getExplainPlan()
    {
        return $this->explainPlan;
    }

    /**
     * @param mixed $explainPlan
     * @return HeavyQueryException
     */
    public function setExplainPlan($explainPlan)
    {
        $this->explainPlan = $explainPlan;

        return $this;
    }
}
