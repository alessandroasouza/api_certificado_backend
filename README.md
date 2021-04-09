## Instalação

1.Para efeutar a instalação deve-e primeiramente clonar o projeto;


3.Em seguida será necessário insalar os pacotes via Composer[composer install]. Na pasta raiz do projeto executar:



5.Renomear o arquivo .env.example para .env e alterar as configurações do banco local;


7.Executar o migration para criar a estrutura do banco de dados[php artisan migrate];


9.Inicializar o projeto[php -S localhost:8080 -t public]


10. php artisan db:seed para gerar dados fakes de teste


##Git##

git pull ou git pull origin main baixar todas as atualizações

git add .  -> adicionar arquivos todos os arquivos
git comit -m "descreva aqui o que você fez" -> Commit do código
git git push -> Enviar para o servidor GitHub

git checkout -b alteracao_que_voce_vai_fazer
