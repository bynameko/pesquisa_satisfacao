# Sistema de Pesquisa de Satisfação #

Sistema web desenvolvido com foco na criação, gerenciamento e análise de pesquisas de satisfação, permitindo que empresas coletem feedback de clientes por meio de convites individuais e acompanhem os resultados através de um painel administrativo.

O projeto foi desenvolvido utilizando Laravel e Filament, seguindo boas práticas de arquitetura para facilitar manutenção, escalabilidade e evolução futura.

---

## Objetivos do Projeto ##

* Disponibilizar pesquisas através de links únicos.
* Evitar respostas duplicadas utilizando tokens exclusivos.
* Fornecer uma interface simples e responsiva para os respondentes.
* Centralizar a administração das pesquisas em um painel moderno.
* Gerar relatórios para análise dos resultados.

---

## Tecnologias ##

* PHP 8.2
* Laravel 12
* Filament 5
* MariaDB / MySQL
* Blade
* Vite
* Tailwind CSS

---

## Funcionalidades ##

### Painel Administrativo ###

* Cadastro de pesquisas
* Cadastro de perguntas
* Geração de convites individuais
* Controle de validade das pesquisas
* Dashboard com estatísticas
* Exportação em PDF
* Exportação em CSV
* Gerenciamento de usuários

### Área Pública ###

* Resposta sem necessidade de login
* Identificação do respondente
* Interface otimizada para dispositivos móveis
* Utilização de token único por convite
* Bloqueio automático de respostas duplicadas

---

## Arquitetura ##

O projeto foi planejado seguindo uma estrutura modular baseada em:

* Eloquent ORM
* Migrations
* Resources do Filament
* Services
* Policies
* Boas práticas do Laravel

O objetivo é manter o código organizado e preparado para futuras expansões.

---

## Roadmap ##

### MVP ###

* Cadastro de pesquisas
* Cadastro de perguntas
* Geração de convites
* Página pública
* Dashboard
* Relatórios

### Futuras versões ###

* Pesquisas com lógica condicional
* Perguntas em múltiplos idiomas
* API REST
* Integração com ERP
* Envio automático de convites por e-mail
* Dashboards avançados
* Autenticação SSO

---

## Objetivo do Repositório ##

Este projeto faz parte do meu portfólio profissional e demonstra a utilização do ecossistema Laravel para desenvolvimento de aplicações corporativas com foco em organização do código, escalabilidade e experiência do usuário.

---

## Autor ##

**Jonathan Pellin**

Especialista em Tecnologia da Informação

Desenvolvimento de Software • Infraestrutura • Arquitetura de Soluções
