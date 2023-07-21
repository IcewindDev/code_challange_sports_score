<?php

namespace Models;

class Match
{
    /**
     * @var int
     */
    private int $homeTeam;

    /**
     * @var int
     */
    private int $awayTeam;
    // id should be references to the Team objects in storage
    /**
     * @var int
     */
    private int $homeTeamScore = 0;

    /**
     * @var int
     */
    private int $awayTeamScore = 0;

    /**
     * @return int
     */
    public function getHomeTeam(): int
    {
        return $this->homeTeam;
    }

    /**
     * @param int $homeTeam
     */
    public function setHomeTeam(int $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    /**
     * @return int
     */
    public function getAwayTeam(): int
    {
        return $this->awayTeam;
    }

    /**
     * @param int $awayTeam
     */
    public function setAwayTeam(int $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }
}