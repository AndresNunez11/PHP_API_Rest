<?php

require_once "conexion.php";

class ModeloClientes
{
    //mostrar todos los clientes
    static public function index($tabla)
    {
        $stmt = conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $stmt->closeCursor(); // mejor que close() para PDO
        $stmt = null;
        return $resultado;

    }
    static public function create($tabla, $datos)
    {
       $stmt = conexion::conectar()->prepare("INSERT INTO $tabla ( primer_nombre, primer_apellido, email, id_cliente, llave_secreta, created_at, updated_at) 
                                                        VALUES ( :nombre, :apellido, :email, :id_cliente, :llave_secreta, :created_at, :updated_at);"); 
        
        $stmt->bindParam(":nombre",$datos["nombre"],PDO::PARAM_STR);
        $stmt->bindParam(":apellido",$datos["apellido"],PDO::PARAM_STR);
        $stmt->bindParam(":email",$datos["email"],PDO::PARAM_STR);
        $stmt->bindParam(":id_cliente",$datos["id_cliente"],PDO::PARAM_STR);
        $stmt->bindParam(":llave_secreta",$datos["llave_secreta"],PDO::PARAM_STR);
        $stmt->bindParam(":created_at",$datos["created_at"],PDO::PARAM_STR);
        $stmt->bindParam(":updated_at",$datos["updated_at"],PDO::PARAM_STR);

        if($stmt->execute())
        {
            return "OK";
        }else
        {
            print_r(conexion::conectar()->errorInfo());
        }
        $stmt->closeCursor(); // mejor que close() para PDO
        $stmt = null;

    }

}

?>