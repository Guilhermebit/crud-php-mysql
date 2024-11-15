<?php

namespace App\Utils;

class ValidationUtil {

    public static function formataCpf($cpf) {
         return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
    }

    // Valida o formato do CPF
    public static function validaCpf(&$cpf) {

         // Remove caracteres não numéricos
         $cpf = preg_replace('/\D/', '', $cpf);

         // Verifica se o CPF tem 11 dígitos
         if (strlen($cpf) !== 11) {
             return false;
         }

         // Verifica se todos os dígitos são iguais (CPF inválido)
         if (preg_match('/(\d)\1{10}/', $cpf)) {
             return false;
         }

         // Validação dos dígitos verificadores
         for ($t = 9; $t < 11; $t++) {
              $d = 0;
              for ($c = 0; $c < $t; $c++) {
                   $d += $cpf[$c] * (($t + 1) - $c);
              }
              $d = ((10 * $d) % 11) % 10;
              if ($cpf[$c] != $d) {
                 return false;
              }
         }

         return true;
    }

     // Valida o formato do E-mail
     public static function validaEmail($email) {
       return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
     }

}

?>
