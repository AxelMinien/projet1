# Fiche mémoire : Gestionnaire de tâches collaboratif

## 1. Machine virtuelle
- OS : Debian 13 (trixie)
- Nom d'hôte : pasdebiantrixie
- IP : 192.168.105.125
- Accès : SSH via PuTTY, utilisateur root
- Dossier du projet : /root/taskmanager
- Samba : non installé

## 2. PostgreSQL 17
- Hôte / port : 127.0.0.1 / 5432
- Base : taskmanager
- Utilisateur : taskmanager
- Mot de passe PostgreSQL : (voir fiche privée sur la VM)
- Superutilisateur : postgres (su - postgres)
- DATABASE_URL (dans .env.local) : postgresql://taskmanager:MOTDEPASSE@127.0.0.1:5432/taskmanager?serverVersion=17&charset=utf8
- Connexion rapide : PGPASSWORD=MOTDEPASSE psql -h 127.0.0.1 -U taskmanager -d taskmanager

## 3. Mailpit
- SMTP : 127.0.0.1:1025 (MAILER_DSN=smtp://127.0.0.1:1025)
- Interface web : http://192.168.105.125:8025
- Installé dans /usr/local/bin/mailpit (service systemd conseillé)

## 4. Application
- URL : http://192.168.105.125:8000
- Lancement : cd ~/taskmanager && nohup php -S 0.0.0.0:8000 -t public > /tmp/php-server.log 2>&1 &
- PHP 8.4, Symfony 7, Doctrine ORM
- Serveur Symfony CLI inutilisable en root (PHP-FPM refuse root), d'où le serveur intégré PHP
- Comptes de test prévus (fixtures non chargées) : admin@test.com, alice@test.com, bob@test.com, chloe@test.com, mot de passe : password

## 5. GitHub
- Compte utilisé : maxiiram (edenthibault@gmail.com)
- Dépôt : AxelMinien/projet1 (public)
- Projet envoyé dans le sous-dossier : taskmanager/
- Jeton (classic, case repo) : stocké dans /root/.github_token (chmod 600), jamais dans le projet
- Expiration du jeton : 30 jours après création (créé le 06/11/2026)
- Script d'envoi : ~/push-github.sh "message du commit"
- Exclus de l'envoi : .env.local, vendor/, var/
- À faire : révoquer l'ancien jeton github_pat_... apparu dans une capture
- Si 403 : maxiiram doit être collaborateur du dépôt d'Axel

## 6. Modèle de données (conforme au MCD)
- Tables : utilisateur, projet, tache, est_membre, notification, journal_activite
- Clés : id_utilisateur, id_projet, id_tache, id_notification, id_journal
- est_membre : clé composée (id_utilisateur, id_projet) + date_ajout
- Enums : Statut (a_faire, en_cours, terminee), Priorite (basse, moyenne, haute), TypeNotification (tache_assignee, tache_modifiee)
- Colonnes renommées : mot_de_passe, role (valeur ROLE_USER ou ROLE_ADMIN)

## 7. Architecture MVC
- src/Controller : contrôleurs (C)
- src/Entity, src/Repository, src/Enum, src/Service : modèle (M)
- templates/, assets/ : vues Twig et Stimulus (V)
- src/Form : formulaires
- src/Security/Voter : droits d'accès (ProjetVoter, TacheVoter)
- src/EventSubscriber, src/Event : notifications et journal
- src/DataFixtures : données de test
- migrations/, config/, public/, tests/, translations/, docs/
- var/ et vendor/ : non versionnés

## 8. Commandes utiles
- Cache : php bin/console cache:clear
- Routes : php bin/console debug:router
- Valider le schéma : php bin/console doctrine:schema:validate
- Migration : php bin/console make:migration puis doctrine:migrations:migrate
- Fixtures : php bin/console doctrine:fixtures:load
- Logs : tail -f var/log/dev.log
- Envoi GitHub : ~/push-github.sh "message"

## 9. Avancement
- Phase 0 : environnement : fait
- Phase 1 : entités, migration : commande unique à valider (schema:validate et \dt)
- Phase 2 : authentification (make:auth, make:registration-form) : à faire
- Phases 3 à 8 : projets, tâches, notifications, tableau de bord, recherche, tests : à faire
