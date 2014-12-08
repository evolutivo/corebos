<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by TSolucio are Copyright (C) TSolucio.
 * All Rights Reserved.
 ************************************************************************************/

$optionalModuleStrings = array(
	'CustomerPortal_description'=>'Configuración del comportamiento del Portal del Cliente',
	'FieldFormulas_description'=>'Configuración de Fórmulas de actualización de campos personalizados',
	'RecycleBin_description'=>'Módulo de control de entidades eliminadas, permite restaurar y eliminar definitivamente',
	'Tooltip_description'=>'Configuración de referencias de campos que pueden ser una combinación de otros',
	'Webforms_description'=>'Soporte para webforms. Captura de información externa.',
	'SMSNotifier_description'=>'Envío de mensajes SMS a Cuentas, Contactos y PreContactos',
	'Assets_description'=>'Gestión de Recursos',
	'ModComments_description' => 'Permite añadir comentarios a las entidades relacionadas',
	'Projects_description' => 'Gestión de Proyectos, Tareas e Hitos',
	'Dutch_description' => 'Dutch Language pack',
	'French_description' => 'Pack de langue Francais',
	'Hungarian_description' => 'Hungarian Language pack',
	'Spanish_description' => 'Paquete Idioma Español',
	'Deutsch_description' => 'Paquete Idioma Alemán',
);

