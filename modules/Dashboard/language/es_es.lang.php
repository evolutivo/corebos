<?php
/*************************************************************************************************
 * Copyright 2016 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
 * Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
 * file except in compliance with the License. You can redistribute it and/or modify it
 * under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
 * granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
 * the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
 * applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing
 * permissions and limitations under the License. You may obtain a copy of the License
 * at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *************************************************************************************************
*  Module       : Dashboard
*  Language     : Español
*  Version      : 504
*  Created Date : 2007-03-30 Last change : 2007-10-10
*  Author       : Joe Bordes
 ********************************************************************************/

$mod_strings = Array(
'LBL_SALES_STAGE_FORM_TITLE'=>'Oportunidades por fase de venta',
'LBL_SALES_STAGE_FORM_DESC'=>'Muestra suma acumulada de oportunidades de negocio por etapa para los usuarios seleccionados con fecha estimada de cierre dentro del tiempo especificado.',
'LBL_MONTH_BY_OUTCOME'=>'Oportunidades por resultados mensuales',
'LBL_MONTH_BY_OUTCOME_DESC'=>'Muestra la suma acumulada por resultados mensuales para los usuarios seleccionados dentro del rango de tiempo especificado. Resultados en base a oportunidades de negocio en estado "Cerrado".',
'LBL_LEAD_SOURCE_FORM_TITLE'=>'Todas las oportunidades por origen de contacto',
'LBL_LEAD_SOURCE_FORM_DESC'=>'Muestra la suma acumulada por origen de contacto para los usuarios seleccionados.',
'LBL_LEAD_SOURCE_BY_OUTCOME'=>'Todas la oportunidades por origen de contacto y resultados',
'LBL_LEAD_SOURCE_BY_OUTCOME_DESC'=>'Muestra la suma acumulada de resultados por origen de contacto, para los usuarios seleccionados, del rango especificado. Resultados en base a oportunidades de negocio en estado "Cerrado".',
'LBL_PIPELINE_FORM_TITLE_DESC'=>'Muestra la suma acumulada por estado de venta de sus oportunidades con fecha estimada de cierre dentro del rango de tiempo especificado.',
'LBL_DATE_RANGE'=>'Rango de tiempo es',
'LBL_DATE_RANGE_TO'=>'a',
'ERR_NO_OPPS'=>'Por favor, genere alguna oportunidad para ver sus gráficos.',
'LBL_TOTAL_PIPELINE'=>'El total de oportunidades es ',
'LBL_ALL_OPPORTUNITIES'=>'La suma total de todas las oportunides es  ',
'LBL_OPP_SIZE'=>'Sumas expresadas en Miles de',
'LBL_OPP_SIZE_VALUE'=>'1K',
'NTC_NO_LEGENDS'=>'Ninguna',
'LBL_LEAD_SOURCE_OTHER'=>'Otras',
'LBL_EDIT'=>'Editar',
'LBL_REFRESH'=>'Actualizar',
'LBL_CREATED_ON'=>'Ejecutado por última vez el ',
'LBL_OPPS_IN_STAGE'=>'Oportunidades por el estado de venta',
'LBL_OPPS_IN_LEAD_SOURCE'=>'Oportunidades por origen de los Pre-contactos',
'LBL_OPPS_OUTCOME'=>'Oportunidades por resultados',
'LBL_USERS'=>'Usuarios:',
'LBL_SALES_STAGES'=>'Fase de venta:',
'LBL_LEAD_SOURCES'=>'Origen de los Pre-Contactos:',
'LBL_DATE_START'=>'Fecha de inicio:',
'LBL_DATE_END'=>'Fecha de fin:',
//Added for 5.0 
'LBL_NO_PERMISSION'=>'Su perfil no permite ver la gráfica de este módulo',
'LBL_NO_PERMISSION_FIELD'=>'Su perfil no permite ver la gráfica de este módulo o campo',

'leadsource' => 'Pre-Contactos por Origen',
'leadstatus' => 'Pre-Contactos por Estado',
'leadindustry' => 'Pre-Contactos por Actividad',
'salesbyleadsource' => 'Ventas por Origen de Pre-Contacto',
'salesbyaccount' => 'Ventas por Cuenta',
'salesbyuser' => 'Ventas por Usuario',
'salesbyteam' => 'Ventas por Equipo',
'accountindustry' => 'Cuentas por Actividad',
'productcategory' => 'Productos por Categoría',
'productbyqtyinstock' => 'Productos por Cantidad en stock',
'productbypo' => 'Productos por Ordenes de Compra',
'productbyquotes' => 'Productos por Presupuestos',
'productbyinvoice' => 'Productos por Facturas',
'sobyaccounts' => 'Ordenes de Venta por Cuenta',
'sobystatus' => 'Ordenes de Venta por Estado',
'pobystatus' => 'Ordenes de Compra por Cuenta',
'quotesbyaccounts' => 'Presupuestos por Cuenta',
'quotesbystage' => 'Presupuestos por Estado',
'invoicebyacnts' => 'Facturas por Cuenta',
'invoicebystatus' => 'Facturas por Estado',
'ticketsbystatus' => 'Incidencias por Estado',
'ticketsbypriority' => 'Incidencias por Prioridad',
'ticketsbycategory' => 'Incidencias por Categoría',
'ticketsbyuser'=>'Incidencias por Usuario',
'ticketsbyteam'=>'Incidencias por Equipo',
'ticketsbyproduct'=>'Incidencias por Producto',
'contactbycampaign'=>'Contactos por Campaña',
'ticketsbyaccount'=>'Incidencias por Cuenta',
'ticketsbycontact'=>'Incidencias por Contacto',

'LBL_DASHBRD_HOME'=>'Estadísticas',
'LBL_HORZ_BAR_CHART'=>'Gráfico de barras horizontal',
'LBL_VERT_BAR_CHART'=>'Gráfico de barras vertical',
'LBL_PIE_CHART'=>'Gráfico de tarta',
'LBL_NO_DATA'=>'Sin Datos Disponibles',
'DashboardHome'=>'Estadísticas',
'GRIDVIEW'=>'Vista Rejilla',
'NORMALVIEW'=>'Vista normal',
'VIEWCHART'=>'Ver Gráfica',
'LBL_DASHBOARD'=>'Estadísticas',

// Added/Updated for vtiger CRM 5.0.4
"Approved"=>"Aprobado",
"Created"=>"Creado",
"Cancelled"=>"Cancelado",
"Delivered"=>"Solicitado",
"Received Shipment"=>"Envios Recibidos",
"Sent"=>"Enviado",
"Credit Invoice"=>"Factura Proforma",
"Paid"=>"Pagado",
"Un Assigned"=>"Sin Asignar",
"Cold Call"=>"Puerta Fría",
"Existing Customer"=>"Cliente",
"Self Generated"=>"Autogenerado",
"Employee"=>"Empleado",
"Partner"=>"Socio",
"Public Relations"=>"Relaciones Públicas",
"Direct Mail"=>"Correo Directo",
"Conference"=>"Conferencia",
"Trade Show"=>"Feria",
"Web Site"=>"Página Web",
"Word of mouth"=>"Boca a Boca",
"Other"=>"Otro",
"--None--"=>"Ninguno",
"Attempted to Contact"=>"Intentado Contactar",
"Cold"=>"Frio",
"Contact in Future"=>"Contactar más Adelante",
"Contacted"=>"Contactado",
"Hot"=>"Caliente",
"Junk Lead"=>"Contacto Basura",
"Lost Lead"=>"Contacto Fallido",
"Not Contacted"=>"No Contactado",
"Pre Qualified"=>"Pre Clasificado",
"Qualified"=>"Clasificado",
"Warm"=>"Tibio",
"Apparel"=>"Apparel",
"Banking"=>"Bancos",
"Biotechnology"=>"Biotecnología",
"Chemicals"=>"Química",
"Communications"=>"Comunicaciones",
"Construction"=>"Constructión",
"Consulting"=>"Consultoría",
"Education"=>"Educación",
"Electronics"=>"Electrónica",
"Energy"=>"Energía",
"Engineering"=>"Ingeniería",
"Entertainment"=>"Entretenimiento",
"Environmental"=>"Medio Ambiente",
"Finance"=>"Financiero",
"Food & Beverage"=>"Alimentación",
"Government"=>"Gobierno",
"Healthcare"=>"Salud",
"Hospitality"=>"Hospitalidad",
"Insurance"=>"Seguros",
"Machinery"=>"Maquinaria",
"Manufacturing"=>"Fabricación",
"Media"=>"Media",
"Not For Profit"=>"No Lucrativo",
"Recreation"=>"Recreo",
"Retail"=>"Retail",
"Shipping"=>"Envío",
"Technology"=>"Tecnología",
"Telecommunications"=>"Telecomunicaciones",
"Transportation"=>"Transporte",
"Utilities"=>"Utilidades",
"Hardware"=>"Hardware",
"Software"=>"Software",
"CRM Applications"=>"Aplicaciones de CRM",
"Open"=>"Abierto",
"In Progress"=>"En Progreso",
"Wait For Response"=>"Esperando Respuesta",
"Closed"=>"Cerrado",
"Low"=>"Baja",
"Normal"=>"Normal",
"High"=>"Alta",
"Urgent"=>"Urgente",
"Big Problem"=>"Gran Dificultad",
"Small Problem"=>"Pequeña Dificultad",
"Other Problem"=>"Otra Dificultad",
"Accepted"=>"Aceptado",
"Rejected"=>"Rechazado",
"Prospecting"=>"Buscando",
"Qualification"=>"Valorando",
"Needs Analysis"=>"Necesita Análisis",
"Value Proposition"=>"Valorando Proposición",
"Id. Decision Makers"=>"Identificando Responsable",
"Perception Analysis"=>"Analizando",
"Proposal/Price Quote"=>"Propuesta/Presupuesto",
"Negotiation/Review"=>"Negociando/Revisando",
"Closed Won"=>"Cerrado Ganado",
"Closed Lost"=>"Cerrado Perdido",

);

?>
