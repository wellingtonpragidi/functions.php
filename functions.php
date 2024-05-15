<?php
/**
 * Substitui strings para ser usado em slugs (use $period como true em arquivos para não remover o ponto da extensao)
 * @link https://www.php.net/manual/pt_BR/function.iconv.php
 * */
function str_clear($string, $period = false) {
    if($period == true) {
    	$string = preg_replace("/[^\w\d]$/", "", iconv("UTF-8", "ASCII//TRANSLIT", $string));
    	$string = str_replace(["!", "@", "#", "$", "%", "¨", "&", "(", ")", "+", "=", "{", "]", "}", "]", "'", '"', "|", "`", "´", "^", "~", ",", ";", "?"], "", $string);
    } else {
    	$string = preg_replace("/[^\w\s]/", "", iconv("UTF-8", "ASCII//TRANSLIT", $string));
    }
    $string = str_replace(" ", "-", $string);
    $string = strtolower($string);
    return $string;
}

/**
 * $hook: conteudo a ser resumido
 * $length: tamanho do resumo, numero de letras
 * */
function summary($hook, $length) {
    $output = substr(strip_tags($hook), 0, $length);
    $output .= (strlen($hook) > $length) ? '...' : '';
    return $output;
}

/**
 * @link https://packit.ui.webship.com.br/alerts/#php
 * */
function alert($type, $content, $setTime = 6000, $fadeTime = 2100, $redirect = '') {
    echo "<div class=\"alert $type\">$content</div>
    <script>";
    if( $setTime != false && $fadeTime != false && $redirect == '' ) {
        echo "window.setTimeout(function() {
            fade.out.selector('.alert', $fadeTime);
        }, $setTime)";
    }
    if( $setTime != false && $fadeTime == false && $redirect != '' ) {
        echo "window.setTimeout(function() {
            window.location='$redirect';
        }, $setTime)";
    }
    echo "</script>";
}

/**
 * @link https://packit.ui.webship.com.br/complements/preloader/
 * */
function preloader($time = 2400) {
    echo '<div class="loader">
        <div class="loading"></div>
        <div data-window="left"></div>
        <div data-window="right"></div>
    </div>
    <script>
        setTimeout(function() {
            document.body.classList.add("loaded");
        }, '.$time.');
    </script>';
}

/**
 * funcao para converter formato de data e/ou data e hora
 * $date: date ou datetime do banco de dados
 * $format: tipos do formato de saída (int) padrao = dia/mes/ano
 * classe DateTimeImmutable para substituir as funcoes strftime e strtotime qua estao defasadas
 */
function isdate($date, $format = 0) {

	function translate($date) {
		$pt_br = ['January' => 'Janeiro', 'February' => 'Fevereiro', 'March' => 'Março', 'April' => 'Abril', 'May' => 'Maio', 'June' => 'Junho', 'July' => 'Julho', 'August' => 'Agosto', 'September' => 'Setembro', 'October' => 'Outubro', 'November' => 'Novembro', 'December' => 'Dezembro'];
		return strtr($date, $pt_br);
	}

	$date = new DateTimeImmutable($date);

	switch ($format) {
		case 1:
			$isformat = $date->format('d \d\e F \d\e Y');
		break;
		case 2:
			$isformat = $date->format('d/m/Y \a\s H:i:s');
		break;
		case 3:
			$isformat = $date->format('d \d\e F \d\e Y \a\s H:i:s');
		break;
		default:
			$isformat = $date->format('d/m/Y');
		break;
	}
	return translate($isformat);
}

/**
 * Gerador de simbolos para URLs e senhas
 * */
function token_generator($size = 22, $symbol = '') {
    $lower      = 'abcdefghijklmnopqrstuvwxyz';
    $upper      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $number     = '1234567890';
    $symbol     == 'password' ? '!@#$%&*()_+=-[]{}~^><:;?' : '!@$*-~=_#';
    $display    = '';
    $characters = $upper.$lower.$number.$symbol;
    $length = strlen($characters);
    for ($i = 1; $i <= $size; $i++) {
    	if($size < 10) {
    		return "Token precisa ter no mínimo 10 caracteres.";
    	}
    	if($size > 80) {
    		return "Token pode ter no máximo 80 caracteres.";
    	}
    	else {
	        $random = mt_rand(1, $length);
	        $display .= $characters[$random - 1];
	    }
    }
    return $display;
}

/**
 * funcao para validar requisitos de senhas.
 *  ) Expressao regular:
 * 1) | = delimitador usado  ◄►  ^ = inicio da expressao  ◄►  $ = fim da expressao
 * 2) (?=.*[a-z]) = expressão requer que tenha pelo menos uma letra minuscula 
 * 3) (?=.*[A-Z]) = expressão requer que tenha pelo menos uma letra maiuscula
 * 5) (?=.*\d) = expressão requer que tenha pelo menos um numer
 * 6) !@#$%&*()_+=-><.,;? = expressão indica que pode haver os carateres abaixo, porem isso nao é obrigatorio
 * 7) {8,20} = {min,max} obrigatorio o minimo de 8 e o maximo de 20 caracteres na expressao 
 * */
function regex_password($password) {
    $pattern = "|^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%&*()_+=-><.,;?\s]{8,20}$|";

    return preg_match($pattern, $password) ? true : false;
}

/**
 * caso precise remover ultima virgula de um "array" */
function destroylastcomma($str) {
    $list = array(
        ", ;" => ";", 
        ",;"  => ";", 
        ",)"  => ")", 
        ", )" => ")"
    );
    $str = strtr($str, $list);
    return $str;
}

/**
 * calcular idade */
function calc_age($birth) { 
    $birth = explode('/', $birth); 
    $today = date('d/m/Y'); $today = explode('/', $today); 
    $years = $today[2] - $birth[2]; 
    if($birth[1] > $today[1]) {
        return $years - 1; 
    }
    if($birth[1] == $today[1]) { 
        if($birth[0] <= $today[0]) { 
            return $years; 
        } 
        else { 
            return $years - 1; } 
    }
    return $years; 
}