$installationStrings = array(
	'LBL_VTIGER_CRM_5' => 'vtiger CRM 5',
	'LBL_CONFIG_WIZARD' => 'Asistente Configuración',
	'LBL_WELCOME' => 'Bienvenido',
	'LBL_WELCOME_CONFIG_WIZARD' => 'Bienvenido al Asistente de Configuración',
	'LBL_ABOUT_CONFIG_WIZARD' => 'Este asistente de configuración te ayuda a instalar vtigerCRM ',
	'LBL_ABOUT_VTIGER' => 'vtiger CRM es un proyecto de aplicación CRM totalmente código abierto y dirigido por la comunidad. <br><br>
							El objetivo del proyecto es suministrar la mejor y más abierta solución CRM al menor coste total de propiedad para la PYME.<br><br> 
							El producto está siendo utilizado ampliamente en el mundo entero, con más de mil descargas diarias. <br><br>
							vtiger CRM tiene una comunidad de desarrolladores y usuario activa, con colaboradores de muchos países distintos.<br> <br>
							Está disponible en muchos idiomas, con una extensa red de asociados en docenas de países.',
	'LBL_INSTALL' => 'Instalar',
	'LBL_MIGRATE' => 'Migrar',
	'ERR_RESTRICTED_FILE_ACCESS' => 'Lo siento! Intento de acceder a un fichero restringido',
	'LBL_INSTALLATION_CHECK' => 'Verificación de Instalación',
	'LBL_BACK' => 'Atrás',
	'LBL_NEXT' => 'Siguiente',
	'LBL_AGREE' => 'Acepto',
	'LBL_SYSTEM_CONFIGURATION'=> 'Configuración Sistema',
	'LBL_INSTALLATION_CHECK' => 'Verificación de Instalación',
	'LBL_PRE_INSTALLATION_CHECK' => 'Verificación antes de Instalación',
	'LBL_INSTALLING' => 'Instalando...',
	'DoingStep' => 'Ejecutando el paso',
	'LBL_CHECK_AGAIN' => 'Verificar',
	'LBL_CONFIRM_SETTINGS' => 'Confirmar Configuración',
	'LBL_CONFIRM_CONFIG_SETTINGS' => 'Confirmar Datos de Configuración',
	'LBL_CONFIG_FILE_CREATION' => 'Creando Fichero Configuración',
	'LBL_OPTIONAL_MODULES' => 'Módulos Opcionales',
	'LBL_SELECT_OPTIONAL_MODULES_TO_install' => 'Selecciona los Módulos Opcionales a Instalar',
	'LBL_SELECT_OPTIONAL_MODULES_TO_update' => 'Selecciona los Módulos Opcionales a Actualizar',
	'LBL_SELECT_OPTIONAL_MODULES_TO_copy' => 'Selecciona los Módulos Opcionales a Copiar',
	'MSG_CONFIG_FILE_CREATED' => 'Fichero de Configuración (config.inc.php) creado correctamente',
	'LBL_FINISH' => 'Terminar',
	'LBL_CONFIG_COMPLETED' => 'Configuración Completada',
	'LBL_PHP_VERSION_GT_5' => 'Versión PHP >= 5.2',
	'LBL_YES' => 'Sí',
	'LBL_NO' => 'No',
	'LBL_NOT_CONFIGURED' => 'No Configurado',
	'LBL_IMAP_SUPPORT' => 'Soporte IMAP',
	'LBL_ZLIB_SUPPORT' => 'Soporte Zlib',
	'LBL_GD_LIBRARY' => 'Librería gráfica GD',
	'LBL_RECOMMENDED_PHP_SETTINGS' => 'Configuración Recomendada PHP',
	'LBL_DIRECTIVE' => 'Directiva',
	'LBL_RECOMMENDED' => 'Recomendada',
	'LBL_PHP_INI_VALUE' => 'Valor PHP.ini',
	'LBL_READ_WRITE_ACCESS' => 'Acceso Lectura/Escritura',
	'LBL_NOT_RECOMMENDED' => 'No Recomendada',
	'LBL_PHP_DIRECTIVES_HAVE_RECOMMENDED_VALUES' => 'La configuración PHP es la recomendada',
	'MSG_PROVIDE_READ_WRITE_ACCESS_TO_PROCEED' => 'Habilita acceso lectura/escritura a los ficheros y directorios listados a continuación para proceder',
	'WARNING_PHP_DIRECTIVES_NOT_RECOMMENDED_STILL_WANT_TO_PROCEED' => 'Algunas de las directivas de configuración de PHP no son las recomendadas. Esto puede afectar algunas de las características de vtiger CRM. ¿Seguro que quieres seguir?',
	'LBL_CHANGE' => 'Cambiar',
	'LBL_DATABASE_INFORMATION' => 'Información Base de Datos',
	'LBL_CRM_CONFIGURATION' => 'Configuración CRM',
	'LBL_USER_CONFIGURATION' => 'Configuración Usuario',
	'LBL_DATABASE_TYPE' => 'Tipo Base de Datos',
	'LBL_NO_DATABASE_SUPPORT' => 'No se ha encontrado soporte para Base de Datos',
	'LBL_HOST_NAME' => 'Nombre Servidor',
	'LBL_USER_NAME' => 'Nombre Usuario',
	'LBL_PASSWORD' => 'Contraseña',
	'LBL_DATABASE_NAME' => 'Nombre Base de Datos',
	'LBL_CREATE_DATABASE' => 'Crear Base de Datos',
	'LBL_DROP_IF_EXISTS' => 'Se eliminará la base de datos si existe',
	'LBL_ROOT' => 'Raíz',
	'LBL_UTF8_SUPPORT' => 'Soporte UTF-8',
	'LBL_URL' => 'URL',
	'LBL_CURRENCY_NAME' => 'Nombre moneda',
	'LBL_USERNAME' => 'Nombre usuario',
	'LBL_EMAIL' => 'Correo',
	'LBL_POPULATE_DEMO_DATA' => 'Crear información de demostración',
	'LBL_DATABASE' => 'Base de Datos',
	'LBL_SITE_URL' => 'Url Servidor',
	'LBL_PATH' => 'Camino',
	'LBL_MISSING_REQUIRED_FIELDS' => 'Faltan campos obligatorios',
	'ERR_ADMIN_EMAIL_INVALID' => 'La cuenta de correo del usuario admin es inválida',
	'ERR_STANDARDUSER_EMAIL_INVALID' => 'La cuenta de correo del usuario standard es inválida',
	'WARNING_LOCALHOST_IN_SITE_URL' => 'Indica el nombre de servidor exacto en vez de "localhost" en la URL del servidor, o tendrá algunos problemas con las extensiones de la aplicación. ¿Quieres continuar?',
	'LBL_DATABASE_CONFIGURATION' => 'Configuración Base de Datos',
	'LBL_ENABLED' => 'Habilitado',
	'LBL_NOT_ENABLED' => 'No Habilitado',
	'LBL_SITE_CONFIGURATION' => 'Configuración Sitio',
	'LBL_DEFAULT_CHARSET' => 'Codificación por Defecto',
	'ERR_DATABASE_CONNECTION_FAILED' => 'No se ha podido conectar con el servidor de la base de datos',
	'ERR_INVALID_MYSQL_PARAMETERS' => 'Parámetros de Conexión mySQL Incorrectos',
	'MSG_LIST_REASONS' => 'Esto puede ser debido a las siguientes causas',
	'MSG_DB_PARAMETERS_INVALID' => 'alguno de los parámetros de acceso a la base de datos es incorrecto, revisalos e intentalo de nuevo',
	'MSG_DB_USER_NOT_AUTHORIZED' => 'el usuario de base de datos indicado no tiene acceso para conectar al servidor de base de datos',
	'LBL_MORE_INFORMATION' => 'Más Información',
	'ERR_INVALID_MYSQL_VERSION' => 'Versión de MySQL no soportada, conecta a un servidor MySQL 4.1.x o superior',
	'ERR_UNABLE_CREATE_DATABASE' => 'No se ha podido crear la base de datos',
	'MSG_DB_ROOT_USER_NOT_AUTHORIZED' => 'Mensaje: El usuario administrador indicado no tiene permiso para crear bases de datos o el nombre de la base de datos tiene caracteres no permitidos. Verifica la configuración de la base de datos',
	'ERR_DB_NOT_FOUND' => 'No se ha podido encontrar la base de datos. Intenta cambiar los datos de acceso',
	'LBL_SUCCESSFULLY_INSTALLED' => 'Instalación Correcta',
	'LBL_DEMO_DATA_IN_PROGRESS' => 'Creando información de demostración',
	'LBL_PLEASE_WAIT' => 'Por favor espera',
	'LBL_ALL_SET_TO_GO' => 'está todo preparado para empezar!',
	'LBL_INSTALL_PHP_FILE_RENAMED' => 'El fichero install.php ha sido renombrado a',
	'LBL_MIGRATE_PHP_FILE_RENAMED' => 'El fichero migrate.php ha sido renombrado a',
	'LBL_INSTALL_DIRECTORY_RENAMED' => 'El directorio install ha sido renombrado a',
	'WARNING_RENAME_INSTALL_PHP_FILE' => 'Por seguridad debes renombrar el fichero install.php',
	'WARNING_RENAME_MIGRATE_PHP_FILE' => 'Por seguridad debes renombrar el fichero migrate.php',
	'WARNING_RENAME_INSTALL_DIRECTORY' => 'Por seguridad debes renombrar el directorio install',
	'LBL_LOGIN_USING_ADMIN' => 'Por favor accede utilizando el usuario "admin" con los datos introducidos en el paso 3/4',
	'LBL_SET_OUTGOING_EMAIL_SERVER' => 'No olvides configurar los datos del servidor saliente de correo, accesible desde Configuración-&gt;Servidor de Correo Saliente',
	'LBL_RENAME_HTACCESS_FILE' => 'Renombra htaccess.txt a .htaccess para controlar el acceso a los ficheros de la aplicación',
	'MSG_HTACCESS_DETAILS' => 'Este fichero .htaccess funcionará si la directiva "<b>AllowOverride All</b>" esta establecida en la configuración de Apache (httpd.conf) para el DocumentRoot o el camino actual de vtiger CRM.<br>
				   				Si la directiva AllowOverride tiene valor None ie., "<b>AllowOverride None</b>" entonces el fichero .htaccess no tendrá efecto.<br>
				   				Si AllowOverride es None añade la siguiente configuración en al servidor apache (httpd.conf) <br>
				   				<b>&lt;Directory "C:/Program Files/vtigercrm/apache/htdocs/vtigerCRM"&gt;<br>Options -Indexes<br>&lt;/Directory&gt;</b><br>
				   				Así se restringe el acceso a los ficheros sin necesidad del fichero .htaccess',
	'LBL_YOU_ARE_IMPORTANT' => 'Eres muy importante para nosotros!',
	'LBL_PRIDE_BEING_ASSOCIATED' => 'Nos enorgullece estar asociado contigo',
	'LBL_TALK_TO_US_AT_FORUMS' => 'Habla con nosotros en <a href="http://forums.vtiger.com" target="_blank">forums</a>',
	'LBL_DISCUSS_WITH_US_AT_BLOGS' => 'Comenta con nosotros en <a href="http://blogs.vtiger.com" target="_blank">blogs</a>',
	'LBL_WE_AIM_TO_BE_BEST' => 'Nuestro objetivo - simplemente el mejor',
	'LBL_SPACE_FOR_YOU' => 'Vente, hay espacio para ti también!',	
	'LBL_NO_OPTIONAL_MODULES_FOUND' => 'No se han encontrado Módulos Opcionales',
	'LBL_PREVIOUS_INSTALLATION_INFORMATION' => 'Información de Instalación Anterior',
	'LBL_PREVIOUS_INSTALLATION_PATH' => 'Camino de la Instalación Anterior',
	'LBL_PREVIOUS_INSTALLATION_VERSION' => 'Versión Instalación Anterior ',
	'LBL_MIGRATION_DATABASE_NAME' => 'Nombre de la base de datos para la Migración',
	'LBL_IMPORTANT_NOTE' => 'Nota Importante',
	'MSG_TAKE_DB_BACKUP' => 'Asegurate de hacer una <b>copia de seguridad (volcado) de la base de datos</b> antes de seguir',
	'QUESTION_MIGRATE_USING_NEW_DB' => 'Migrar utilizando una base de datos nueva',
	'MSG_CREATE_DB_WITH_UTF8_SUPPORT' => 'Crea la base de datos con soporte de codificación UTF8',
	'LBL_EG' => 'eg',
	'MSG_COPY_DATA_FROM_OLD_DB' => '<b>Copia el volcado</b> de la base de datos anterior a esta nueva',
	'LBL_SELECT_PREVIOUS_INSTALLATION_VERSION' => 'Por favor indica la versión de la Instalación anterior',
	'LBL_SOURCE_CONFIGURATION' => 'Configuración Anterior',
	'LBL_OLD' => 'Viejo',
	'LBL_NEW' => 'Nuevo',
	'LBL_INNODB_ENGINE_CHECK' => 'Comprobación motor InnoDB',
	'LBL_FIXED' => 'Corregido',
	'LBL_NOT_FIXED' => 'No Corregido',
	'LBL_NEW_INSTALLATION_PATH' => 'Camino Nueva Instalación',
	'ERR_CANNOT_WRITE_CONFIG_FILE' => 'No se ha podido crear el fichero de configuración (config.inc.php). Comprueba los permisos y reinicia la instalación',
	'ERR_DATABASE_NOT_FOUND' => 'ERR : No se ha encontrado la Base de Datos. Indica un nombre de base de datos correcto',
	'ERR_NO_CONFIG_FILE' => 'La fuente indicada no tiene un fichero de configuración. Por favor indica una fuente correcta',
	'ERR_NO_USER_PRIV_DIR' => 'La fuente indicada no tiene un directorio de user privileges. Por favor indica una fuente correcta',
	'ERR_NO_STORAGE_DIR' => 'La fuente indicada no tiene un directorio de Storage. Por favor indica una fuente correcta',
	'ERR_NO_SOURCE_DIR' => 'La fuente indicada parece no existir. Por favor indica una fuente correcta',
	'ERR_NOT_VALID_USER' => 'No es un usuario válido. Por favor introduce los datos de acceso de un usuario administrador.',
	'ERR_MIGRATION_DATABASE_IS_EMPTY' => 'Esta base de datos está vacía. Por favor copia la información de una base de datos anterior para poder migrar',
	'ERR_NOT_AUTHORIZED_TO_PERFORM_THE_OPERATION' => 'No está autorizado a realizar esta operación',
	'LBL_DATABASE_CHECK' => 'Comprobación base de datos',
	'MSG_TABLES_IN_INNODB' => 'Todas las tablas que han de estar en formato InnoDB, lo están',
	'MSG_CLOSE_WINDOW_TO_PROCEED' => 'Puedes cerrar esta ventana y proceder con la migración',
	'LBL_RECOMMENDATION_FOR_PROPERLY_WORKING_CRM' => 'Para el correcto funcionamiento de vtiger CRM, recomendamos lo siguiente',
	'LBL_TABLES_SHOULD_BE_INNODB' => 'Tablas que han de tener el motor InnoDB',
	'QUESTION_WHAT_IS_INNODB' => '¿Qué es InnoDB?',
	'LBL_TABLES_CHARSET_TO_BE_UTF8' => 'Para conseguir soporte completo de UTF-8, las tablas deben tener la codificación UTF8 por defecto',
	'LBL_FIX_ENGINE_FOR_ALL_TABLES' => 'Arreglar motor para todas las tablas',
	'LBL_TABLE' => 'Tabla',
	'LBL_TYPE' => 'Tipo',
	'LBL_CHARACTER_SET' => 'Codificación',
	'LBL_CORRECT_ENGINE_TYPE' => 'Tipo de Motor Correcto',
	'LBL_FIX_NOW' => 'Arreglar Ahora',
	'LBL_CLOSE' => 'Cerrar',
	'LBL_PRE_MIGRATION_TOOLS' => 'Herramientas premigración',
	'ERR_TABLES_NOT_INNODB' => 'El motor actual de tu base de datos no es el recomendado "Innodb"',
	'MSG_CHANGE_ENGINE_BEFORE_MIGRATION' => 'Por favor asegurate de cambiar el motor antes de la migración',
	'LBL_VIEW_REPORT' => 'Ver Informe',
	'LBL_IMPORTANT' => 'Importante',
	'LBL_DATABASE_BACKUP' => 'Copia Seguridad Base Datos',
	'LBL_DATABASE_COPY' => 'Copia Base Datos',
	'LBL_DB_DUMP_DOWNLOAD' => 'Descargar volcado de Base de Datos',
	'LBL_DB_COPY' => 'Copia Base Datos',
	'QUESTION_NOT_TAKEN_BACKUP_YET' => 'No has hecho una copia de seguridad de la base de datos',
	'LBL_CLICK_FOR_DUMP_AND_SAVE' => '<b>&#171; Pulsa</b> en el icono de la izquierda para iniciar el volcado y <b>Guarda</b> el resultado',
	'LBL_NOTE' => 'Nota',
	'LBL_RECOMMENDED' => 'Recomendado',
	'MSG_PROCESS_TAKES_LONGER_TIME_BASED_ON_DB_SIZE' => 'Este proceso tardará más o menos dependiendo del tamaño de la base de datos',
	'QUESTION_MIGRATING_TO_NEW_DB' => '¿Estás migrando a una nueva base de datos',
	'LBL_CLICK_FOR_NEW_DATABASE' => '<b>&#171; Pulsa</b> en el icono de la izquierda para proceder si no has preparado una nueva base de datos con datos anteriores',
	'MSG_USE_OTHER_TOOLS_FOR_DB_COPY' => 'Utiliza herramientas como (mysql, phpMyAdmin) para preparar una nueva base de datos con información',
	'LBL_COPY_OLD_DB_TO_NEW_DB' => 'Haz una copia de tu base de datos actual para la migración',
	'LBL_IF_DATABASE_EXISTS_WILL_RECREATE' => 'Si una base de datos con el mismo nombre ya existe, será eliminada',
	'LBL_SHOULD_BE_PRIVILEGED_USER' => 'Debe tener privilegios para CREATE DATABASE',
	'ERR_FAILED_TO_FIX_TABLE_TYPES' => 'No ha sido posible arreglar el tipo de las tablas',
	'ERR_SPECIFY_NEW_DATABASE_NAME' => 'Indica el nombre de la nueva base de datos',
	'ERR_SPECIFY_ROOT_USER_NAME' => 'Indica el nombre de usuario administrador',
	'ERR_DATABASE_COPY_FAILED' => '<span class="redColor">Error</span> creando copia de base de datos, por favor hazla manualmente',
	'MSG_DATABASE_COPY_SUCCEDED' => 'Copia de base de datos correcta.<br />Pulsa Siguiente &#187; para seguir',
	'MSG_SUCCESSFULLY_FIXED_TABLE_TYPES' => 'Tablas cambiadas a motor InnoDB correctamente',
	'LBL_MIGRATION' => 'Migración',
	'LBL_SOURCE_VERSION_NOT_SET' => 'Versión anterior no establecida. Por favor comprueba vtigerversion.php y continua el proceso de migración',
	'LBL_GOING_TO_APPLY_DB_CHANGES' => 'Iniciando cambios en la base de datos',
	'LBL_DATABASE_CHANGES' => 'Cambios en Base de Datos',
	'LBL_STARTS' => 'Empieza',
	'LBL_ENDS' => 'Termina',
	'LBL_SUCCESS' => 'EXITO',
	'LBL_FAILURE' => 'FRACASO',
	'LBL_MIGRATION_FINISHED' => 'Migración Completada Correctamente',
	'LBL_OLD_VERSION_IS_AT' => 'La versión anterior está disponible en : ',
	'LBL_CURRENT_SOURCE_PATH_IS' => 'El camino del código es : ',
	'LBL_DATABASE_EXTENSION' =>'Extensión Base de Datos',
	'LBL_DOCUMENTATION_TEXT' => 'Documentación, incluiendo el Manual del Usuario, puede obtenerse en',
    'LBL_USER_PASSWORD_CHANGE_NOTE' => 'contraseñas de todos los usuarios se cambiará al nombre del usuario. Por favor, informa a todos los usuarios y que procedan a cambiar su contraseña',
	'LBL_PASSWORD_FIELD_CHANGE_FAILURE' => "no se ha podido cambiar el campo de contraseña",
	'LBL_OPENSSL_SUPPORT' => 'Soporte de OpenSSL',
	'LBL_OPTIONAL_MORE_LANGUAGE_PACK' => 'Paquetes de idioma adicionales se pueden obtener en',
	'LBL_GETTING_STARTED' => 'Primeros pasos:',
	'LBL_GETTING_STARTED_TEXT' => 'Puedes empezar a utilizar tu CRM ahora.',
	'LBL_YOUR_LOGIN_PAGE' => 'La página de acceso:',
	'LBL_ADD_USERS' => 'Para añadir más usuarios, accede a la página de Configuración.',
	'LBL_SETUP_BACKUP' => "No olvides configurar el 'Servidor de Copias' para mantener un copia de la información y los ficheros de tu CRM en otra ubicación regularmente. ",
	'LBL_RECOMMENDED_STEPS' => 'Pasos Recomendados:',
	'LBL_RECOMMENDED_STEPS_TEXT' => 'Es importante que completes los siguientes pasos',
	'LBL_DOCUMENTATION_TUTORIAL' => 'Documentación y Tutorial',
	'LBL_WELCOME_FEEDBACK' => 'Agradecemos tus comentarios',
	'LBL_TUTORIAL_TEXT' => 'Hay tutoriales en vídeo disponibles en',
	'LBL_DROP_A_MAIL' => 'Mándanos un email a',
	'LBL_LOGIN_PAGE' => 'La página de acceso: ',
);
?>
