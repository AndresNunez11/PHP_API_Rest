<?php

require_once "conexion.php";

class ModeloCursos
{
    static public function index($tabla, $tabla2, $cantidad, $desde)
    {
        if($cantidad != null)
        {
           $stmt = conexion::conectar()->prepare("SELECT $tabla.id, $tabla.titulo, $tabla.descripcion, $tabla.instructor, 
                                                            $tabla.imagen, $tabla.precio, $tabla.id_creador, $tabla2.nombre, $tabla2.apellido
                                                            FROM $tabla INNER JOIN $tabla2 on $tabla.id_creador = $tabla2.id LIMIT $desde, $cantidad"); 
        }
        else
        {
            //$stmt = conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt = conexion::conectar()->prepare("SELECT $tabla.id, $tabla.titulo, $tabla.descripcion, $tabla.instructor, 
                                                            $tabla.imagen, $tabla.precio, $tabla.id_creador, $tabla2.nombre, $tabla2.apellido
                                                            FROM $tabla INNER JOIN $tabla2 on $tabla.id_creador = $tabla2.id");

        }
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt->closeCursor(); // mejor que close() para PDO
        $stmt = null;
        return $resultado;

    }

    static public function create($tabla, $datos)
    {
        $stmt = conexion::conectar()->prepare("INSERT INTO $tabla( titulo, descripcion, instructor, imagen, precio,
        id_creador, created_at, updated_at) 
        VALUES (:titulo, :descripcion, :instructor, :imagen, :precio, :id_creador, :created_at, :updated_at)");

        $stmt->bindParam(":titulo", $datos["titulo"],PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"],PDO::PARAM_STR);
        $stmt->bindParam(":instructor", $datos["instructor"],PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos["imagen"],PDO::PARAM_STR);
        $stmt->bindParam(":precio", $datos["precio"],PDO::PARAM_STR);
        $stmt->bindParam(":id_creador", $datos["id_creador"],PDO::PARAM_STR);
        $stmt->bindParam(":created_at", $datos["created_at"],PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $datos["updated_at"],PDO::PARAM_STR);

        if($stmt->execute())
        {
            return "OK";
        }else
        {
            print_r(conexion::conectar()->errorInfo());
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    static public function show($tabla,$tabla2, $id)
    {
        //echo $tabla."//".$id;
       $stmt = conexion::conectar()->prepare("SELECT $tabla.id, $tabla.titulo, $tabla.descripcion, $tabla.instructor, 
                                                        $tabla.imagen, $tabla.precio, $tabla.id_creador, $tabla2.nombre, $tabla2.apellido
                                                        FROM $tabla INNER JOIN $tabla2 on $tabla.id_creador = $tabla2.id WHERE $tabla.id=:id");
       $stmt->bindParam(":id", $id,PDO::PARAM_INT);
       $stmt->execute();
       $resultado = $stmt->fetchAll(PDO::FETCH_CLASS);
       $stmt->closeCursor(); // mejor que close() para PDO
       $stmt = null;
       return $resultado; 

    }

    static public function update($tabla, $datos)
    {
        $stmt = conexion::conectar()->prepare("UPDATE $tabla 
                                    SET titulo=:titulo, descripcion= :descripcion,
                                    instructor=:instructor, imagen=:imagen, precio=:precio,
                                    updated_at=:updated_at WHERE id=:id");

        $stmt->bindParam(":id", $datos["id"],PDO::PARAM_STR);
        $stmt->bindParam(":titulo", $datos["titulo"],PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"],PDO::PARAM_STR);
        $stmt->bindParam(":instructor", $datos["instructor"],PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos["imagen"],PDO::PARAM_STR);
        $stmt->bindParam(":precio", $datos["precio"],PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $datos["updated_at"],PDO::PARAM_STR);
         if($stmt->execute())
        {
            return "OK";
        }else
        {
            print_r(conexion::conectar()->errorInfo());
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    static public function delete($tabla, $id)
    {
       $stmt = conexion::conectar()->prepare("DELETE FROM $tabla WHERE id=:id");
       $stmt->bindParam(":id", $id,PDO::PARAM_INT);
        if($stmt->execute())
        {
            return "OK";
        }else
        {
            print_r(conexion::conectar()->errorInfo());
        }
        $stmt->closeCursor();
        $stmt = null;

    }




}

?>