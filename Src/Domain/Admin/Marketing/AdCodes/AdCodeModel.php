<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

class AdCodeModel
{
    private $databaseTableModel; // TODO not sure we need this
    private $startDt;
    private $endDt;
    private $description;
    private $results;
    private $id;

    public function __construct(
        int $id,
        string $startDt,
        string $endDt = null,
        string $description,
        string $results)
    {
        $this->databaseTableModel = new AdCodesModel();
        $this->id = $id;
        $this->startDt = $startDt;
        $this->endDt = $endDt;
        $this->description = $description;
        $this->results = $results;
    }

    public function getDatabaseTableModel()
    {
        return $this->databaseTableModel;
    }

    public function getColumns()
    {
        return $this->databaseTableModel->getColumns();
    }

    public function getStartDt()
    {
        return $this->startDt;
    }

    public function getEndDt()
    {
        return $this->endDt;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getId()
    {
        return $this->id;
    }
}
