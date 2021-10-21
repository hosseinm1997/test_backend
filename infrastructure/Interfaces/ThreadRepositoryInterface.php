<?php

namespace Infrastructure\Interfaces;

interface ThreadRepositoryInterface
{
    public function store($ticketId, array $data);
}
