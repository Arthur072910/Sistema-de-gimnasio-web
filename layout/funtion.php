<?php
// functions.php
function active_class($current_page) {
    // Obtiene el nombre del archivo actual (ej: index.php)
    $url_array =  explode('/', $_SERVER['PHP_SELF']) ;
    $name = end($url_array);  
    
    if($name == $current_page){
        return 'active'; // Retorna la clase de CSS activa
    }
}
?>