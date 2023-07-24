<?php

namespace Models;

class Match
{
    public const STATUS_ONGOING  = 'ongoing';
    public const STATUS_FINISHED = 'finished';
    private string $id;
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
     * @var string
     */
    private string $status;
    //it would be nice to move this in a status handler service which will handle all entities

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

    /**
     * @return int
     */
    public function getHomeTeamScore(): int
    {
        return $this->homeTeamScore;
    }

    /**
     * @param int $homeTeamScore
     */
    public function setHomeTeamScore(int $homeTeamScore): self
    {
        $this->homeTeamScore = $homeTeamScore;

        return $this;
    }

    /**
     * @return int
     */
    public function getAwayTeamScore(): int
    {
        return $this->awayTeamScore;
    }

    /**
     * @param int $awayTeamScore
     */
    public function setAwayTeamScore(int $awayTeamScore): self
    {
        $this->awayTeamScore = $awayTeamScore;

        return $this;
    }

    /**
     * @return $this
     */
    public function startGame()
    {
        $this->setStatus(self::STATUS_ONGOING)
             ->setHomeTeamScore(0)
             ->setAwayTeamScore(0);

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}