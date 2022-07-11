<p align="center"><a href="https://yampi.com.br" target="_blank"><img src="https://icons.yampi.me/svg/brand-yampi.svg" width="200"></a></p>

# Teste prático para Back-End Developer
***

- CRUD através de uma API REST com Laravel;
- comando artisan que se comunicará com uma outra API para importar em um banco de dados;


### Configuração do ambiente
***

**Para configuração do ambiente é necessário ter o [Docker](https://docs.docker.com/desktop/) instalado em sua máquina.**

Dentro da pasta do projeto, rode o seguinte comando: 
```bash
docker-compose up -d
```

Copie o arquivo `.env.example` a renomeie para `.env` dentro da pasta raíz da aplicação.

```bash
cp .env.example .env
```

Após criar o arquivo `.env`, será necessário acessar o container da aplicação para rodar alguns comandos de configuração do Laravel.

Para acessar o container use o comando:

```bash
docker exec -it yampi_test_app sh
```

Digite os seguintes comandos dentro do container:

```bash
composer install
php artisan key:generate
php artisan migrate
```

Após rodar esses comandos, seu ambiente estará pronto para começar.

Para acessar a aplicação, basta acessar `localhost:8000`

### Instruções para teste

##### Importação do produtos por comando

É possivel utilizar o comando abaixo, que busca produtos API da `https://fakestoreapi.com` e armazena os resultados na base de dados.

```bash
php artisan products:import
```

Caso deseje importar um produto em específico é possível informar o id no comando (id de 1 a 20 conforme a API da FakeStore):

```bash
php artisan products:import --id=15
```

Se o item ja existir na base é retornado a uma mensagem de aviso no terminal, conforme o exemplo abaixo:
`Item Rain Jacket Women Windbreaker Striped Climbing Raincoats não importado, já existe um registro cadastrado na base com esse nome.`

##### Requisições API

Segue as requisições do projeto:

Requisições | Tipo     | Rota                                    | 
----------- | :------: | :------:                                | 
index       | GET      | http://localhost:8000/api/products      |
store       | POST     | http://localhost:8000/api/products      |     
show        | GET      | http://localhost:8000/api/products/{id} |
update      | PUT      | http://localhost:8000/api/products/{id} |
delete      | DELETE   | http://localhost:8000/api/products/{id} |

Possiveis itens do payload:

Campo        | Tipo      | 
-----------  | :------:  | 
name         | string    |      
price        | float     |
description  | text      | 
category     | string    |
image        | url       | 


Os endpoints de criação e atualização devem seguir o seguinte formato de payload:

```json
{
    "name": "product name",
    "price": 109.95,
    "description": "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...",
    "category": "test",
    "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}
```

Possiveis filtros da requisição de listagem:

Campo       | Tipo      | Filtro          | 
----------- | :------:  | :------:        | 
search      | string    | name e category |
category    | string    | category        |     
image       | boolean   | image           |
per_page    | int       | num. de itens   |

Obs: Quando os filtros `search` e `category` são utilizados juntos a listagem traz as "categorias" informados em ambos.

##### Exemplo de Requisições

- Listagem geral (index)

```json
curl --location --request GET 'http://localhost:8000/api/products' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '
```

- Listagem com filtros (index)

```json
curl --location --request GET 'http://localhost:8000/api/products' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "search": "clothing",
    "category": "electronics",
    "image": true
}'
```

- Criação (store)

```json
curl --location --request POST 'http://localhost:8000/api/products' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Notebook",
    "price": 5000.00,
    "description": "auris condimentum feugiat lorem eu suscipit. Vivamus cursus eros quis urna placerat, in vestibulum urna congue.",
    "category": "eletronics",
    "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}'
```

- Busca pelo Id (show)

```json
curl --location --request GET 'http://localhost:8000/api/products/649' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

- Atualização (update)

```json
curl --location --request PUT 'http://localhost:8000/api/products/26' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "New Notebook",
    "price": 5250.00,
    "description": "new description.",
    "category": "new category",
    "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}'
```

- Exclusão (delete)

```json
curl --location --request DELETE 'http://localhost:8000/api/products/615' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

---

Se houver dúvidas, por favor, contatatar via linkedin (https://www.linkedin.com/in/vinicius-rizzi/).

