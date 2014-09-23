<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 ********************************************************************************
*  Module       : Reports
*  Language     : Español
*  Version      : 5.4.0
*  Created Date : 2007-03-30
*  Last change  : 2012-02-28
*  Author       : Odin Consultores, Francisco Hernandez
 ********************************************************************************/

$mod_strings = Array(
'LBL_MODULE_NAME' => 'Informes',
'LBL_MODULE_TITLE'=>'Informes: Inicio',
'LBL_CREATE_REPORT'=>'Crear Informe',
'LBL_CUSTOMIZE_REPORT'=>'Personalizar Informes',
'LBL_REP_BUTTON'=>'Crear Nuevo Informe',
'LBL_REP_FOLDER_BUTTON'=>'Nueva carpeta de informes',
'LBL_REP_FOLDER'=>'Carpeta del Informe',
'LBL_REP_FOLDER_DTLS'=>'Detalles de la Carpeta',
'LBL_REP_FOLDER_NAME'=>'Nombre de la Carpeta:',
'LBL_REP_FOLDER_DESC'=>'Descripción de la Carpeta:',
'LBL_NEW_REP0_HDR1'=>'Seleccione un Módulo para el cual tenga que crear un nuevo informe :',
'LBL_NEW_REP0_HDR2'=>'Seleccione el Módulo Relacionado:',
'LBL_NEW_REP0_HDR3'=>'Nota:',
'LBL_NEW_REP0_HDR4'=>'El modulo del informe y el modulo relacionado una vez seleccionados no pueden ser modificados',
'LBL_CONTINUE_BUTTON'=>'Continuar',
'LBL_NEW_REP1_HDR1'=>'Proporcione la información siguiente del informe',
'LBL_SELECT_COLUMNS'=>'Seleccionar Columnas',
'LBL_SPECIFY_GROUPING'=>'Especifique Agrupación',
'LBL_COLUMNS_TO_TOTAL'=>'Elija las columnas para sumar',
'LBL_SPECIFY_CRITERIA'=>'Especifique los criterios',
'LBL_SAVERUN_BUTTON'=>'Guardar y Ejecutar',
'LBL_TABULAT_REPORT'=>'Informe Tabulado',
'LBL_REPORT_TYPE_HDR1'=>'El Informe Tabular es la manera más simple y más rápida de conseguir un listado de sus datos.',
'LBL_SUMMARY_REPORT'=>'Informe de Resumen',
'LBL_REPORT_TYPE_HDR2'=>'Los Informes de resumen permiten que veas los datos junto con los subtotales y otra información en resumen',
'LBL_AVAILABLE_COLUMNS'=>'Columnas Disponibles:',
'LBL_SELECTED_COLUMNS'=>'Columnas Seleccionadas:',
'LBL_ADD_BUTTON'=>'Agregar',
'LBL_COLUMNS'=>'Columnas',
'LBL_COLUMNS_SUM'=>'Sumar',
'LBL_COLUMNS_AVERAGE'=>'Promedio',
'LBL_COLUMNS_LOW_VALUE'=>'El valor más bajo',
'LBL_COLUMNS_LARGE_VALUE'=>'El valor más alto',
'LBL_NONE'=>'Ninguno',
'LBL_GROUPING_SORT'=>'Orden del grupo:',
'LBL_GROUPING_SUMMARIZE'=>'Resumir la información por:',
'LBL_GROUPING_THEN_BY'=>'y despues por:',
'LBL_GROUPING_FINALLY_BY'=>'y finalmente por:',
'LBL_ADVANCED_FILTER'=>'Filtros avanzados',
'LBL_STANDARD_FILTER'=>'FIltros estandar',
'LBL_SF_COLUMNS'=>'Columna',
'LBL_SF_STARTDATE'=>'Fecha de Inicio',
'LBL_SF_ENDDATE'=>'Fecha de Fin',
'LBL_AF_HDR1'=>'Fije las condiciones de la búsqueda para restringir la lista.',
'LBL_AF_HDR2'=>'Puede utilizar el &quot;or&quot; en los filtros incorporando artículos múltiples a la tercera columna.',
'LBL_AF_HDR3'=>'Puede incorporar hasta 10 artículos, separados por comas. Por ejemplo:  El CA, NY, TX, FL para buscar el CA o NY o TX o el FL.',
'LBL_FILTER_OPTIONS'=>'Opciones de los Filtros',
'LBL_CUSTOMIZE_BUTTON'=>'Personalizar',
'LBL_EXPORTPDF_BUTTON'=>'Exportar a PDF',
'LBL_APPLYFILTER_BUTTON'=>'Aplicar Filtros',
'LBL_GENERATED_REPORT'=>'Informe Generado',
'LBL_GRAND_TOTAL'=>'Importe Total',

//Added for 4.2 Patch I
'LBL_EXPORTXL_BUTTON'=>'Exportar a Excel',
'LBL_EXPORTCSV'=>'Exportar a CSV',
//Added for 5 Beta
'LBL_NO_PERMISSION'=>'Tu perfil no está autorizado a ver los informes de uno de los módulos',
'LBL_SELECT_COLUMNS_TO_GENERATE_REPORTS'=>'Seleccione columnas para generar el informe',
'LBL_AVAILABLE_FIELDS'=>'Campos Disponibles',
'LBL_SELECTED_FIELDS'=>'Campos Seleccionados',
'LBL_CALCULATIONS'=>'Cálculos',
'LBL_SELECT_COLUMNS_TO_TOTAL'=>'Seleccione columnas para el Total',
'LBL_SELECT_FILTERS_TO_STREAMLINE_REPORT_DATA'=>'Seleccione los filtros para los informes',
'LBL_SELECT_FILTERS'=>'Filtros',
'LBL_SELECT_COLUMNS_TO_GROUP_REPORTS'=>'Seleccione columnas para informe agrupado',
'LBL_BACK_TO_REPORTS'=>'Volver a los Informes',
'LBL_SELECT_ANOTHER_REPORT'=>'Seleccione otro Informe',
'LBL_SELECT_COLUMN'=>'Seleccione una Columna',
'LBL_SELECT_TIME'=>'Seleccione la Hora',
'LBL_PRINT_REPORT'=>'Imprimir Informe',
'LBL_CLICK_HERE'=>'Pulse Aquí',
'LBL_TO_ADD_NEW_GROUP'=>'para agregar un nuevo grupo',
'LBL_CREATE_NEW'=>'Crear Nuevo',
'LBL_RELATIVE_MODULE'=>'Modulo Relacionado',
'LBL_REPORT_TYPE'=>'Tipo de Informe',
'LBL_REPORT_DETAILS'=>'Detalles de Informe',
'LBL_TYPE_THE_NAME'=>'Introduzca el Nombre',
'LBL_DESCRIPTION_FOR_REPORT'=>'Descripción para el Informe',
'LBL_REPORT_NAME'=>'Nombre del Informe',
'LBL_DESCRIPTION'=>'Descripción',
'LBL_TOOLS'=>'Herramientas',
'LBL_AND'=>'y',
'LBL_ADD_NEW_GROUP'=>'Agregar un Nuevo Grupo',
'LBL_REPORT_MODULE'=>'Módulo de Informes',
'LBL_SELECT_RELATIVE_MODULE_FOR_REPORT'=>'Seleccione el Módulo Relacionado para el Informe',
'LBL_SELECT_REPORT_TYPE_BELOW'=>'Seleccione abajo el tipo de Informe',
'LBL_TABULAR_FORMAT'=>'Formato Tabulado',
'LBL_TABULAR_REPORTS_ARE_SIMPLEST'=>'Los Informes Tabulados son la manera más sencilla y rápida de obtener sus datos',
'LBL_SUMMARY_REPORT_VIEW_DATA_WITH_SUBTOTALS'=>'Los Informes de resumen permiten que veas los datos junto con los subtotales y otra información en resumen',
'LBL_FILTERS'=>'Filtros',
'LBL_MOVE_TO'=>'Mover a',
'LBL_RENAME_FOLDER'=>'Renombrar Directorio',
'LBL_DELETE_FOLDER'=>'Borrar Directorio',

'Account and Contact Reports'=>'Informes de Cuentas y Contactos',
'Lead Reports'=>'Informes de Prospectos',
'Potential Reports'=>'Informes de Oportunidades',
'Activity Reports'=>'Informes de Tareas',
'HelpDesk Reports'=>'Informes de Casos',
'Product Reports'=>'Informes de Productos',
'Quote Reports'=>'Informes de Cotizaciones',
'PurchaseOrder Reports'=>'Informes de Pedidos de Compra',
'SalesOrder Reports'=>'Informes de Pedidos',
'Invoice Reports'=>'Informes de Facturación',
'Campaign Reports'=>'Informes de Campaña',
'Contacts by Accounts'=>'Contactos por Cuenta',
'Contacts without Accounts'=>'Contactos sin Cuentas',
'Contacts by Potentials'=>'Contactos por Oportunidades',
'Contacts related to Accounts'=>'Contactos relacionados con Cuentas',
'Contacts not related to Accounts'=>'Contactos sin Cuenta',
'Contacts related to Potentials'=>'Contactos relacionados con Oportunidades',
'Lead by Source'=>'Prospectos por Origen',
'Lead Status Report'=>'Informes de Estado de los Prospectos',
'Potential Pipeline'=>'Gráfica de Oportunidades',
'Closed Potentials'=>'Oportunidades Cerradas',
'Potential that have Won'=>'Oportunidades Exitosas',
'Tickets by Products'=>'casos por Producto',
'Tickets by Priority'=>'casos por Prioridad',
'Open Tickets'=>'casos Abiertos',
'Tickets related to Products'=>'casos relacionados con Productos',
'Tickets that are Open'=>'casos Abiertos',
'Product Details'=>'Detalles de Productos',
'Products by Contacts'=>'Productos por Contacto',
'Product Detailed Report'=>'Informes detallado de Productos',
'Products related to Contacts'=>'Productos relacionados con Contactos',
'Open Quotes'=>'Cotizaciones Pendientes',
'Quotes Detailed Report'=>'Informes de Cotizaciones Detallados',
'Quotes that are Open'=>'Cotizaciones Pendientes',
'PurchaseOrder by Contacts'=>'Órdenes de Compra por Contactos',
'PurchaseOrder Detailed Report'=>'Informes detallados de Órdenes de Compra',
'PurchaseOrder related to Contacts'=>'Órdenes de Compra relacionadas con Contactos',
'Invoice Detailed Report'=>'Informes detallado de Facturas ',
'Last Month Activities'=>'Tareas del Mes Pasado',
'This Month Activities'=>'Tareas de este Mes',
'Campaign Expectations and Actuals'=>'Expectativas y Realidad de Campaña',
'SalesOrder Detailed Report'=>'Informes detallado de Pedidos',

'LBL_DELETE'=>'Borrar',
'Create_Reports'=>'Crear Informe',
'Create_New_Folder'=>'Crear Carpeta',
'Move_Reports'=>'Mover Informes',
'Delete_Report'=>'Borrar Informes',

'Custom'=>'Personalizar',
'Previous FY'=>'Año Fiscal Anterior',
'Current FY'=>'Año Fiscal Actual',
'Next FY'=>'Año Fiscal Próximo',
'Previous FQ'=>'Trimestre Fiscal Anterior',
'Current FQ'=>'Trimestre Fiscal Actual',
'Next FQ'=>'Trimestre Fiscal Próximo',
'Yesterday'=>'Ayer',
'Today'=>'Hoy',
'Tomorrow'=>'Mañana',
'Last Week'=>'Última Semana',
'Current Week'=>'Semana Actual',
'Next Week'=>'Próxima Semana',
'Last Month'=>'Último Mes',
'Current Month'=>'Mes Actual',
'Next Month'=>'Próximo Mes',
'Last 7 Days'=>'Últimos 7 Días',
'Last 30 Days'=>'Últimos 30 Días',
'Last 60 Days'=>'Últimos 60 Días',
'Last 90 Days'=>'Últimos 90 Días',
'Last 120 Days'=>'Últimos 120 Días',
'Next 7 Days'=>'Próximos 7 Días',
'Next 30 Days'=>'Próximos 30 Días',
'Next 60 Days'=>'Próximos 60 Días',
'Next 90 Days'=>'Próximos 90 Días',
'Next 120 Days'=>'Próximos 120 Días',
'TITLE_VTIGERCRM_CREATE_REPORT'=>'Vtiger CRM - Crear Informe',
'TITLE_VTIGERCRM_PRINT_REPORT'=>'Vtiger CRM - Imprimir Informe',
'NO_FILTER_SELECTED'=>'Sin Filtrar',

'LBL_GENERATE_NOW'=>'Generar',
'Totals'=>'Totales',
'SUM'=>'SUM',
'AVG'=>'PROM',
'MAX'=>'MAX',
'MIN'=>'MIN',
'LBL_CUSTOM_REPORTS'=>'Informes Personalizados',

'ticketid'=>'Id de Caso',
'NO_COLUMN'=>'No Hay Columnas disponibles para Total',

// Added/Updated for vtiger CRM 5.0.4
'LBL_REPORT_DELETED' => 'El Informe que intentas ver ha sido eliminado.',

//Added for Reports
'LBL_SHARING'=>'Compartir',
'SELECT_FILTER_TYPE'=>'Selecciona Tipo Informe',
'LBL_USERS'=>'Usuarios',
'LBL_GROUPS'=>'Grupos',
'LBL_SELECT_FIELDS'=>'Selecciona Campos',
'LBL_MEMBERS'=>'Miembros',
'LBL_RELATED_FIELDS'=>'Campos Relacionados',
'LBL_NO_ACCESS'=>' Acceso Denegado al módulo(s) ',
'LBL_NOT_ACTIVE'=>' Acceso Denegado al módulo(s) ',
'LBL_PERM_DENIED'=>' Permiso Denegado al Informe(s): ',
'LBL_FLDR_NOT_EMPTY'=>'La carpeta que intentas eliminar no esta vacía, Mueve o Elimina los Informes que contiene.',
'NO_REL_MODULES'=>'No hay módulos relacionados con el módulo seleccionado',
'LBL_REPORT_GENERATION_FAILED'=>'Error generando informe!',
'LBL_OR'=>'o',
'LBL_NEW_GROUP'=>'Agregar Grupo',
'LBL_DELETE_GROUP'=>'Eliminar Grupo',
'LBL_NEW_CONDITION'=>'Agregar Condición',
'LBL_SHARING_TYPE'=>'Compartir',
'LBL_SELECT_REPORT_TYPE_TO_CONTROL_ACCESS'=>'Selecciona el método de compartir para controlar el acceso al Informe', 
'LBL_ACTION' => 'Acción',
'LBL_VIEW_DETAILS' => 'Ver Detalles',
'LBL_SHOW_STANDARD_FILTERS' => 'Mostrar Filtros Estándar',
'LBL_YEAR' => 'Año',
'LBL_MONTH'=> 'Mes',
'LBL_QUARTER'=> 'Trimestre',
'LBL_NONE'=> 'Ninguno',

'LBL_ROLES'=>'Roles',
'LBL_ROLES_SUBORDINATES'=>'Roles y Subordinados',
'LBL_SCHEDULE_EMAIL'=>'Programar Email',
'LBL_SCHEDULE_EMAIL_DESCRIPTION'=>'Programa el Informe para ser enviado via email a los destinatarios seleccionados a intervalos regulares',
'LBL_USERS_AVAILABEL'=> 'Destinatarios',
'LBL_REPORT_FORMAT_PDF'=> 'PDF',
'LBL_REPORT_FORMAT_EXCEL'=> 'Excel',
'LBL_REPORT_FORMAT_BOTH'=> 'Ambos',
'LBL_REPORT_FORMAT'=> 'Formato Informe',
'LBL_USERS_SELECTED'=>'Destinatarios Seleccionados',
'LBL_SELECT'=>'Selecciona',
'Hourly'=>'Cada hora',
'Daily'=>'Diario',
'Weekly'=>'Semanal',
'BiWeekly'=>'Bi Semanal',
'Monthly'=>'Mensual',
'Annually'=>'Anual',
'LBL_SCHEDULE_REPORT'=>'Programar Informe',
'LBL_SCHEDULE_FREQUENCY'=>'Frecuencia',
'OPTION_SCHEDULE_EMAIL_CHOOSE'=>'Elige ..... ',
'LBL_SCHEDULE_EMAIL_TIME'=>'Hora',
'LBL_SCHEDULE_EMAIL_DOW'=>'Día Semana',
'LBL_SCHEDULE_EMAIL_DAY'=>'Día',
'LBL_SCHEDULE_EMAIL_MONTH'=>'Mes',
'WEEKDAY_STRINGS' => array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'),
'MONTH_STRINGS' => array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'),

'LBL_AUTO_GENERATED_REPORT_EMAIL' => 'Email auto-generado para entregar informe.',
'LBL_TIME_FORMAT_MSG' => 'hh:mm (formato 24 horas)',

'LBL_SAVE_REPORT' => 'Guardar',
'LBL_SAVE_REPORT_AS' => 'Guardar como...',

//Report Charts
'LBL_VIEW_CHARTS' => 'Ver Gráficos',
'LBL_ADD_CHARTS' => 'Añadir Gráfico a Portada',
'LBL_HOME_REPORT_NAME' => 'Nombre Informe',
'LBL_HOME_HORIZONTAL_BARCHART' => 'Gráfico Barras Horizontal',
'LBL_HOME_VERTICAL_BARCHART' => 'Gráfico Barras Vertical',
'LBL_HOME_PIE_CHART' => 'Gráfico circular',
'LBL_HOME_REPORT_TYPE' => 'Tipo Informe',
'LBL_HOME_WINDOW_TITLE' => 'Título de Ventana',
'LBL_GROUPING_TIME' => 'Agrupar por Tiempo',
'LBL_WIDGET_ADDED' => 'Widget Añadido Correctamente.',

)

?>
