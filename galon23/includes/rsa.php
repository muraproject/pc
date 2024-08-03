<?php
require_once 'config.php';

class RSA {
    private $p, $q, $n, $phi, $e, $d;

    public function __construct($p = RSA_P, $q = RSA_Q, $e = RSA_E) {
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
        return $ciphertext;
    }

    public function decrypt($ciphertext) {
        $plaintext = '';
        foreach ($ciphertext as $c) {
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