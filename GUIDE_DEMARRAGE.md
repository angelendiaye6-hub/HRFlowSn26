# Guide de Démarrage Rapide - HRFlowSn

Ce guide vous aide à installer et utiliser HRFlowSn en quelques étapes simples.

## 📋 Prérequis

- Un ordinateur avec Windows, Mac ou Linux
- **XAMPP** (gratuit) - Pour faire fonctionner le site web
- Un navigateur web (Chrome, Firefox, Edge, etc.)

---

## 🔧 Étape 1 : Installer XAMPP

1. Téléchargez XAMPP depuis : https://www.apachefriends.org/
2. Installez XAMPP en cliquant sur "Suivant" à chaque étape
3. Une fois installé, ouvrez **XAMPP Control Panel**
4. Cliquez sur "Start" à côté de **Apache** et **MySQL**
5. Les deux doivent devenir verts

---

## 📁 Étape 2 : Placer le dossier HRFlowSn

Ouvrez le terminal (ou invite de commandes) et exécutez la commande adaptée à votre système :

### Windows
```bash
xcopy /E /I HRFlowSn C:\xampp\htdocs\HRFlowSn
```

### Mac
```bash
cp -r HRFlowSn /Applications/XAMPP/xamppfiles/htdocs/
```

### Linux
```bash
sudo cp -r HRFlowSn /opt/lampp/htdocs/
```

---

## 🗄️ Étape 3 : Créer la base de données

1. Ouvrez votre navigateur web
2. Allez à l'adresse : `http://localhost/phpmyadmin`
3. Cliquez sur **"Nouveau"** en haut à gauche
4. Dans "Nom de la base de données", tapez : `hrflowsn_db`
5. Cliquez sur **"Créer"**
6. Cliquez sur l'onglet **"Importer"** en haut
7. Cliquez sur **"Choisir un fichier"**
8. Sélectionnez le fichier `hrflowsn.sql` dans le dossier HRFlowSn
9. Cliquez sur **"Exécuter"** en bas
10. Vous devriez voir "Import terminé avec succès"

---

## 🚀 Étape 4 : Démarrer l'application

1. Ouvrez votre navigateur web
2. Allez à l'adresse : `http://localhost/HRFlowSn`
3. Vous verrez la page de connexion

---

## 👤 Étape 5 : Créer votre premier compte

1. Cliquez sur **"S'inscrire"** en bas de la page
2. Remplissez les champs :
   - **Email** : votre adresse email
   - **Mot de passe** : choisissez un mot de passe (minimum 6 caractères)
   - **Confirmer le mot de passe** : répétez le même mot de passe
   - **Rôle** : choisissez "Administrateur" pour avoir tous les accès
3. Cliquez sur **"S'inscrire"**
4. Connectez-vous avec votre email et mot de passe

---

## 📊 Étape 6 : Découvrir l'application

Une fois connecté, vous verrez le **Dashboard** avec :
- Le nombre total d'employés
- Les employés actifs
- Les demandes de congé en attente
- Les contrats actifs

Utilisez le menu à gauche pour naviguer :
- **Collaborateurs** : Gérer les employés
- **Contrats** : Gérer les contrats de travail
- **Congés** : Gérer les demandes de congé
- **Formations** : Organiser des formations
- **Évaluations** : Évaluer les employés

---

## ❓ Problèmes fréquents

### "Erreur de connexion à la base de données"
- Vérifiez que MySQL est démarré dans XAMPP (doit être vert)
- Vérifiez que la base de données `hrflowsn_db` existe dans phpMyAdmin

### "Page introuvable / 404"
- Vérifiez que le dossier HRFlowSn est bien dans `htdocs`
- Vérifiez que Apache est démarré dans XAMPP (doit être vert)

### "Mot de passe incorrect"
- Assurez-vous d'utiliser le bon email et mot de passe
- Si vous avez oublié, contactez l'administrateur système

---

## 📞 Besoin d'aide ?

Si vous rencontrez des problèmes :
1. Vérifiez que XAMPP est bien ouvert et que Apache et MySQL sont verts
2. Vérifiez que vous avez bien suivi toutes les étapes
3. Contactez votre équipe technique ou administrateur système

---

## 💡 Conseils

- **Gardez XAMPP ouvert** quand vous utilisez l'application
- **Sauvegardez régulièrement** vos données (exportez depuis phpMyAdmin)
- **Utilisez des mots de passe forts** pour sécuriser votre compte
- **Fermez la session** quand vous quittez votre ordinateur

---

**Félicitations ! Vous êtes maintenant prêt à utiliser HRFlowSn 🎉**
