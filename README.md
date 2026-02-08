
Module HumHub permettant de tronquer le contenu les publications dans les emails de résumé d'activité pour améliorer la lisibilité et réduire la taille des emails.

## 🎯 Fonctionnalités
* **Troncature automatique du contenu** : Limite le contenu des publications dans les emails de résumé à un nombre de caractères configurable
* **Configuration administrateur** : Panneau de paramètres simple d'utilisation dans l'administration HumHub
* **Activation/Désactivation** : Possibilité d'activer ou de désactiver la troncature sans désinstaller le module

## 📋 Prérequis
**HumHub** : Version 1.8 ou supérieure

## 🚀 Installation
Télécharger la dernière version packagée ou cloner ce dépôt.
Téléverser les fichiers du module dans `protected/modules/limitsummarymail/`

## ⚙️ Configuration
### Paramètres
|Paramètre|Description|Valeur par défaut|
|---|---|---|
|**Activer la troncature**|Activer/désactiver la troncature du contenu|Activé|
|**Limite de caractères**|Nombre maximum de caractères à afficher|500|
## 🔧 Fonctionnement
Le module intercepte le rendu des emails de résumé d'activité et traite le contenu HTML :
1. **Interception de la vue** : Utilise `EVENT_AFTER_RENDER` de Yii2 pour capturer le HTML de l'email
1. **Analyse DOM** : Analyse la structure HTML en utilisant DOMDocument
1. **Troncature intelligente** : Identifie les nœuds de texte dépassant la limite configurée
1. **Sortie propre** : Tronque le contenu avec des points de suspension "..."
1. **Méthode de secours** : Utilise le traitement par expression régulière si l'analyse DOM échoue

## 🐛 Dépannage
### Le module ne fonctionne pas ?
* Vider le cache
* Vérifier les logs (protected/runtime/logs/app.log)
* Vérifier que le module est activé dans Administration → Modules
* Vérifier que la limite de caractères est correctement définie (1-500)

## 📝 Structure des fichiers
limitsummarymail/
├── Module.php                    # Classe principale du module
├── config.php                    # Configuration du module
├── module.json                   # Métadonnées du module
├── Events.php                    # Gestionnaires d'événements
├── components/
│   └── MailSummaryProcessor.php  # Logique de traitement HTML
├── controllers/
│   └── AdminController.php       # Contrôleur du panneau d'administration
├── models/
│   └── ConfigureForm.php         # Modèle du formulaire de configuration
├── views/
│   └── admin/
│       └── index.php             # Vue de configuration
├── messages/                     # Traductions
│   ├── en/
│   └── fr/
├── migrations/
│   └── uninstall.php             # Nettoyage lors de la désinstallation
└── resources/
    └── module_image.png          # Icône du module

## 🌐 Traductions
Langues disponibles :
* **Français** (fr) - Par défaut
* **Anglais** (en)
Pour ajouter une nouvelle langue, créer un dossier dans `messages/` avec le code de la langue et copier `base.php` depuis le dossier `fr`.

## 🤝 Contribution
Les contributions sont les bienvenues ! Veuillez :
1. Forker le dépôt
1. Créer une branche de fonctionnalité (`git checkout -b feature/nouvelle-fonctionnalite`)
1. Commiter vos modifications (`git commit -m 'Ajouter nouvelle fonctionnalite'`)
1. Pousser vers la branche (`git push origin feature/nouvelle-fonctionnalite`)
1. Ouvrir une Pull Request
## 📄 Licence
Ce projet est sous licence **AGPL-3.0-or-later** 

## 🙏 Remerciements
* Construit pour [HumHub](https://www.humhub.com/) - Le kit de réseau social open source flexible
* **Et la superbe communauté francophone sous ** : [Communauté HumHub](https://community.humhub.com/)

**Fait avec ❤️ pour la communauté HumHub**
