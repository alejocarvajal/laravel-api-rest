<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## Instalación

- Requiere PHP 7.2

- Descomprimir el archivo stradataAPI.zip
- Acceder a la carpeta stradataAPI
- Modificar el archivo .env los parametros de la BD
    ~~~
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=stradata
    DB_USERNAME=root
    DB_PASSWORD=admin
    ~~~
- Importar el archivo stradata.sql en la base de datos

## Ejecución
a través del terminal se ejecuta el comando:
php artisan serve --port=8001

## Algoritmo

Algoritmo ( ApiController  )

1) Se consulta todos los registros del diccionario en la base de datos

2) Se convierte el nombre a buscar en minuscula

3) Se eliminan los acentos del nombre a buscar

4) Se recorre cada uno de los registros

    4.1) Se convierte el nombre del diccionario en minuscula

    4.2) Se eliminan los acentos del nombre

5) Cada registro se compara a traves de similar_text

    5.1) La primera comparación se realiza con nombre y apellido /  apellido y nombre; Se retorna el de mayor porcentaje en la coincidencia

    5.2) La segunda comparación se realiza con nombre y apellido /  apellido y nombre reemplazando caracteres en los nombres (b,v,s,z)

    5.3) Cuando se tienen los porcentajes de las comparaciones anteriores, se devuelve el mas alto

6) si el porcentaje obtenido de la comparación es mayor o igual al solicitado se agrega toda la info del nombre a un array

7) una vez termina se devuelve un JSON con los resultados encontrados, cantidad de registros y mensaje de ejecucion

## Uso WebService
- Servicio Obtener token de usuario
    - Servidor: http://127.0.0.1:8000/api/login
    - Metodo: POST
    - Body en JSON:
        ~~~
        {
            "email" : "test@gmail.com",
            "password" : "12345678"
        }
        ~~~
    - Retorno:
        ~~~
        {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTYyMzkyNjUxNCwiZXhwIjoxNjIzOTMwMTE0LCJuYmYiOjE2MjM5MjY1MTQsImp0aSI6IlVjemtsZkpDREVObXloWHoiLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.2IjtaAnSrQ35vHYt3Q51FJAjOspxXXF2E3Vx1xV3BJ8"
        }
        ~~~
- Servicio obtiene las coincidencias del nombre
    - Servidor: http://127.0.0.1:1/api/stradataAPI/findName
    - Metodo: POST
    - Header:
        - Authorization: "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNmVjNDZmZmNjNzg2YzczZDExZDMyZGQ5NGI1MTU2YWZjMDMwODY1ZTNmYzA3ZTI0YjdmYmU2MjQxZWMxYWU5MTRjYjRkMGUwNjU0OWIwNjgiLCJpYXQiOjE2MjM4MzI0ODAsIm5iZiI6MTYyMzgzMjQ4MCwiZXhwIjoxNjU1MzY4NDgwLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.pts_YPVo8iD9XriRwwFmiCoqdPbSi_WfWcxhqi-SArbRoFcRTuOKP4dBo9iwysF7aVRTkGn0fkeaGZ7NV4FGX-4GyL2EJ6z3wGEZC4pMjhflMROQZTOSg4TzNMzOjPv8TeOM7gdC6vRJAnPIe2j5WbecQKoTe2tbS6j15VtRYNEVrOQ7nvX6Zx8dYpwqZgxnf03wvgzwgtRuNk0SZ2-5ldFFF9pkLD5Pli2rjHPBe_e8M3AzQWNKE2MwSdRiyRl7jtqT4kZwuvClMx_FE2-N948N1vadf08atxzA5N-RTo05PePA7jVMlg08xHNb-bj-FsntA8lnqTnx03018goX0sQoc18I6xo6yZ1KslpS8M4hMiIss2o2275qJBMTQiV1S30G7o1yXksh4BQdOTNG67mrOh0zmloenYrn236P5FjySIfsaFR2ubYqJKvTqRWksOZOatEnE6zmco6n-ht2wEoDax6gJrMg3TkKV5gsnFX9J25om1XzaSxhybZjYa0gR5mhdctXhzPsdJvVp8fAlcqFTBSOItVneaZC-GvVCjRWplxq5vJgmE0GY9dKF291PPKiQbVZcwBv3SUD0JILWIwKY_y4dcm4ysidzlqRij4NO_oUc3mdrDBz_gz33I1lP1CSR1kX-OOSAGmAA0bTZkNyHtWPzu9CL3bLtnBR8Rk"
    - Body en JSON:
        ~~~
        {
            "nombre" : "Alejandro Rodriguez",
            "porcentaje" : "14.5"
        }
        ~~~
    - Retorno: 
        ~~~
        {
            "nombre_buscado": "Alejandro Rodriguez",
            "porcentaje_buscado": "14.5",
            "registros_encontrados": 2,
            "resultados": [
                {
                    "nombre": "Bryan Alejandro Castro Parrado",
                    "porcentaje": 14.516129032258064,
                    "departamento": "ARAUCA",
                    "localidad": "NO APLICA",
                    "municipio": "SARAVENA",
                    "anios_activo": 10,
                    "tipo_persona": "PREFERENTE",
                    "tipo_cargo": "POLITICO"
                },
                {
                    "nombre": "Alejandro Arboleda Urrego",
                    "porcentaje": 14.634146341463415,
                    "departamento": "CUNDINAMARCA",
                    "localidad": "NO APLICA",
                    "municipio": "UBALA",
                    "anios_activo": 8,
                    "tipo_persona": "PREFERENTE",
                    "tipo_cargo": "POLITICO"
                }
            ],
            "estado_ejecucion": "registros encontrados"
        }
        ~~~
        
