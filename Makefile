reset_database:
	rm var/data.db
	rm migrations/*
	symfony console make:migration
	symfony console doctrine:migrations:migrate --no-interaction