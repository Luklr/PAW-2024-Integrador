# PAW-2024
Repositorio para el trabajo practico integrador de la materia Programación en Ambiente Web, año 2024. 

Integrantes: 
- Gonzalo Benito
- Francisco Guerra
- Kevin Monti
- Lucio Reinoso

Enlaces adicionales:
- [Website y Wireframes (Figma)](https://www.figma.com/design/ONXuvvXs0WmqVRkJkGa68g/TP-Integrador?node-id=0-1&t=2Tb9tEXdZ4r9nR69-0)


## Instalación y Ejecución (local)

* ```git clone <url-repo>```
* ```cd project-name```
* ```composer install```
* ```cp .env.example .env``` # Editar el ```.env``` con los valores deseados
* ```docker compose up -d```
* ```./db/scripts/init_db.sh```
* Ejecutar: ```php -S localhost:8888 -t public/```

## Usuarios de prueba

* Usuario común: 
    * Email: user@example.com
    * Contraseña: 12345678
* Usuario administrador:
    * Email: admin@example.com
    * Contraseña: 12345678