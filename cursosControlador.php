<?php

class CursosControlador
{
    public function  index($pagina)
    {

    //$user = $_SERVER['PHP_AUTH_USER'];
    //$pass = $_SERVER['PHP_AUTH_USER'];
        $clientes = ModeloClientes::index("clientes");

        
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
           
            foreach($clientes as $key => $value)
            {
                

                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($value["id_cliente"].":".$value["llave_secreta"]))
                    {
                        if($pagina != null)
                        {
                            $cantidadRegistros = 10;
                            $desde = ($pagina-1)*$cantidadRegistros; // (1-1)*10=0 muestra desde l 0 hasta el 10

                            $curso = ModeloCursos::index("cursos","clientes",$cantidadRegistros, $desde);
                        } else 
                        {
                            $curso = ModeloCursos::index("cursos","clientes", null, null);
                        }

                      // $curso = ModeloCursos::index("cursos","clientes");

                        $Json = array
                            (
                            "status"=>200,
                            "Total Registros"=>count($curso),
                            "detalle"=> $curso,
                            );
                        echo json_encode($Json, true);
                        return;
                    }
                    
            }
        }
    }

    public function create($datos)
    {
        //validar las credenciales del cliente

         $clientes = ModeloClientes::index("clientes");

        
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
           
            foreach($clientes as $key => $valueclientes)
            {
                

                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueclientes["id_cliente"].":".$valueclientes["llave_secreta"]))
                {
                    foreach($datos as $key => $valuedatos)
                    {
                      if (isset($valueDatos) && 
                      !preg_match('/^[(\)|\=|\&|\$|;|\-|_|\*|"|<|>|\?|¡|!|¿|:|,|.|\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/',
                      $valueDatos)) 
                       {
                            $json = array(
                                'status'=> 404,
                                "detalle"=>"Error en el campo".$key
                            );
                            echo json_encode($json, true);
                            return;
                        }
                    }
                       //Validar que el titulo o descripcion no esten repeditos
                    $cursos = ModeloCursos::index("cursos", "clientes", null, null);
                    foreach($cursos as $key => $cursosvalue)
                    {
                        if($cursosvalue->titulo ==$datos["titulo"])
                        {
                            $json = array(
                                "status"=> 400,
                                "detalle"=> "El titulo ya existe"
                                );
                            echo json_encode($json, true);
                            return;
                        } 
                        if($cursosvalue->descripcion ==$datos["descripcion"])
                        {
                            $json = array(
                                "status"=> 400,
                                "detalle"=> "la descripcion ya existe"
                            );
                            echo json_encode($json, true);
                            return;
                        }                            
                    }
                       //Datos se llevan a modelo
                    $datos = array
                    (
                        "titulo"=>$_POST["titulo"],
                        "descripcion"=>$_POST["descripcion"],
                        "instructor"=>$_POST["instructor"],
                        "imagen"=>$_POST["imagen"],
                        "precio"=>$_POST["precio"],
                        "id_creador"=> $valueclientes["id"],
                        "created_at"=> date ("Y-m-d H:i:s"),
                        "updated_at"=> date ("Y-m-d H:i:s") 
                    );
                    $create = ModeloCursos::create("cursos",$datos);

                    //Respuesta del modelo
                    if($create == "OK")
                    {
                        $json = array(
                            "status"=> 200,
                            "detalle"=> "Registro exitoso, su curso ha sido guardado"
                        );
                    }
                    echo json_encode($json, true);
                    return;
                }                    
            }
        }
    }
    public function show($id)
    {
       //Validar las credenciales del cliente
       $clientes = ModeloClientes::index("clientes");
       if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            foreach($clientes as $key => $valueclientes)
            {
                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueclientes["id_cliente"].":".$valueclientes["llave_secreta"]))
                {
                    //Para mostrar los cursos
                    $curso = ModeloCursos::show("cursos","clientes", $id);
                    if(!empty($curso))
                    {
                        $json = array(
                        "status"=> 200,
                        "detalle"=> $curso
                        );
                        echo json_encode($json, true);
                        return;
                    }else 
                    {
                        $json = array(
                        "status"=> 200,
                        "Total de registros"=>0,
                        "detalle"=> "No hay ningun curso asignado al numero de id"
                        );
                        echo json_encode($json, true);
                        return;
                    }                    
                }
            }

        }

    }
    public function update($id, $datos)
    {
       //Validar las credenciales del cliente
       $clientes = ModeloClientes::index("clientes");
       if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            foreach($clientes as $key => $valorclientes)
            {
                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valorclientes["id_cliente"].":".$valorclientes["llave_secreta"]))
                {
                    foreach($datos as $key => $valuedatos)
                    {
                      if (isset($valueDatos) && 
                      !preg_match('/^[(\)|\=|\&|\$|;|\-|_|\*|"|<|>|\?|¡|!|¿|:|,|.|\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/',
                      $valueDatos)) 
                       {
                            $json = array(
                                'status'=> 404,
                                "detalle"=>"Error en el campo".$key
                            );
                            echo json_encode($json, true);
                            return;
                        }
                    }
                    // validar el id del creador del curso
                    $curso = ModeloCursos::show("cursos","clientes", $id);
                    foreach($curso as $key => $valorcurso)
                    {
                       if($valorcurso->id_creador == $valorclientes["id"])
                       {
                        //Levvar datos al modelo
                            $datos=array(
                                "id"=>$id,
                                "titulo"=> $datos["titulo"],
                                "descripcion"=> $datos["descripcion"],
                                "instructor"=> $datos["instructor"],
                                "imagen"=> $datos["imagen"],
                                "precio"=> $datos["precio"],
                                "updated_at"=> date("Y-m-d H:i:s")
                            );
                            $update = ModeloCursos::update("cursos",$datos);
                            if($update=="OK")
                            {
                                 $json = array(
                                'status'=> 200,
                                "detalle"=>"Actualizacion exitosa"
                                );
                                echo json_encode($json, true);
                                return;
                            }else
                            {
                                $json = array(
                                'status'=> 200,
                                "detalle"=>"No estas autorizado para actualizar el curso"
                                );
                                echo json_encode($json, true);
                                return;
                            }
                       } 
                    }
                }
            }
        }
    }

    public function delete($id)
    {
        $clientes = ModeloClientes::index("clientes");
       if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            foreach($clientes as $key => $valorclientes)
            {
                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valorclientes["id_cliente"].":".$valorclientes["llave_secreta"]))
                {
                     $curso = ModeloCursos::show("cursos", "clientes",$id);
                     foreach($curso as $key => $valorCurso)
                     {
                        if($valorCurso->id_creador == $valorclientes["id"])
                        {
                            $delete = ModeloCursos::delete("cursos", $id);
                            if($delete=="OK")
                            {
                                 $json = array(
                                'status'=> 200,
                                "detalle"=>"Se elimino el curso correctamente"
                                );
                                echo json_encode($json, true);
                                return;
                            }else
                            {
                                $json = array(
                                'status'=> 200,
                                "detalle"=>"No estas autorizado para borrar el curso"
                                );
                                echo json_encode($json, true);
                                return;
                            }
                        }
                     }

                }
            }
        }
    }
}
?>