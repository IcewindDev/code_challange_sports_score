<?php

namespace Repositories;

use Models\Team;

class TeamRepository
{
    public function __construct()
    {
        // any dependencies for handling storage
    }

    /**
     * @param string $id
     * @return Team|null
     */
    public function find(string $id)
    {
        // TODO implement logic for finding Team
        // TODO must return null if not found
    }

    /**
     * @param Team $Team
     * @return void
     */
    public function saveTeam(Team $team)
    {
        // logic for storing games
    }

    /**
     * @param Team $Team
     * @return void
     */
    public function updateTeam(Team $team)
    {
        // logic for updating Team in storage
    }
}