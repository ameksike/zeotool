<?php
/*
 * @author      $author
 * @package    	$package
 * @made        $made
 * @update      $update
 * @copyright  	Copyright (c) ZeoTool
 * @license    	GPL
 * @version    	$version
 * @description $description
 */
class $name extends ZtModule
{
    public function __construct()
    {
		/*
		 * Extender el comportamiento de clases como ZtModule permite la
		 * generacion automatica de la ayuda con suporte de idiomas "idiom/"
		 * esto es muy comodo para entornos o ambientes en modo CLI.
		 */
		/*
		 *  El modo verbose permite mostrar informacion adicional al usuario,
		 *  en aras de mejorar su esperiencia y comprencion del sistema,
		 *  se considera determinante para el uso del recurso ZtPrompt (show, log, etc).
		 */
        Zt::setVerbose(false);
		/*
		 *  Si se especifica el modo verbose en el constructur se establece entonces
		 *  de forma global para todas las funciones del modulo, aunque tambien
		 *  se puede especificar para cada funcion en particular,
		 *  por defecto se asume el valor true,
		 *  el valor verbose en "true" es aconsejable para entornos CLI, por el contrario
		 *  el valor verbose en "false" se aconseja para entornos WEB.
		 */
    }

    public function getParams(\$name = "maria", \$age = 12)
    {
		/*
		 * los parametros corresponden al mismo orden en que se
		 * especifican en la consola a la hora de ejecutar el servicio
		 * ej:
		 *      bin/zt democli:getParams "Tieso Manso" 33"
		 * 		public function getParams(\$name = "maria", \$age = 12)
		 * 		\$name <===> "Tieso Manso"  y \$age <===>  33
         **/
        ZtPrompt::show("Ej: bin/zt democli:getParams 'Tieso Manso' 33 ");
        ZtPrompt::show("El nombre es: \$name ");
        ZtPrompt::show("La edad es: \$age ");
    }

    public function returnValue(\$name = "maria", \$age = 12)
    {
        return array(
            "name" => \$name,
            "age" => \$age
        );
    }

    public function interactive()
    {
        /*
        * El recurso ZtPrompt o garantiza al desarrollador una interactividad con el usuario,
        * muy util para entornos en modo CLI
        * a traves de ZtPrompt::get se optienen las entradas del usuario,
        * a traves de ZtPrompt::show se muestra informacion al usuario,
        * a traves de ZtPrompt::password implementa un Prompt de captura de contrasenas,
        * a traves de ZtPrompt::newPassword implementa un Prompt de captura de contrasenas y la rectifica.
        * */
        \$name = ZtPrompt::get("Especifique el nombre de susuario", true);
        ZtPrompt::show("El nombre del usuario es: '\$name', este campo es obligatorio.");
        \$apll = ZtPrompt::get("Especifique los apellidos del usuario");
        ZtPrompt::show("Los apellidos del usuario son: '\$apll', este campo no es obligatorio y no toma un valor por defecto.");
        \$age = ZtPrompt::get("Especifique la edad del usuario", "12");
        ZtPrompt::show("La edad del usuario es: '\$age', este campo no es obligatorio y su valor por defecto es '12'.");
        \$sex = ZtPrompt::get("Especifique el sexo del usuario", 'M', array("M", "F"));
        ZtPrompt::show("El sexo del usuario es: '\$sex', este campo no es obligatorio y su valor por defecto es 'M', los valores permitidos son [M,F].");
        return "El resultado es: \$name \$apll, \$age, \$sex.";
    }


    public function prompt()
    {
        Zt::setVerbose(true);
        \$newpass = ZtPrompt::newPassword();
        ZtPrompt::show(\$newpass);
        \$getpass = ZtPrompt::password();
        ZtPrompt::show(\$getpass);
    }

    public function confirm()
    {
        Zt::setVerbose(true);
        if (ZtPrompt::confirm("Entiende usted esta pregunta? ", true)) {
            ZtPrompt::show("Usted especifico que Si");
        } else {
            ZtPrompt::show("Usted especifico que No");
        }
    }

