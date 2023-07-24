<?php

namespace Tests;

use Helpers\Constants;
use Models\Match;
use Models\Team;
use Repositories\MatchRepository;
use Services\MatchHandler;

class MatchHandlerTest
{
    private MatchHandler    $matchHandler;
    private MatchRepository $matchRepo;

    public function __construct(MatchHandler $matchHandler, MatchRepository $matchRepo)
    {
        $this->matchHandler = $matchHandler;
        $this->matchRepo    = $matchRepo;
    }

    //TODO create test case for validation of missing teams

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
        \assertEquals(MatchHandler::ERROR_SCORE_NEGATIVE, $response[Constants::MESSAGE]);
    }

    public function testUpdateScoreFail2()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->setStatus(Team::STATUS_PLAYING);

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->setStatus(Team::STATUS_AVAILABLE);

        $match = new Match();
        $match->setId(346);

        $response = $this->matchHandler->updateScore($match, 1, 0);

        \assertEquals(false, $response[Constants::RESULT]);
        \assertEquals(MatchHandler::ERROR_MATCH_NOT_FOUND, $response[Constants::MESSAGE]);
    }

    public function testUpdateScoreFail3()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->setStatus(Team::STATUS_PLAYING);

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->setStatus(Team::STATUS_AVAILABLE);

        $match = new Match();
        $match->setStatus(Match::STATUS_FINISHED);

        $response = $this->matchHandler->updateScore($match, 1, 0);

        \assertEquals(false, $response[Constants::RESULT]);
        \assertEquals(MatchHandler::ERROR_MATCH_FINISHED, $response[Constants::MESSAGE]);
    }

    public function testUpdateScoreOk()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->startGame();

        // TODO use repo for saving changes

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->startGame();
        // TODO use repo for saving changes

        $match = new Match();
        $match->setHomeTeam(1)
              ->setAwayTeam(2)
              ->startGame();

        $this->matchRepo->saveMatch($match);

        $response = $this->matchHandler->updateScore($match, 1, 0);

        \assertEquals(true, $response[Constants::RESULT]);
        \assertInstanceOf(Match::class, $response[Constants::MATCH]);
    }

    public function testFinishGameFail1()
    {
        $response = $this->matchHandler->finishGame(123);

        \assertEquals(false, $response[Constants::RESULT]);
        \assertEquals(MatchHandler::ERROR_MATCH_NOT_FOUND, $response[Constants::MESSAGE]);
    }

    public function testFinishGameFail12()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->startGame();

        // TODO use repo for saving changes

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->startGame();
        // TODO use repo for saving changes

        $match = new Match();
        $match->setHomeTeam(1)
              ->setAwayTeam(2)
              ->startGame();
        $response = $this->matchHandler->finishGame(123);

        \assertEquals(false, $response[Constants::RESULT]);
        \assertEquals(MatchHandler::ERROR_MATCH_FINISHED, $response[Constants::MESSAGE]);
    }

    public function testFinishGameFail13()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->startGame();

        // TODO use repo for saving changes

        $match = new Match();
        $match->setHomeTeam(1)
              ->setAwayTeam(2)
              ->startGame();
        $response = $this->matchHandler->finishGame(123);

        \assertEquals(false, $response[Constants::RESULT]);
        \assertEquals(MatchHandler::ERROR_TEAM_MISSING, $response[Constants::MESSAGE]);
    }

    public function testFinishGameFail1Ok()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->startGame();

        // TODO use repo for saving changes

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->startGame();
        // TODO use repo for saving changes

        $match = new Match();
        $match->setHomeTeam(1)
              ->setAwayTeam(2)
              ->startGame();

        $match = $this->matchRepo->saveMatch($match);

        $response = $this->matchHandler->finishGame($match->getId());

        \assertEquals(true, $response[Constants::RESULT]);
        \assertInstanceOf(Match::class, $response[Constants::MATCH]);
    }

    public function testGetMatchesSummaryNoResults()
    {
        $response = $this->matchHandler->getMatchesSummary();

        // there should be no matches in the system
        \assertEquals(true, $response[Constants::RESULT]);
        \assertEmpty($response[Constants::SUMMARY]);
    }

    public function testGetMatchesSummaryResult()
    {
        $homeTeam = new Team();
        $homeTeam->setId(1)
                 ->setName('Spain')
                 ->startGame();

        // TODO use repo for saving changes

        $awayTeam = new Team();
        $awayTeam->setId(2)
                 ->setName('Brazil')
                 ->startGame();
        // TODO use repo for saving changes

        $match = new Match();
        $match->setHomeTeam(1)
              ->setAwayTeam(2)
              ->setHomeTeamScore(10)
              ->setAwayTeamScore(2)
              ->startGame();

        $match = $this->matchRepo->saveMatch($match);

        $response = $this->matchHandler->getMatchesSummary();

        // there should be no matches in the system
        \assertEquals(true, $response[Constants::RESULT]);
        \assertEquals(
            [
                [
                    "home" => [
                        "name"  => "Spain",
                        "score" => 10,
                    ],
                    "away" => [
                        "name"  => "Brazil",
                        "score" => 2,
                    ],
                ],
            ],
            $response[Constants::SUMMARY]
        );
    }

    // TODO test case for TEAM MISSING on FINISH or UPDATE (team gets deleted for whatever reason)
}