
/*+**********************************************************************************
- * The contents of this file are subject to the vtiger CRM Public License Version 1.0
- * ("License"); You may not use this file except in compliance with the License
- * The Original Code is:  vtiger CRM Open Source
- * The Initial Developer of the Original Code is vtiger.
- * Portions created by vtiger are Copyright (C) vtiger.
- * All Rights Reserved.
- ************************************************************************************/
function AdocmastersetValueFromCapture(recordid,value,target_fieldname) {
	//alert(target_fieldname);
        if(target_fieldname=='adoc_product2'){
            document.getElementById('adoc_product2').value = recordid;
				document.getElementById('adoc_product2_display').value = value;}
                            if(target_fieldname=='adoc_product'){
                                document.getElementById('adoc_product').value = recordid;
                                document.getElementById('adoc_product_display').value = value;
                            }
		

}