# 🧩 Proyecto CEA-PHP — Arquitectura Hexagonal

Este proyecto implementa una aplicación PHP usando **Arquitectura Hexagonal (Puertos y Adaptadores)**.  
Permite registrar y listar usuarios conectados a una base de datos MySQL.  
Fue desarrollado con **PHP puro (sin frameworks)** aplicando los principios de **DDD (Domain-Driven Design)**.

---

## ⚙️ Tecnologías utilizadas

- PHP 8.0 (CLI o XAMPP)
- MySQL (con PDO)
- Visual Studio Code
- PowerShell / Git Bash
- Git y GitHub

---

## 🧱 Estructura del proyecto

cea-php/
│
├── public/ → Punto de entrada del proyecto
│ ├── index.php → Página principal con formulario y lista de usuarios
│ ├── db.php → Conexión a la base de datos
│ └── db_test.php → Prueba de conexión a MySQL
│
├── src/
│ ├── Domain/ → Capa del dominio (reglas del negocio)
│ │ ├── Entities/ → Entidades (User.php)
│ │ ├── ValueObjects/ → Objetos de valor (Email.php)
│ │ ├── Services/ → Lógica del dominio (UserService.php)
│ │ ├── Events/ → Eventos del dominio
│ │ └── Exceptions/ → Excepciones personalizadas
│ │
│ ├── Application/ → Casos de uso del sistema
│ │ └── UseCases/ → Acciones específicas (RegisterUser.php, LoginUser.php)
│ │
│ └── Infrastructure/ → Conexiones externas
│ ├── Persistence/ → Interfaces del repositorio (UserRepository.php)
│ └── Adapters/ → Implementaciones concretas (UserRepositoryMySQL.php)
│
└── README.md 


---

## 💡 Explicación de la arquitectura

- **Domain:**  
  Aquí se encuentra la lógica principal del sistema. Contiene las entidades (`User`), objetos de valor (`Email`) y servicios (`UserService`).

- **Application:**  
  Define los casos de uso, que son las acciones que puede realizar el sistema, como registrar o listar usuarios.

- **Infrastructure:**  
  Contiene los adaptadores para conectar el dominio con la base de datos (MySQL), implementando los puertos definidos en la capa de dominio.

- **Public:**  
  Es la capa de entrada. Aquí está el formulario web y las pruebas de conexión a la base de datos.

---

## 🚀 Cómo ejecutar el proyecto

### 1️⃣ Crea la base de datos en MySQL

Ejecuta en tu consola MySQL o phpMyAdmin:

```sql
CREATE DATABASE cea_php;
USE cea_php;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE
);
