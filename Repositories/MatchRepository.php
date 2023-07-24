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
     * @param string $id
     * @return Match|null
     */
    public function find(string $id)
    {
        // TODO implement logic for finding match
        // TODO must return null if not found
    }

    /**
     * @param Match $match
     * @return Match
     */
    public function saveMatch(Match $match)
    {
        // logic for storing games
    }

    /**
     * @param Match $match
     * @return Match
     */
    public function updateMatch(Match $match)
    {
        // logic for updating match in storage
    }

    /**
     * @param array $array
     * @return array
     */
    public function findBy(array $array)
    {
        // TODO implement logic for query builder
        // if no matches found return empty array
    }
}