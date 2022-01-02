<?php

class frogs {
	public $spots = array(1, 1, 1, 0, -1, -1, -1);
    public $start_time = 0; 
    public $finish_time = 0;
    public $elapsed_sec;

    public function isEmpty($i) {
        if ($i < 0 || $i > 6) {
            return false;
        }
        if ($this->spots[$i] == 0) {
            return true;
        }
    }

    public function move($i, $j) {
        if ($this->isEmpty($j)) {
            $this->spots[$j] = $this->spots[$i];
            $this->spots[$i] = 0;
            return true;
        }
        else {
            return false;
        }
    }

    public function clickSquare($i){
        if ($this->spots == array(1, 1, 1, 0, -1, -1, -1)) {
            $this->start_time = microtime(true);
        }
        if ($this->move($i, $i+$this->spots[$i])) return;
        $this->move($i, $i+2*$this->spots[$i]);
	}

	public function getSpots(){
		return $this->spots;
	}

	public function checkWin(){
        if ($this->spots == array(-1, -1, -1, 0, 1, 1, 1)) {
            $this->finish_time = microtime(true);
            return true;
        }
        return false;
	}
    
    /* Get elapsed time from user making first move until winning */
    public function getTime() {
        if (empty($this->elapsed_sec)) {
            $this->elapsed_sec = $this->finish_time - $this->start_time;
        }
        return intval($this->elapsed_sec);
    }
}
?>
