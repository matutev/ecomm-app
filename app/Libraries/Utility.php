<?php

namespace App\Libraries;

class Utility
{
    /**
     * Chequea si los parametros poseen caracteres especiales
     * 
     * @param array $params            los parametros del post        
     * 
     * @return bool                     true si algun parametros contiene sino false   
     */
    public static function validateSpecialChars(array $params): bool
    {   
        $specialChars = '!@#$%^&*()_=+[{]};:\'",<>?\\|';

        foreach($params as $param){
            if(strpbrk($param, $specialChars) !== false){
                return true;
            }
        }

        return false;
    }

}
