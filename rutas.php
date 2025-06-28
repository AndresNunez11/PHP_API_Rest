<?php

$arrayRutas = explode("/", $_SERVER['REQUEST_URI']); 

//prueba de impresion arreglo de URL
//echo "<pre>"; print_r($arrayRutas);echo"<prev>";

/*$Json = array (
    "detalle"=> "No encontrado"
);

echo json_encode($Json, true);*/


if(isset($_GET["pagina"]) && is_numeric($_GET["pagina"]))
{
     $cursos = new CursosControlador();
     $cursos -> index($_GET["pagina"]);

}else
{
    if(count(array_filter($arrayRutas)) == 2) 
    {
        $Json = array(
            "detalle"=>"No encontrado"
        );
        echo json_encode($Json, true);

        return;
    } 
    else 
    {
        if(count(array_filter($arrayRutas)) == 3) 
        {
            // cuando se hace una peticion y se envia en la URL la palabra cursos en el indice 3
            if(array_filter($arrayRutas)[3]=="cursos")
            {
                // Se evaliuda el metodo por el cual se hace el llamado
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST")
                {
                    //apturar los datos
                    $datos = array(
                        "titulo"=>$_POST["titulo"],
                        "descripcion"=>$_POST["descripcion"],
                        "instructor"=>$_POST["instructor"],
                        "imagen"=>$_POST["imagen"],
                        "precio"=>$_POST["precio"]);
                    //Validar que la informacion se esta enviando
                    //echo "<prev>"; print_r($datos); echo "<prev>";
                    //return;
                $cursos = new CursosControlador();
                $cursos -> create($datos);
                } 
                
                else if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET")
                {
                $cursos = new CursosControlador();
                $cursos -> index(null);
                }
            }

            // cuando se hace una peticion y se envia en la URL la palabra register en el indice 3
            else if(array_filter($arrayRutas)[3]=="registro")
            {
                // Se evaliuda el metodo por el cual se hace el llamado
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST")
                {
                    $datos= array("nombre"=>$_POST["nombre"],
                    "apellido"=>$_POST["apellido"],
                    "email"=>$_POST["email"] );

                    //echo "<pre>"; print_r($datos);echo"<prev>";

                $clientes = new ClientesControlador();
                $clientes -> create($datos);
                }
            }

        }
        
        else 
        {
            if(array_filter($arrayRutas)[3]=="cursos" && is_numeric(array_filter($arrayRutas)[4]) )
            {
                
                // Metodo get valida si se agreag un id de curso
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET")
                {
                    $cursos = new CursosControlador();
                    $cursos -> show(array_filter($arrayRutas)[4]);
                }
                
                //Metodo put para subir informacion
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT")
                {
                    //Capturar los tados

                    $datos = array();
                    parse_str(file_get_contents('php://input'),$datos); // captura los datos que se enstan enviando en el formulario
                    $editarcurso = new CursosControlador();
                    $editarcurso -> update(array_filter($arrayRutas)[4], $datos);
                }

                //Metodo delete para borrar informacion
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE")
                {
                    $borrarcurso = new CursosControlador();
                    $borrarcurso -> delete(array_filter($arrayRutas)[4]);
                }


            }    
        }

    }
}

?>