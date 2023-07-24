<?php

namespace Services;

use Helpers\Constants;
use Repositories\MatchRepository;
use Models\Match;
use Models\Team;

class MatchHandler
{
    public const ERROR_PLAY_ITSELF        = "A team cannot play itself";
    public const ERROR_TEAM_NOT_AVAILABLE = "One of the teams is not available for a match";
    public const ERROR_SCORE_NEGATIVE     = "Score cannot be negative";
    public const ERROR_WRONG_SCORE        = "New score cannot be lower than previous one";
    public const ERROR_MATCH_FINISHED     = "Match ended and cannot be updated";
    public const ERROR_MATCH_NOT_FOUND    = "Match not found";
    /**
     * @var MatchRepository
     */
    private MatchRepository $matchRepo;

    public function __construct(MatchRepository $matchRepo)
    {
        $this->matchRepo = $matchRepo;
    }

    /**
     * @param Team $homeTeam
     * @param Team $awayTeam
     * @return array
     */
    public function startGame(Team $homeTeam, Team $awayTeam)
    {
        try {
            $this->validateMatch($homeTeam, $awayTeam);

            $match = new Match($homeTeam, $awayTeam);
            $match->setAwayTeamScore(0)
                  ->setHomeTeamScore(0);

            $this->matchRepo->saveMatch($match);

            return [
                Constants::RESULT => true,
                Constants::MATCH  => $match,
            ];
        } catch (\Exception $exception) {
            // TODO log exception
            // TODO create service for logging errors
            return [
                Constants::RESULT  => false,
                Constants::MESSAGE => $exception->getMessage(),
            ]
        }
    }

    /**
     * @param string $matchId
     * @param int    $homeNewScore
     * @param int    $awayNewScore
     * @return array
     */
    public function updateScore(string $matchId, int $homeNewScore, int $awayNewScore)
    {
        try {
            $match = $this->matchRepo->find($matchId);

            $this->validateScoreUpdate($match, $homeNewScore, $awayNewScore);

            $match->setHomeTeamScore($homeNewScore)
                  ->setAwayTeamScore($awayNewScore);

            $this->matchRepo->updateMatch($match);

            return [
                Constants::RESULT => true,
                Constants::MATCH  => $match,
            ];
        } catch (\Exception $exception) {
            // TODO log exception
            // TODO create service for logging errors
            return [
                Constants::RESULT  => true,
                Constants::MESSAGE => $exception->getMessage(),
            ];
        }
    }

    /**
     * @param Team $homeTeam
     * @param Team $awayTeam
     * @return void
     * @throws \Exception
     */
    private function validateMatch(Team $homeTeam, Team $awayTeam)
    {
        // could be included here validation for finding match as well
        if ($homeTeam->getId() === $awayTeam->getId()) {
            throw new \Exception(self::ERROR_PLAY_ITSELF);
        }

        if ($homeTeam->getStatus() === Team::STATUS_PLAYING || $awayTeam->getStatus() === Team::STATUS_PLAYING) {
            throw new \Exception(self::ERROR_TEAM_NOT_AVAILABLE);
        }
    }

    /**
     * @param Match|null $match
     * @param int        $newHomeScore
     * @param int        $newAwayScore
     * @return void
     * @throws \Exception
     */
    private function validateScoreUpdate(?Match $match, int $newHomeScore, int $newAwayScore)
    {
        if (null === $match) {
            throw new \Exception(self::ERROR_MATCH_NOT_FOUND);
        }

        if (Match::STATUS_FINISHED === $match->getStatus()) {
            throw new \Exception(self::ERROR_MATCH_FINISHED);
        }

        $this->validateScore($match->getHomeTeamScore(), $newHomeScore);
        $this->validateScore($match->getAwayTeamScore(), $newAwayScore);
    }

    /**
     * @param int $oldScore
     * @param int $newScore
     * @return void
     * @throws \Exception
     */
    private function validateScore(int $oldScore, int $newScore)
    {
        if ($newScore < 0) {
            throw new \Exception(self::ERROR_SCORE_NEGATIVE);
        }
        //validation for score => you should not be able to "unscore"
        // not sure about the football rules if you can deny a goal after a while and not right away
        // then validating score does not make any sense
        if ($newScore < $oldScore) {
            throw new \Exception(self::ERROR_WRONG_SCORE);
        }
    }
}