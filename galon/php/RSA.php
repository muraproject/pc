<?php

class RSA {
    private $p, $q, $n, $phi, $e, $d;

    public function __construct($p = 17, $q = 11, $e = 7) {
        $this->p = $p;
        $this->q = $q;
        $this->n = $p * $q;
        $this->phi = ($p - 1) * ($q - 1);
        $this->e = $e;
        $this->d = $this->modInverse($this->e, $this->phi);
    }

    private function modInverse($a, $m) {
        for ($x = 1; $x < $m; $x++) {
            if (($a * $x) % $m == 1) {
                return $x;
            }
        }
        return null;
    }

    public function encrypt($plaintext) {
        $ciphertext = [];
        foreach (str_split($plaintext) as $char) {
            $m = ord($char);
            $c = bcpowmod($m, $this->e, $this->n);
            $ciphertext[] = $c;
        }
        return implode(' ', $ciphertext);
    }

    public function decrypt($ciphertext) {
        $plaintext = '';
        foreach (explode(' ', $ciphertext) as $c) {
            $m = bcpowmod($c, $this->d, $this->n);
            $plaintext .= chr($m);
        }
        return $plaintext;
    }

    public function getPublicKey() {
        return [$this->e, $this->n];
    }

    public function getPrivateKey() {
        return [$this->d, $this->n];
    }
}
?>
