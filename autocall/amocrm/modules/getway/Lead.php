<?php


namespace Autocall\Amocrm;

require_once __DIR__ . '/../../business_rules/getway/iLead.php';

class Lead implements iLead
{

    public function __construct(
        private int $idContact,
        private string $leadsName,
        private int $IdPipeline,
        private int $IdResponsible,
        private int $create_time,
    )
    {
    }

    function getIdContact(): int
    {
        return $this->idContact;
    }

    function getName(): string
    {
        return $this->leadsName;
    }

    function getIdPipeline(): int
    {
        return $this->IdPipeline;
    }

    function getIdResponsible(): int
    {
        return $this->IdResponsible;
    }

    function create_time(): int
    {
        return $this->create_time;
    }
}