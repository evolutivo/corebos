function moveStock(){
 var productid=document.getElementById("product_selected_id").value;
 alert(productid);
$.post('index.php', {
module:'evvtApps',
action:'evvtAppsAjax',
file:'kendo_content', ajax:'true',kaction:'moveStock',product:productid,
async:false
},
function(result){
   alert(result); 
});   
}
