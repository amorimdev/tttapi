<?php

namespace TicTacToe\Component\Move;

use TicTacToe\Dto\GameResultDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TicTacToe\Service\Move\Maker as MakerService;
use TicTacToe\Service\Board\WinnerVerifier;

/**
 * Class Maker
 * @package TicTacToe\Component\Move
 */
class Maker
{

    /**
     * @var MakerService
     */
    private $maker;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var WinnerVerifier
     */
    private $winnerVerifier;

    /**
     * Maker constructor.
     * @param MakerService $maker
     * @param WinnerVerifier $winnerVerifier
     * @param Validator $validator
     */
    public function __construct(MakerService $maker, WinnerVerifier $winnerVerifier, Validator $validator)
    {
        $this->maker = $maker;
        $this->winnerVerifier = $winnerVerifier;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function makeMoveByRequest(Request $request)
    {
        try {
            $requestData = $this->validator
                ->validateMoveByRequest($request);

            $boardState = (array) $requestData->boardState;
            $playerUnit = $requestData->playerUnit;

            $winnerPosition = $this->winnerVerifier
                ->verifyPosition($boardState, $playerUnit);

            $nextMove = $this->maker
                ->makeMove($boardState, $playerUnit);

            return new JsonResponse(
                $this->fabricateGameResult($winnerPosition, $nextMove, $playerUnit)
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                400
            );
        }
    }

    /**
     * @param array $winnerPosition
     * @param array $nextMove
     * @param string $playerUnit
     * @return GameResultDto
     */
    private function fabricateGameResult(array $winnerPosition, array $nextMove, $playerUnit)
    {
        $responseDto = new GameResultDto();
        $responseDto->winner = isset($winnerPosition[3]) ? $winnerPosition[3] : '';
        $responseDto->playerWins = $responseDto->winner == $playerUnit;
        $responseDto->botWins = $responseDto->winner && $responseDto->winner != $playerUnit;
        $responseDto->tiedGame = !count($nextMove) && !$responseDto->winner;
        $responseDto->winnerPositions = array_slice($winnerPosition, 0, 3);
        $responseDto->nextMove = $nextMove;

        return $responseDto;
    }
}
