<?php

namespace Tests;

use Helpers\Constants;
use Models\Match;
use Models\Team;
use Services\MatchHandler;

class MatchHandlerTest
{
    private MatchHandler $matchHandler;

    public function __construct(MatchHandler $matchHandler)
    {
        $this->matchHandler = $matchHandler;
    }

    public function testStartGameFail1()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1);

        $awayTeam = new Team();
        $awayTeam->setId(1);

        $response = $this->matchHandler->startGame($homeTeam, $awayTeam);

        \assertEquals(false, $response['result']);
        \assertEquals(MatchHandler::ERROR_PLAY_ITSELF, $response['message']);
    }

    public function testStartGameFail2()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->setStatus(Team::STATUS_PLAYING);

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->setStatus(Team::STATUS_AVAILABLE);

        $response = $this->matchHandler->startGame($homeTeam, $awayTeam);

        \assertEquals(false, $response['result']);
        \assertEquals(MatchHandler::ERROR_TEAM_NOT_AVAILABLE, $response[Constants::MESSAGE]);
    }

    public function testStartGameOk()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->setStatus(Team::STATUS_AVAILABLE
                     ->setStatus(Team::STATUS_AVAILABLE);

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->setStatus(Team::STATUS_AVAILABLE);

        $response = $this->matchHandler->startGame($homeTeam, $awayTeam);

        \assertEquals(true, $response[Constants::RESULT]);
        \assertInstanceOf(Match::class, $response[Constants::MATCH]);
    }

    public function testUpdateScoreFail1()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->setStatus(Team::STATUS_PLAYING);

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->setStatus(Team::STATUS_AVAILABLE);

        $match = new Match();
        $match->setHomeTeam(1);
        $match->setAwayTeam(2);
        $match->startGame();

        $response = $this->matchHandler->updateScore($match, 1, -1);

        \assertEquals(false, $response[Constants::RESULT]);
        \assertEquals(MatchHandler::ERROR_SCORE_NEGATIVE,$response['message']);
    }
}