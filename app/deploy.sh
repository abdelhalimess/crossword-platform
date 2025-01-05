#!/bin/bash

# Variables
LOCAL_ARCHIVE="mon_app.zip"       # Nom de l'archive locale
REMOTE_SERVER="urouen@192.168.76.76"   # Utilisateur et adresse IP du serveur distant
REMOTE_DIR="/var/www/html"        # Répertoire cible sur le serveur distant
INSTALL_SCRIPT="install.php"      # Nom du script d'installation sur le serveur distant
TEMP_DIR="/tmp"                   # Répertoire temporaire pour décompresser l'archive localement

# Vérifier si l'archive existe localement
if [ ! -f "$LOCAL_ARCHIVE" ]; then
    echo "L'archive $LOCAL_ARCHIVE n'existe pas."
    exit 1
fi

# Étape 1 : Décompresser l'archive localement
echo "Décompression de l'archive..."
mkdir -p "$TEMP_DIR"
unzip "$LOCAL_ARCHIVE" -d "$TEMP_DIR"

if [ $? -ne 0 ]; then
    echo "Erreur lors de la décompression de l'archive !"
    exit 1
fi

echo "Archive décompressée avec succès."

# Étape 2 : Transférer l'archive décompressée vers le serveur distant
echo "Transfert de l'application vers le serveur distant..."

scp -r "$TEMP_DIR/*" "$REMOTE_SERVER:$REMOTE_DIR"

if [ $? -ne 0 ]; then
    echo "Erreur lors du transfert de l'application !"
    exit 1
fi

echo "Application transférée avec succès."

# Étape 3 : Lancer le script d'installation sur le serveur distant
echo "Lancement du script d'installation sur le serveur distant..."

ssh "$REMOTE_SERVER" "php $REMOTE_DIR/$INSTALL_SCRIPT"

if [ $? -ne 0 ]; then
    echo "Erreur lors de l'exécution du script d'installation !"
    exit 1
fi

echo "Installation terminée avec succès."

# Nettoyage temporaire
rm -rf "$TEMP_DIR"

echo "Déploiement terminé."
