# SGUW
>Sistema de Gerenciamento de Usuário do Wi-fi do IFCE - Campus Sobral

## FERRAMENTAS USADAS

* Laravel [^2]
* Inertia [^3]
* ReactJS [^4]
* Tailwind CSS [^5]

[^2]: Framework PHP <https://laravel.com/>
[^3]: Integração PHP Javascript sem API <https://inertiajs.com/>
[^4]: Biblioteca para construção de páginas em Javascript<https://react.dev/>
[^5]: Framework CSS para estilização de páginas <https://tailwindcss.com/>

## INSTALAÇÃO

Para instalar faça um clone do projeto:

```sh
git clone https://github.com/CTI-Sobral-IFCE/sguw.git
```

Acesse o diretório do projeto:

```sh
cd sguw
```

Faça uma cópia do arquivo ```.env```:

```sh
cp .env.example .env
```

Abra o arquivo ```.env``` e preencha com as configurações(Nome, url, banco de dados) do projeto.

Instale os pacotes do PHP/Laravel:

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Adicione um ``alias`` no arquivo de configuração do ``bash`` [^1]:
[^1]: Documentação para criação de alias <https://laravel.com/docs/11.x/sail#configuring-a-shell-alias>

```sh
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

Instale os pacotes Javascript/Inertia/Javascript:

```sh
sail npm install
```

Gere a chave de segurança da sua aplicação Laravel:

```sh
sail artisan key:generate
```

Povoe o banco de dados:

```sh
sail artisan migrate:fresh --seed
```

Para executar o projeto depois das configurações:

```sh
sail run dev
```

O sistema está distribuído em duas partes do front-end.

* Página de acesso público
  * <http://url-do-app>
* Dashboard
  * <http://url-do-app/admin>

Para acessar a dashboard use as seguintes credenciais
* Usuário: ti.sobral@ifce.edu.br
* Senha: qwe123

### TELAS

Gerenciamento de Usuários

![Gerenciamento de Usuários](https://github.com/CTI-Sobral-IFCE/skeleton/blob/main/public/screenshots/users-admin.png?raw=true "Admin users")

![Formulário de novo Usuários](https://github.com/CTI-Sobral-IFCE/skeleton/blob/main/public/screenshots/users-create.png?raw=true "Create users")

![Detalhes do Usuários](https://github.com/CTI-Sobral-IFCE/skeleton/blob/main/public/screenshots/users-show.png?raw=true "Show users")

Gerenciamento de permissões

![Gerenciamento de Permissões](https://github.com/CTI-Sobral-IFCE/skeleton/blob/main/public/screenshots/permissions-show.png?raw=true "Show permissions")

<font size="1">*A permissão Administrador não precisa de nenhuma regra pois, por padrão, já acessa todos as páginas.*</font>

### CRÉDITOS

Projeto desenvolvido pela Coordenadoria de Tecnologia da Informação do IFCE - *Campus* Sobral.

### CONTATO

<ti.sobral@ifce.edu.br>

### LICENÇA

Este é um software de código aberto licenciado sob a licença do [MIT](https://opensource.org/licenses/MIT).
