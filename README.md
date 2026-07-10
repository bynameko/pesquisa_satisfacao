Quero continuar o planejamento de um sistema de pesquisas de satisfação desenvolvido em PHP 8.2, Laravel 12.6, Filament 5.6 e MySQL (MariaDB 10.4).

Decisões já tomadas:

* O sistema terá um painel administrativo em Filament.
* Os clientes responderão pesquisas através de uma página pública, sem login.
* O administrador poderá criar pesquisas, perguntas e definir datas de validade.
* O sistema deverá gerar relatórios em PDF e CSV com filtros por período.
* Utilizaremos tokens únicos para impedir respostas duplicadas.
* Não haverá cadastro prévio de clientes para responder pesquisas.
* O administrador poderá gerar uma quantidade de links únicos para cada pesquisa.
* Cada link representa um convite único.
* O cliente acessa o link, informa nome e e-mail, responde a pesquisa e o link é marcado como utilizado.
* Após respondido, o mesmo link não poderá mais ser utilizado.
* A estrutura principal será:

Pesquisa  
├── Perguntas  
├── Convites (links únicos)  
│   ├── token  
│   ├── status  
│   └── respondido_em  
├── Respondente  
│   ├── nome  
│   └── email  
└── Respostas  

Requisitos desejados:

* Interface extremamente simples para o respondente.
* Compatível com celular.
* Possibilidade de copiar links individuais.
* Possibilidade de gerar QR Codes.
* Dashboard com estatísticas.
* Exportação CSV e PDF.
* Controle de validade das pesquisas.
* Possibilidade de gerar centenas ou milhares de links por pesquisa.

A partir dessas definições, quero continuar o projeto elaborando:

1. Modelagem completa do banco de dados.
2. Migrations Laravel.
3. Models e relacionamentos Eloquent.
4. Estrutura dos Resources do Filament.
5. Fluxo completo da página pública de resposta.
6. Dashboard administrativo.
7. Estratégia de geração de relatórios.
8. Estrutura do projeto seguindo boas práticas para crescimento futuro.
9. Planejamento do MVP e das funcionalidades para versões futuras.

Esse projeto será armazenado no github.
