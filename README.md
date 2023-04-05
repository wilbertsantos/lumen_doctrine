# Lumen + Doctrine Application

## Installation

clone the repository.

install the needed dependencies: ```composer update```

migrate the database:  ```php artisan migrate```

serve: ```php -S localhost:8000 -t public```

run the command to get users from 3rd party api: ```php artisan import:customers```

all customer endpoint: ```http://localhost:8000/customers```

get customer info endpoint: ```http://localhost:8000/customers/1```

run the tests: ```vendor\bin\phpunit.bat tests```