<?php

namespace Repositories;

use Models\Match;

class MatchRepository
{
    public function __construct()
    {
        // any dependencies for handling storage
    }

    /**
     * @param Match $match
     * @return void
     */
    public function saveMatch(Match $match){
        // logic for storing games
    }

    /**
     * @param Match $match
     * @return void
     */
    public function updateMatch(Match $match){
        // logic for updating match in storage
    }
}