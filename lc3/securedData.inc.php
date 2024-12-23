<?php
//************************
//securisation donnée transmises $_POST et $_GET
//http://sdz.tdct.org/sdz/securite-php-securiser-les-flux-de-donnees.html

function securite_bdd($string) {
		global $link;//$link défini hors fonction existe aussi dans la fonction, sinon à passer en argument
//https://www.php.net/manual/en/function.ctype-digit.php
        if(ctype_digit($string)) {// On regarde si le type de $string est un nombre entier (int)
//https://www.php.net/manual/en/function.intval.php      
            $string = intval($string);
        } else { // Pour tous les autres types
            $string = mysqli_real_escape_string($link,$string);
//https://www.php.net/manual/en/mysqli.real-escape-string.php            
            $string = addcslashes($string, '%_');
//https://www.php.net/manual/en/function.addcslashes.php            
        }         
        return $string;
}
//fin securité

//****************************************************
function random_pw($pw_length) {
    $pass = NULL;
    $charlist = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz023456789';
    $ps_len = strlen($charlist);
    mt_srand((double)microtime()*1000000);

    for($i = 0; $i < $pw_length; $i++) {
        $pass .= $charlist[mt_rand(0, $ps_len - 1)];
    }
    return ($pass);
}
//******************************************


?>