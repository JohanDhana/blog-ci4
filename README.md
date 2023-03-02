
CodeIgniter 4 Blog Application 
=====================================
This project  for CodeIgniter 4 serves as a basic blog application. It is migrated from an old project in ci3 , includes blog post creation and management, user management, roles, permissions and a dynamically-generated menu.

Feature
-------
* CSS framework [Bootstrap 4 and 5](https://getbootstrap.com/)
* Dynamically-Generated Menu


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
You can find how it works with the read code routes, controller and views etc. Finnally... Happy Coding!



Contributing
------------
Contributions are very welcome.

License
-------

This package is free software distributed under the terms of the [MIT license](LICENSE.md).
