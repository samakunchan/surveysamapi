Commande à run pour obtenir les clés publiques et privés:

openssl genrsa -out config/jwt/private.pem 4096 

openssl rsa -in config/jwt/private.pem -outform PEM -pubout -out config/jwt/public.pem

