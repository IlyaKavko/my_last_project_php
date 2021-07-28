<?php


namespace Storage;

require_once __DIR__ . '/TechnologicalCardSchema.php';
require_once __DIR__ . '/../../business_rules/technological_cards_title/iTechnologicalCardTitle.php';

class TechnologicalCard extends TechnologicalCardSchema implements iTechnologicalCardTitle
{
    public function __construct(private IStoreApp $app)
    {
        parent::__construct($this->app->getPragmaAccountId());
    }
    function setTechnologicalCard(string $title): array
    {
        return $this->setTechnologicalCards($title);
    }
    function getTechnologicalCards(): array
    {
        return $this->getTechnologicalCard();
    }
}