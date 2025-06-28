<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class ClientesControlador
{
    public function create($datos)
    {

        //echo "<pre>"; print_r($datos);echo"<prev>";
        if(isset($datos["nombre"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/", $datos["nombre"]))
        {
            $Json = array(
                "detalle"=>"error en el campo del nombre, permitido solo letras"
            );
            echo json_encode($Json, true);
            return;

        }
        //validar si el apellido son letras
         if(isset($datos["apellido"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/", $datos["apellido"]))
        {
            $Json = array(
                "detalle"=>"error en el campo del apellido, permitido solo letras"
            );
            echo json_encode($Json, true);
            return;

        }
        //Validar el email
        if(isset($datos["email"]) && !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $datos["email"]))
        {

            $Json = array(
                "detalle"=>"error en el campo del correo, se permite unicamente una direccion de correo"
            );
            echo json_encode($Json, true);
            return;

        }
        //Validar email repetido
        $clientes = ModeloClientes::index("clientes");

        foreach($clientes as $Key => $value)
        {
            if($value["email"]==$datos["email"])
            {
                $json = array 
                (
                    "status"=>404,
                    "detalle"=>"El email esta repetido"
                );
                echo json_encode($json, true); 
            }
            //echo json_encode($json, true);
            //return;

        }

        //Generar las credenciales del cliente

        $salt = '$2y$10$1234567890123456789012'; // 22 caracteres válidos

        $id_cliente = str_replace("$", "c", crypt(
            $datos["nombre"] . $datos["apellido"] . $datos["email"],
            $salt
        ));
        //echo "<pre>"; print_r($id_cliente); echo "</pre>";

        $llave_secreta = str_replace("$", "c", crypt(
            $datos["email"] . $datos["apellido"] . $datos["nombre"],
            $salt
        ));
        //echo "<pre>"; print_r($llave_secreta); echo "</pre>";
        $datos=array("nombre"=>$datos["nombre"],
                        "apellido"=>$datos["apellido"],
                        "email"=>$datos["email"],
                        "id_cliente"=>$id_cliente,
                        "llave_secreta"=>$llave_secreta,
                        "created_at"=>date('Y-m-d h:i:s'),
                        "updated_at"=>date('Y-m-d h:i:s')
                    );

        $create = ModeloClientes::create("clientes",$datos);
        if($create=="OK")
        {
            $json = array 
                (
                    "status"=>404,
                    "detalle"=>"Se genero su contraseña",
                    "id_cliente"=> $id_cliente,
                    "Llave_secreta"=> $llave_secreta
                );
                echo json_encode($json, true); 
                return;
        }
    }
}



?>