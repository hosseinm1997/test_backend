<?php

namespace Infrastructure\Interfaces;

interface TicketRepositoryInterface
{
    public function index();

    public function show(int $ticketId);

    public function store(array $data);
}
