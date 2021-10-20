<?php

namespace Infrastructure\Interfaces;

interface ThreadRepositoryInterface
{
    public function store($ticketID, array $data);
}
