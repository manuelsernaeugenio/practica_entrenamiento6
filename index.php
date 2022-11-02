<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        input {
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <?php

    /***********************************************
    Función que valida el nombre y el apellid con un custom callback.    
     */
    function validarString($dato)
    {
        if (mb_strlen($dato, 'UTF-8') <= 0) {
            return false;
        }

        if (count(explode(' ', $dato)) == 0 || count(explode(' ', $dato)) > 2) {
            return false;
        }

        return $dato;
    }

    /*******************************************************
     * Función para quitar símbolos y carácteres no válidos.
     */
    function sanitize($post)
    {
        return htmlentities(trim($post));
    }

    $imprimirFormulario = true;
    $datos = [];

    /****************************************************************
     * Al enviar el post, hacemos las comprobaciones de los campos.    
     */

    if ($_POST) {

        // Le pasaremos este array de opciones a otro array para validar los datos.
        $argumentos = array(
            'nombre' => array(
                'filter' => FILTER_CALLBACK,
                'options' => 'validarString'
            ),
            'apellidos' => array(
                'filter' => FILTER_CALLBACK,
                'options' => 'validarString'
            ),
            'altura' => array(
                'filter' => FILTER_VALIDATE_FLOAT,
                "options" => array("min_range" => 0.5, "max_range" => 2.5)
            ),
            'edad' => array(
                'filter' => FILTER_VALIDATE_INT,
                "options" => array("min_range" => 0)
            )
        );

        // Array que contiene los datos ya validados.
        $datos = [
            'nombre' => sanitize(array_key_exists('nombre', $_POST) ? $_POST['nombre'] : ""),
            'apellidos' => sanitize(array_key_exists('apellidos', $_POST) ? $_POST['apellidos'] : ""),
            'edad' => sanitize(array_key_exists('edad', $_POST) ? $_POST['edad'] : ""),
            'altura' => sanitize(array_key_exists('altura', $_POST) ? $_POST['altura'] : "")
        ];

        $validaciones = filter_var_array($datos, $argumentos);

        // Si todos los datos son válidos, imprimimos los datos y ocultamos el formulario.
        if ($validaciones['nombre'] && $validaciones['apellidos'] && $validaciones['edad'] && $validaciones['altura']) {
            $imprimirFormulario = false;
            echo "<br><span style='color:green;'><b>Nombre</b>: {$_POST['nombre']}</span><br>";
            echo "<br><span style='color:green;'><b>Apellidos</b>: {$_POST['apellidos']}</span><br>";
            echo "<br><span style='color:green;'><b>Edad</b>: {$_POST['edad']}</span><br>";
            echo "<br><span style='color:green;'><b>Altura</b>: {$_POST['altura']}</span><br>";
        }
    }

    // Mostramos el formulario si los datos no son válidos aun junto a sus errores.
    if ($imprimirFormulario) {
    ?>
        <form action="#" method="post">
            <p>
                <input placeholder="Nombre" value="<?= array_key_exists('nombre', $datos) ? $datos['nombre'] : "" ?>" type="text" name="nombre" id="nombre">
                <?php
                if ($_POST) {
                    if ($validaciones['nombre'] === false) {
                        echo "<br><span style='color:red;'>El nombre {$datos['nombre']} no es válido.</span><br>";
                    }
                }
                ?>
            </p>
            <p>
                <input placeholder="Apellidos" value="<?= array_key_exists('apellidos', $datos) ? $datos['apellidos'] : "" ?>" type="text" name="apellidos" id="apellidos">
                <?php
                if ($_POST) {
                    if ($validaciones['apellidos'] === false) {
                        echo "<br><span style='color:red;'>El apellido {$datos['apellidos']} no es válido.</span><br>";
                    }
                }
                ?>
            </p>
            <p>
                <input placeholder="Edad" value="<?= array_key_exists('edad', $datos) ? $datos['edad'] : "" ?>" type="text" name="edad" id="edad">
                <?php
                if ($_POST) {
                    if ($validaciones['edad'] === false) {
                        echo "<br><span style='color:red;'>El edad {$datos['edad']} no es válido.</span><br>";
                    }
                }
                ?>
            </p>
            <p>
                <input placeholder="Altura" value="<?= array_key_exists('altura', $datos) ? $datos['altura'] : "" ?>" type="text" name="altura" id="altura">
                <?php
                if ($_POST) {
                    if ($validaciones['altura'] === false) {
                        echo "<br><span style='color:red;'>La altura {$datos['altura']} no es válida.</span><br>";
                    }
                }
                ?>
            </p>
            <p>
                <input type="submit" value="Enviar">
            </p>
        </form>
    <?php } ?>
</body>

</html>