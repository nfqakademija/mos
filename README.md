Endpoints:

@Route("api/profile/view/{id}", name="api.profile.view.id", methods="POST")

http://127.0.0.1:8000/api/profile/view/2

Result:

{"username":"teacher1","name":"Teachername1","surname":"Teachersurname1","birth_date":null,"email":"teacher1@email.com","phone":null,"region":null,"address":null,"reg_date":{"date":"2018-11-07 08:54:07.000000","timezone_type":3,"timezone":"UTC"},"last_access_date":null,"roles":["ROLE_TEACHER","ROLE_USER"]}






Useful stuff during development.


- bin/console doctrine:database:create --if-not-exists
- bin/console doctrine:schema:drop --force --full-database

- bin/console doctrine:migration:diff
- bin/console doctrine:migration:migrate
- bin/console doctrine:fixtures:load


Generate the SSH keys (for tokens) :
(https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation)

1. $ mkdir -p config/jwt # For Symfony3+, no need of the -p option
2. $ openssl genrsa -out config/jwt/private.pem -aes256 4096
3. $ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

In case first openssl command forces you to input password use following to get the private key decrypted

1. $ openssl rsa -in config/jwt/private.pem -out config/jwt/private2.pem
2. $ mv config/jwt/private.pem config/jwt/private.pem-back
3. $ mv config/jwt/private2.pem config/jwt/private.pem
