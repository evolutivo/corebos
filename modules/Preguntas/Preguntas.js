/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
function set_return(preguntas_id, preguntas_name) {
  if (window.opener['tree_callback']) {
	  window.opener['tree_callback'](preguntas_id, preguntas_name);
	  return true;
	} else if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.preguntasid_display.value = preguntas_name;
        window.opener.document.QcEditView.preguntasid.value = preguntas_id;
	} else {
        window.opener.document.EditView.preguntasid_display.value = preguntas_name;
        window.opener.document.EditView.preguntasid.value = preguntas_id;
	}
}

function PreguntasSelectAll(mod,image_pth)
{
    if(document.selectall.selected_id != undefined)
    {
                var x = document.selectall.selected_id.length;
                var y=0;
                idstring = "";
                namestr = "";
                var action_str="";
                if ( x == undefined) {
                        if (document.selectall.selected_id.checked) {
                                idstring = document.selectall.selected_id.value;
                                c = document.selectall.selected_id.value;
                                var pregun_array = JSON.parse($('popup_pregun_'+c).attributes['vt_pregun_arr'].nodeValue);
                                var preguntasid = pregun_array['entityid'];
                                var question = pregun_array['question'];
                                var puntos_si = pregun_array['yes_points'];
                                var puntos_no = pregun_array['no_points'];
                                var categoria = pregun_array['categoriapregunta'];
                                var subcategoria = pregun_array['subcategoriapregunta'];
                                var count = pregun_array['count'];

                set_return_preguntas(preguntasid,question,puntos_si,puntos_no,categoria,subcategoria,count);
                                y=1;
                        } else {
                                alert(alert_arr.SELECT);
                                return false;
                        }
                } else {
                        y=0;
                        for(i = 0; i < x ; i++) {
                                if(document.selectall.selected_id[i].checked) {
                                        idstring = document.selectall.selected_id[i].value+";"+idstring;
                                        c = document.selectall.selected_id[i].value;
                    var pregun_array = JSON.parse($('popup_pregun_'+c).attributes['vt_pregun_arr'].nodeValue);
                    var preguntasid = pregun_array['entityid'];
                    var question = pregun_array['question'];
                    var puntos_si = pregun_array['yes_points'];
                    var puntos_no = pregun_array['no_points'];
                    var categoria = pregun_array['categoriapregunta'];
                    var subcategoria = pregun_array['subcategoriapregunta'];
                    //var count = prod_array['count'];
                                        if(y>0) {
                                                var count = window.opener.fnAddJLine(image_pth);
                                        } else {
                                                var count = pregun_array['count'];
                                        }

                                                set_return_preguntas(preguntasid,question,puntos_si,puntos_no,categoria,subcategoria,count);

                                        y=y+1;
                                }
                        }
                }
                if (y != 0) {
                        document.selectall.idlist.value=idstring;
                        return true;
                } else {
                        alert(alert_arr.SELECT);
                        return false;
                }
    }
}

function set_return_preguntas(preguntasid,question,puntos_si,puntos_no,categoria,subcategoria,count)
{

        var preguntaid = window.opener.document.getElementsByName("preguntasid_" + count)[0];


        var pregunta = window.opener.document.getElementsByName("pregunta" + count)[0];


        var category = window.opener.document.getElementsByName("category" + count)[0];


        var subcategory = window.opener.document.getElementsByName("subcategory" + count)[0];


        var yes_points = window.opener.document.getElementsByName("yes_points" + count)[0];


        var no_points = window.opener.document.getElementsByName("no_points" + count)[0];



        if(typeof(preguntaid) != 'undefined')
                preguntaid.value = preguntasid;
        if(typeof(pregunta) != 'undefined')
                pregunta.value = question;
        if(typeof(category) != 'undefined')
                category.value = categoria;
        if(typeof(subcategory) != 'undefined')
                subcategory.value = subcategoria;
        if(typeof(yes_points) != 'undefined')
                yes_points.value = puntos_si;
        if(typeof(no_points) != 'undefined')
                no_points.value = puntos_no;

}

function set_tree_returned_elements(elements, count, treesel) {
  jQuery.ajax({
      type: 'POST',
      url: 'index.php',
      async: false,
      dataType: 'json',
      data: {
        module: 'Preguntas',
        action: 'PreguntasAjax',
        file: 'getData',
        ids: elements,
	tree: treesel
      },
      success: function (pregun_array) {
        for(i=0; i<pregun_array.length; i++) {
          if(i>0) {
            count = window.opener.fnAddJLine('themes/softed/images/');
          }
          var preguntasid = pregun_array[i]['preguntasid'];
          var question = pregun_array[i]['question'];
          var puntos_si = pregun_array[i]['yes_points'];
          var puntos_no = pregun_array[i]['no_points'];
          var categoria = pregun_array[i]['categoriapregunta'];
          var subcategoria = pregun_array[i]['subcategoriapregunta'];
          set_return_preguntas(preguntasid, question, puntos_si, puntos_no, categoria, subcategoria, count);
        }
      }
  });
}