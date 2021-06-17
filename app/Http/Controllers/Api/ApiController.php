<?php

namespace App\Http\Controllers\Api;

use App\Diccionario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Metodo usado como servicio, compara el diccionario con la palabra solicitada
     * Logica general algoritmo:
     * @param Request $request parametros de entrada del servicio (nombre y porcentaje de coincidencia)
     * @return response JSON de respuesta con los resultados encontrados
     * @author  alejandro.carvajal <alejo.carvajal03@gmail.com>
     */
    public function findName(Request $request)
    {
        $request_percent = $request->porcentaje;
        $resultado = [];
        $names = Diccionario::all();
        $request_name = mb_strtolower($request->nombre, 'UTF-8'); //Se convierte el nombre a minusculas
        $request_name = $this->removeAccents($request_name);// Se quitan los acentos
        $request_name = explode(" ", $request_name);// Se separa nombre y apellido

        foreach ($names as $name) {
            $nombre = mb_strtolower($name->nombre, 'UTF-8');
            $nombre = $this->removeAccents($name);
            $percent = $this->findSimilar($request_name, $nombre);
            if ($percent >= $request_percent) {
                $resultado[] = [
                    "nombre" => $name->nombre,
                    "porcentaje" => $percent,
                    "departamento" => $name->departamento,
                    "localidad" => $name->localidad,
                    "municipio" => $name->municipio,
                    "anios_activo" => $name->anios_activo,
                    "tipo_persona" => $name->tipo_persona,
                    "tipo_cargo" => $name->tipo_cargo,
                ];
            }
        }
        /*$nombre = mb_strtolower("Alejandro Carvajal", 'UTF-8');
        $nombre = $this->removeAccents($nombre);
        $percent = $this->findSimilar($request_name, $nombre)*/
        if (count($resultado) >= 1) {
            $mensaje = 'registros encontrados';
        } else {
            $mensaje = 'sin coincidencia';
        }
        $respuesta = [
            'nombre_buscado' => $request->nombre,
            "porcentaje_buscado" => $request_percent,
            "registros_encontrados" => count($resultado),
            "resultados" => $resultado,
            "estado_ejecucion" => $mensaje,
        ];
        return response()->json($respuesta);
    }

    /**
     * Metodo para lanzar las validaciones para determinar el porcentaje mas alto encontrado
     * @param Array $request_name arreglo de nombre y apellido a comparar
     * @param String $name nombre de la bd a comparar
     * @return float porcentaje de similitud
     * @author  alejandro.carvajal <alejo.carvajal03@gmail.com>
     */
    private function findSimilar($request_name, $name)
    {
        $comparePercent[] = $this->compareSimilar($request_name, $name);
        $excepciones = [
            'v' => 'b',
            'b' => 'v',
            's' => 'z',
            'z' => 's',
            'j' => 'g',
            'g' => 'j'
        ];
        foreach ($excepciones as $key => $value) {
            $comparePercent[] = $this->compareSimilarReplaceException($request_name, $name, $key, $value);
        }

        $porcentaje = max($comparePercent);

        return $porcentaje;
    }

    /**
     * Metodo para comparar la cadena ingresada en el servicio contra el nombre en bd
     * @param Array $request_name arreglo de nombre y apellido a comparar
     * @param String $name nombre de la bd a comparar
     * @return float porcentaje de similitud
     * @author  alejandro.carvajal <alejo.carvajal03@gmail.com>
     */
    private function compareSimilar($request_name, $name)
    {
        if (count($request_name) > 1) {
            $name_last = $request_name[0] . ' ' . $request_name[1];
            $last_name = $request_name[1] . ' ' . $request_name[0];
            similar_text($name_last, $name, $porcentaje_i_nombre);
            similar_text($last_name, $name, $porcentaje_i_apellido);
            $porcentaje = $porcentaje_i_nombre >= $porcentaje_i_apellido ? $porcentaje_i_nombre : $porcentaje_i_apellido;
        } else {
            similar_text($request_name[0], $name, $porcentaje_final);
            $porcentaje = $porcentaje_final;
        }
        return $porcentaje;
    }

    /**
     * Metodo para comparar la cadena ingresada en el servicio contra el nombre en bd, reemplazando caracteres en las cadenas
     * @param Array $request_name arreglo de nombre y apellido a comparar
     * @param String $name nombre de la bd a comparar
     * @param String $search caracter de busqueda a reemplazar
     * @param String $replace caracter de reemplazo en la busqueda
     * @return float porcentaje de similitud
     * @author  alejandro.carvajal <alejo.carvajal03@gmail.com>
     */
    private function compareSimilarReplaceException($request_name, $name, $search, $replace)
    {
        $name = str_replace($search, $replace, $name);

        if (count($request_name) > 1) {
            $name_last = str_replace($search, $replace, $request_name[0] . ' ' . $request_name[1]);
            $last_name = str_replace($search, $replace, $request_name[1] . ' ' . $request_name[0]);
            similar_text($name_last, $name, $porcentaje_i_nombre);
            similar_text($last_name, $name, $porcentaje_i_apellido);
            $porcentaje = $porcentaje_i_nombre >= $porcentaje_i_apellido ? $porcentaje_i_nombre : $porcentaje_i_apellido;
        } else {
            $request_name[0] = str_replace($search, $replace, $request_name[0]);
            similar_text($request_name[0], $name, $porcentaje_final);
            $porcentaje = $porcentaje_final;
        }
        return $porcentaje;
    }

    /**
     * Metodo para reemplazar los acentos por caracteres sin acentos
     * @param String $word Palabra a reemplazar los acentos
     * @author  alejandro.carvajal <alejo.carvajal03@gmail.com>
     */
    private function removeAccents($word)
    {
        $conv = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
        ];
        return strtr($word, $conv);
    }
}
