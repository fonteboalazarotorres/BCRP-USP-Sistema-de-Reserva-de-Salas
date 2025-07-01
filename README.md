# Sistema de Reserva de Salas USP

Sistema web simples para reserva de salas da USP, desenvolvido em PHP e MySQL, com dashboard administrativo e área pública de reservas sem necessidade de login.

## Funcionalidades

- Reserva de salas com controle automático de disponibilidade e bloqueio de horários conflitantes  
- Suporte para 15 salas com diferentes capacidades (até 4 pessoas e acima de 4 pessoas)  
- Coleta de dados detalhados: nome, número USP, vínculo, data, horário, equipamentos necessários  
- Dashboard administrativo com estatísticas, gráficos de reservas por mês e horários mais reservados  
- Aprovação, rejeição, edição e cancelamento de reservas pelo administrador  
- Exportação de relatórios em PDF e Excel  
- Sistema responsivo e acessível, seguindo identidade visual USP  
- Implementado para rodar em servidores comuns com cPanel  

## Tecnologias utilizadas

- PHP 7.x  
- MySQL  
- JavaScript (Chart.js para gráficos)  
- TCPDF (geração de PDFs)  
- PHPSpreadsheet (exportação Excel)  
- HTML5, CSS3 (com design inspirado na USP)  

## Instalação

1. Clone este repositório:  

git clone https://github.com/fonteboalazarotorres/


2. Configure o banco de dados MySQL:  
- Crie o banco `reserva_salas`  
- Importe o arquivo SQL com as tabelas e dados iniciais (fornecido no projeto)  

3. Ajuste as credenciais do banco no arquivo `includes/config.php`:

define('DB_HOST', 'localhost');
define('DB_NAME', 'reserva_salas');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');


4. Faça upload dos arquivos para seu servidor cPanel ou ambiente local com suporte PHP e MySQL.

5. Acesse:  
- Área pública de reservas: `http://seusite.com/reserva_salas/index.php`  
- Dashboard administrativo: `http://seusite.com/reserva_salas/admin/`  
  (Usuário padrão: `admin`; Senha: `admin123` — altere após o primeiro acesso)

## Uso

- Usuários realizam reservas simples sem login, escolhendo sala, data, horário e equipamentos.  
- Administradores aprovam, rejeitam, editam e cancelam reservas via dashboard.  
- Estatísticas e gráficos ajudam no acompanhamento do uso das salas.  
- Exportação de relatórios em PDF e Excel facilita a análise e arquivamento.

## Contribuição

Contribuições são bem-vindas! Para sugerir melhorias ou corrigir bugs, abra uma issue ou envie um pull request.

## Licença

Este projeto está licenciado sob a licença MIT.