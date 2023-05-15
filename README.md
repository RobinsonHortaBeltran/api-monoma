Correr el proyecto 

1-> Instalar composer 
2-> Ejecutar el siguiente comando para ejecutar los test
2.1->  php artisan test
3-> Copiar el archivo .env.example y cambiar el nombre a .env 
4-> Ejecutar comando para crear migraciones y seeders
4.1-> php artisan mgrate --seed
5-> Ejecutar comando para correr el proyecto 
5.1-> Php artisan serve 

Pruebas en postman 

1-> Abrir postman 
2-> Generar peticion post a la siguiente ruta 
2.1->http://127.0.0.1:8000/api/auth/login
2.2-> pasando los siguientes datos 
2.2.1-> {
    "username":"usuario creado en la bd",
    "password":"password123"
}
3-> Copiar el token y agregarlo en la autorizacion para probar las rutas.

Rutas de accesos y eliminacion de token
1-> POST Login (http://127.0.0.1:8000/api/auth/login)
2-> POST LogOut (http://127.0.0.1:8000/api/auth/logout)

Rutas de pruebas 
1->  Leads Get(http://127.0.0.1:8000/api/lead/)
2->  Obtener un lead Get(http://127.0.0.1:8000/api/lead/{id})
3->  Crear lead Solo como manager Post(http://127.0.0.1:8000/api/lead/)