<?php

class ParseurRSS {

    // Variables pour l'objet RSS
    protected $_urlRSS;
    protected $_description;
    protected $_language;
    protected $_link;
    protected $_title;

    //CONSTRUCTEUR
    public function __construct() {
        
    }

    // Methode de Parsage de l'élément
    public function parser($urlRSS, $nbElement = null, $tagHtml = null) {
        //Passer lurl rss à l'objet
        $this->_urlRSS = $urlRSS;

        //Charger les données du RSS
        $rss = simplexml_load_file($this->_urlRSS);

        //Si le Rss est chargé
        if ($rss) {
            //Vérifier si la langue existe
            if (!empty($rss->channel->language)) {
                $this->_language = (string) $rss->channel->language;
            }

            //Vérifier si le titre existe
            if (!empty($rss->channel->title)) {
                $this->_title = (string) $rss->channel->title;
            }

            //Vérifier si la description existe
            if (!empty($rss->channel->description)) {
                $this->_description = (string) $rss->channel->description;
            }

            //Vérifier si le lien existe
            if (!empty($rss->channel->link)) {
                $this->_link = (string) $rss->channel->link;
            }

            //Initialisation du compteur
            $i = 0;

            //Parcourir les Item du RSS
            foreach ($rss->channel->item as $item) {
                //Récupérer les différents infos de l'item
                $itemTitle = (string) $item->title;
                $itemPubDate = date("d/m/Y H:i",strtotime ( $item->pubDate));
                $itemLink = (string) $item->link;
                $itemDescription = (string) $item->description;
                $itemCategory = (string) $item->category;

                // Si on veut pas des tags html.
                if ($tagHtml == false) {
                    //On les vire
                    $itemDescription = strip_tags($itemDescription);
                }

                //Compteur pour les catégories
                $y = 0;

                //Pour les catégories de chaque Item
                foreach ($item->category as $cat) {
                    // Stocker dans un tableau
                    $itemCategory[$y] = (string) $cat;

                    // Incrémenter
                    $y++;
                }
                //Remplir le tableau associatif contenant les élements ciblés du RSS
                if (isset($itemCategory) && !empty($itemCategory))
                    $retourParser[$i] = array('title' => $itemTitle, 'pubDate' => $itemPubDate, 'description' => $itemDescription, 'link' => $itemLink, 'category' => $itemCategory);
                else
                    $retourParser[$i] = array('title' => $itemTitle, 'pubDate' => $itemPubDate, 'description' => $itemDescription, 'link' => $itemLink, 'category' => "");

                //Incrémenter
                $i++;

                //Si le nombre de cellule est égale au nombre d'Item voulu
                if (count($retourParser) == $nbElement) {
                    //On arrête la boucle
                    break;
                }
            }
            //Retourner le tableau
            return $retourParser;
        } else {
            return "Impossible de charger le flux RSS.";
        }
    }

    public function getTitle() {
        return $this->_title;
    }

    public function getLink() {
        return $this->_link;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function getLanguage() {
        return $this->_language;
    }

    public function getUrlRss() {
        return $this->_urlRSS;
    }

}
?>