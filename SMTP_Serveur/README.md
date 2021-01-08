### Arguments

- "- p PORT" permet de lancer votre serveur sur n'importe quel port indiqué, cet argument est facultatif, en normal votre serveur sera lancé sur le port 587, et en sécurisé 465.
- "-ssl" permet de lancer votre serveur en mode SSL, par conséquent vous devez utiliser la commande -cert. 
- "-cert CERTIFICATE_PATH CERTITIFCATE_PASSWORD" indique le certificat utilisé pour la connexion sécurisé.
- "-save_path SAVE_PATH" indique l'endroit où vos mails seront sauvegardés, cet argument est facultatif par défaut, sinon l'endroit de sauvegarde sera l'endroit où vous avez lancé votre programme.


# Utilisation

Tout d'abord, si vous lancez votre programme sans l'argument "-p PORT", sur linux il faudra lancer le jar en sudo, car les ports 465 et 587 sont protégés. Sur windows, aucun problème.


Par la suite, il vous faudra un client mail quelconque, pour ma part, j'ai utilisé Thunderbird Mail (disponible sur linux et sur Windows), mettez un compte quelconque sur votre client. Ensuite, il vous faudra changer les settings du server SMTP(serveur sortant), ajoutez un serveur smtp qui sera donc, votre ip local (127.0.0.1 si vous testez sur la même machine que le client), puis indiquez dans la case si vous voulez ou non, du SSL/TLS, sinon prenez le mode qui contient aucune sécurité. 
Vous pouvez aussi choisir l'authentification ou non, dans le cas où vous la prenez mettez "mot de passe normal"(sans encryption), le Username est "Username" et le mot de passe est "Password". 

Si vous utilisez la commande "-ssl" il vous faudra générer une clé pour votre serveur, pour cela je vous conseil simplement de suivre ce tuto : https://www.youtube.com/watch?v=X4WImgwvjDw (de 2:02 à 2:54) une fois la clé générée, lors du lancement il vous faudra mettre le chemin de votre clé ainsi que sont mot de passe(cf argument -cert plus haut). 
Il faudra importer cette clé sur thunderbird. Pour cela, aller dans l'onglet sécurité, et faites gérer les certificats et dans vos certificats, ajoutez le votre. De plus il vous faudra suivre ce tuto pour ajouter l'exception à votre serveur https://manuals.gfi.com/en/kerio/connect/content/server-configuration/ssl-certificates/self-signed-certificates-in-mozilla-thunderbird-950.html , remplacez l'exemple par votre serveur (votre_ip:port),
n'oubliez pas de séléctionner votre certificat dans l'onglet sécurité.

Normalement, chaque e-mail envoyé sera écrit soit dans le dossier d'éxecution, soit là où vous avez set votre -save_path en argument, de plus le serveur a des logs et par conséquent vous pouvez suivre tout ce qui est fait.

L'envoi de pièce jointe ne fonctionne pas.