    public function metaInfo()
    {
        \$mildap = ZtMeta::lib("ldap");
        ZtPrompt::show(\$mildap);
        \$mithis = ZtMeta::lib(\$this);
        ZtPrompt::show(\$mithis);
    }

    public function msgIdiom()
    {
        /*
        * cada modulo tiene acceso a la propiedad "idiom", la cual
        * consiste en un arreglo asociativo con los elementos descritos
        * en el fichero idiom/[lenguaje].json de cada modulo,
        * por defecto [lenguaje] asume valor "es" que corresponde al idioma Espanol
        * cabe destacar que idiom/[lenguaje].json del proyecto sobrescribe al modulol
        * */
        ZtPrompt::show(\$this->idiom["msg"]["simple"]);
    }

    public function msgConfig()
    {
        /*
        * cada modulo tiene acceso a la propiedad "cfg", la cual
        * consiste en un arreglo asociativo con los elementos descritos
        * en el fichero cfg/config.json del modulo y sobrescrito por
        * cfg/config.json del proyecto de forma general
        * */
        ZtPrompt::show(\$this->idiom["msg"]["dbname"] . \$this->cfg["mydb"]["name"]);
        return \$this->cfg["mydb"];
    }


    public function pathinfo()
    {
        ZtPrompt::show("Direccion hasta el modulo ldap:	" . Zt::path("ldap"));
        ZtPrompt::show("Direccion hasta el modulo actual:	" . Zt::path(\$this));
        ZtPrompt::show("Direccion hasta el modulo actual:	" . \$this->path);
        ZtPrompt::show("Direccion hasta el dir lib:	" . Zt::path());
        ZtPrompt::show("Direccion hasta la raiz del proy:	" . \$this->root);
    }

    public function noVerboseInfo()
    {
        Zt::setVerbose(false);
        return '[ "mydb1", "mydb2", "mydb3" ]';
    }

    public function debugSimple()
    {
        \$data = json_decode('[ "mydb1", "mydb2", "mydb3" ]', true);
        Zt::debug(\$data);
        ZtPrompt::show("Continuacion... ");
    }

    public function debugTrace()
    {
        \$data = json_decode('[ "mydb1", "mydb2", "mydb3" ]', true);
        Zt::debug(\$data, true);
        ZtPrompt::show("Continuacion... ");
    }

    public function debugTraceArgs()
    {
        \$data = json_decode('[ "mydb1", "mydb2", "mydb3" ]', true);
        Zt::debug(\$data, "args");
        ZtPrompt::show("Continuacion... ");
    }

    public function debugExtend()
    {
        \$data = json_decode('[ "mydb1", "mydb2", "mydb3" ]', true);
        Zt::debug(\$data, false, true);
        ZtPrompt::show("Continuacion... ");
    }

    public function debugNoBreak()
    {
        \$data = json_decode('[ "mydb1", "mydb2", "mydb3" ]', true);
        Zt::debug(\$data, "trace", false, false);
        ZtPrompt::show("Continuacion... ");
    }


    public function useCompile()
    {
        /*
        * esta recurso permite compilar un texto en forma de plantilla
        * especificandose por parametro las variables
        **/
        return Zt::compile("Esto es una \\\$inf de \\\$data...", array( "data"  => "EJEM", "inf"=>"pruv" ));
    }

    public function useTPL()
    {
        /*
        * se compila la plantilla ejemplo1 ubicada en el directorio "src/template/"
        * del modulo denominado democli, pasandole por parametro las variables "count", "result" y "label"
        * */
        return Zt::tpl("ejemplo1", \$this->path . "/src/template/", array(
            "count"  => 1234,
            "result" => 5555,
            "label" => "EJEMPLO",
        ));
    }

    public function useLib(){
        /*
        * De esta forma se puede utilizar otra funcionalidad implementada en otra biblioteca o modulo
        * */
        return Zt::lib("projmin")->modules();
    }

    public function useService(){
        /*
        * De esta forma se puede utilizar el mecanismo de ejecucion de servicios de ZeoTool
        * es una forma similar de lograr el mismo objetivo que la funcion useLib.
        * */
        return Zt::excecute( "democli", "getParams", array( "name" => "EJEM", "age" => 15 ) );
    }
}