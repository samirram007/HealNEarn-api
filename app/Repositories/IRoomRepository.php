<?php

namespace App\Repositories;

use App\Models\Room;

interface IRoomRepository
{
    /**
     * Retrieve a room by its ID.
     */
    public function findById(int $id): ?Room;

    /**
     * Create a new room.
     */
    public function create(array $data): Room;

    /**
     * Update an existing room.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a room by its ID.
     */
    public function delete(int $id): bool;
}
