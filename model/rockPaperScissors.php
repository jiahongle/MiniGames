<?php

class rockPaperScissors {
	public $aiMove = "";
	public $yourMove = "";
	public $allMoves = array("rock", "paper", "scissor");
	public $score = 0;
	public $state = "";

	public function makeMove($move){
		$this->yourMove = $move;
		$i = rand(0, 2);
		$this->aiMove = $this->allMoves[$i];
		switch ($move) {
			case "rock":
				if ($this->aiMove == "rock") {
					$this->state = "DRAW!";
				}
				else if ($this->aiMove == "scissor") {
					$this->state = "YOU WIN!";
				}
				else {
					$this->state = "YOU LOSE!";
				}
				break;
			case "paper":
				if ($this->aiMove == "rock") {
					$this->state = "YOU WIN!";
				}
				else if ($this->aiMove == "scissor") {
					$this->state = "YOU LOSE!";
				}
				else {
					$this->state = "DRAW!";
				}
				break;		
	 		case "scissor":
				if ($this->aiMove == "rock") {
					$this->state = "YOU LOSE!";
				}
				else if ($this->aiMove == "scissor") {
					$this->state = "DRAW!";
				}
				else {
					$this->state = "YOU WIN!";
				}
				break;		
		}
		if ($this->state == "YOU WIN!") {
			$this->score += 1;
		}
		else if ($this->state == "YOU LOSE!") {
			$this->score -= 1;
		}
	}
	public function getYourMove() {
		return $this->yourMove;
	}
	public function getAiMove() {
		return $this->aiMove;
	}

	public function getState(){
		return $this->state;
	}

	public function getScore() {
		return $this->score;
	}
}
?>
