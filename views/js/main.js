$(document).ready(pagination(1));
function pagination(partida){
    var url = 'views/HTMLUsuarios.php';
    $.ajax({
        type: 'POST',
        url:url,
        data:'partida='+partida,
        success:function(data){
            var array = eval(data);
            $('#agrega-registros').html(array[0]);
            $('#paginacion').html(array[1]);
        }
    })
}