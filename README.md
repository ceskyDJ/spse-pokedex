# Pokedex web application project

## Group

- Michal ŠMAHEL: Back-end, Database
- Václav PAVLÍČEK: Front-end

## Life version

You can see live version at https://pokedex.ceskydj.cz/

## Address plan
### Public

- [/](https://pokedex.ceskydj.cz/) - Home page, list of pokemons, pokemon filtering
- [/detail](https://pokedex.ceskydj.cz/detail) - Pokemon detail
- [/json-upload](https://pokedex.ceskydj.cz/json-upload) - Upload data from json (from [GitHub](https://github.com/Biuni/PokemonGO-Pokedex))
- [/login](https://pokedex.ceskydj.cz/login) - User log-in

### Private

- [/user](https://pokedex.ceskydj.cz/user) - User's dashboard, adding or removing pokemons from account
- [/select-pokemon](https://pokedex.ceskydj.cz/select-pokemon) - Add pokemon to user account

### Administration

- [/admin/](https://pokedex.ceskydj.cz/admin) - Home page of administration, users and pokemons managing (editing, removing)
- [/admin/add-user](https://pokedex.ceskydj.cz/admin/add-user) - Adding a new user
- [/admin/edit-user](https://pokedex.ceskydj.cz/admin/edit-user) - Editing an existing user
- [/admin/pokemon-form](https://pokedex.ceskydj.cz/admin/pokemon-form) - Adding or editing pokemon

## Other files

- Database ER Diagram (MySQL Workbench) - [/other/database/ER Diagram.mwb]((https://github.com/ceskyDJ/spse-pokedex/blob/master/other/database/ER%20Diagram.mwb))
- Database ER Diagram (SVG) - [/other/database/ER Diagram.svg](https://github.com/ceskyDJ/spse-pokedex/blob/master/other/database/ER%20Diagram%20-%20SVG.svg)
- Database ER Diagram (PNG) - [/other/database/ER Diagram.png](https://github.com/ceskyDJ/spse-pokedex/blob/master/other/database/ER%20Diagram%20-%20PNG.mwb)
- Database creation script - [/other/database/Create database.sql](https://github.com/ceskyDJ/spse-pokedex/blob/master/other/database/Create%20database.sql)

## First use

- Download sources from GitHub (ex. with git clone)
- Create database
- Set database credentials in src/Config/local-config.ini
- Go to /json-upload for uploading base data
- Create user account manually with PersonManager's register() method (registration isn't available)
- Set admin rights to the new user in database (change isn't available, too)