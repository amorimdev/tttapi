<?php

namespace TicTacToe\Dto;

/**
 * Class GameResultDto
 * @package TicTacToe\Dto
 */
class GameResultDto
{
    /**
     * @var string
     */
    public $winner;

    /**
     * @var boolean
     */
    public $playerWins;

    /**
     * @var boolean
     */
    public $botWins;

    /**
     * @var boolean
     */
    public $tiedGame;

    /**
     * @var array
     */
    public $winnerPositions;

    /**
     * @var array
     */
    public $nextMove;
}
