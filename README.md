# Homeblock
Plugin homeblock for Magix CMS 3

Ajouter un block de contenu sur la page d'accueil de votre site internet.


## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner homeblock (block supplémentaire sur la page d'accueil).
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.
 * Copier le contenu du dossier skin/public dans le dossier de votre skin.

### Ajouter dans home.tpl la ligne suivante

```smarty
{block name="main:after"}
    {include file="homeblock/brick/homeblock.tpl"}
{/block}
````