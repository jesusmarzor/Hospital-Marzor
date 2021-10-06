<?php
require_once(__DIR__.'/views/WebPage.php');
require_once(__DIR__.'/core/request.php');
require_once(__DIR__.'/core/router.php');
$req = new Request();
$router = new Router($req);
$router->iniciarSesion();
$Pagina = new WebPage($req);
$router->execute($Pagina);
$Pagina->cargarPaina();


//$Usuario = new MUsuario();
// echo "<h1>Usuarios</h1>";
// $usuarios = $Usuario->get();
// foreach($usuarios as $usuario){
//     echo $usuario['nombre'].'->'.$usuario['dni'];
//     echo '<br/>';
// }
// $Vacuna = new MVacuna();
// $vacunaNueva = [
//     'nombre' => 'jaja',
//     'acronimo' => 'pp',
//     'descripcion' => 'sasassa'
// ];

// $idVacuna = $Vacuna->insert($vacunaNueva);
//$usuarioParam = $Usuario->getParametros("fechaNac");
// foreach($usuarioParam as $usuario){
//     var_dump($usuario);
//     echo '<br/>';
// }

// $vacunaEdit = [
//     'nombre' => 'Jesus'
// ];
// $idVacuna = $Vacuna->update($vacunaEdit,29);

// // echo $Vacuna->delete(26);

// echo "<h1>Vacunas</h1>";
// $vacunas = $Vacuna->get();
// foreach($vacunas as $vacuna){
//     echo $vacuna['ID'].")".$vacuna['nombre'].'->'.$vacuna['acronimo'];
//     echo '<br/>';
// }
?>