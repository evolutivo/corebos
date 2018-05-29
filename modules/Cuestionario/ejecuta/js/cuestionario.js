$(document).ready(function(){
        
    // first example
    $("#navigation").treeview({
        persist: "location",
        animated: "fast",
        control: "#treecontrol",
        controlb: "#treecontrolb",
    });
});
    
function valida(obj){
    var element = $(obj);
    var id = element.attr("name");
    var accion=element.val();
    switch(accion){
        case 'yes': ajaxrespuesta('yes',id);break;
        case 'no' : ajaxrespuesta('no', id);break;
        case 'text': $('#texto_'+id).css('display','');break; 
        case 'na': 
            $('#texto_'+id).css('display','');
            $('#texto_'+id).css('display', 'none');
        break;
    }
    $('#ok_'+id).attr("src","ok.jpg");
    
}

function muestraPregunta(obj){
    var element = $(obj);
    var id = element.attr("name");
    $('#texto_'+id).css('display','');
    
}

function ocultaPregunta(obj){
    var element = $(obj);
    var id = element.attr("name");
    $('#texto_'+id).css('display','');
    $('#texto_'+id).css('display','none');
    
}

function ajaxrespuesta(accion, id){
    $.ajax({
                 type: "get",
                 url: "ajax/formularioajax.php",
                 data: "action="+accion+'&id='+ id,
                 success: function(data){
                    var campo = "#texto_"+id;
                    $(campo).css('display', 'none');
                    $(campo).attr('innerHTML',data);
                    
                 }
              });
}