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
* ```./vendor/robmorgan/phinx/bin/phinx migrate -e development```
* Ejecutar: ```php -S localhost:8888 -t public/```