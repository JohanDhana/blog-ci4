# CodeIgniter 4 Blog Application

Welcome to the **CodeIgniter 4 Blog Application**. This open-source project is a basic yet fully functional blog application migrated from CodeIgniter 3 to CodeIgniter 4.

## Features
- **Blog Post Management:** Create, update, delete, and manage blog posts with ease.
- **User Management:** Handle user registrations, logins, and profile updates.
- **Roles & Permissions:** Implement different user roles with specific permissions.
- **Dynamic Menu Generation:**
- **Modernized Codebase:** Benefit from the enhancements and improvements of CodeIgniter 4.

## Installation
1. **Clone the Repository:**
   ```bash
   git clone https://github.com/JohanDhana/blog-ci4.git
   cd codeigniter4-blog


Installation
------------

```

**2.** Set CI_ENVIRONMENT, baseURL, index page, and database config in your `.env` file based on your existing database (If you don't have a `.env` file, you can copy first from `env` file: `cp env .env` first). If the database does not exist, create the database first.

```bash
# .env file
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080'
app.indexPage = ''

database.default.hostname = localhost
database.default.database = ciblog
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

**5.** Run development server:

```bash
php spark serve
```

**6.** Open in browser http://localhost:8080/
```bash
Default user and password
+----+--------+-------------+
| No | User   | Password    |
+----+--------+-------------+
| 1  | john  | 1234        |
+----+--------+-------------+
```



Usage
-----
You can find how it works with the read code routes, controller and views etc.



Contributing
------------
Contributions are very welcome.

License
-------

This package is free software distributed under the terms of the [MIT license](LICENSE.md).
