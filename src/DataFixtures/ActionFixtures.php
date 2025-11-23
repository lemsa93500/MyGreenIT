<?php

namespace App\DataFixtures;

use App\Entity\Action;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTimeImmutable();

        $rows = [
            // PC
            ['PC', 1, 5,  'Éteindre l’écran quand tu quittes 10+ min', "Coupe l’écran au lieu de laisser une vidéo tourner en fond."],
            ['PC', 1, 5,  'Baisser la luminosité écran à 60 %', "Réduit la conso et repose tes yeux."],
            ['PC', 2, 8,  'Activer la mise en veille auto à 5 minutes', "Évite l’oubli, économise à chaque pause."],
            ['PC', 2, 8,  'Fermer les applis en arrière-plan inutiles', "Moins de CPU, moins d’énergie."],
            ['PC', 3, 12, 'Programmer l’arrêt du PC la nuit', "Stopper les veilles fantômes quand tu dors."],
            ['PC', 3, 12, 'Activer un mode sombre système', "Moins d’énergie sur écran OLED et plus de confort."],
            ['PC', 4, 16, 'Ranger et fermer 20 onglets inutiles', "Stoppe la bouffe RAM et CPU."],
            ['PC', 5, 20, 'Désinstaller 5 logiciels non utilisés', "Allège le système, moins d’updates, moins d’énergie."],

            // Mail
            ['Mail', 1, 5,  'Supprimer 50 emails publicitaires', "Moins de stockage, moins d’empreinte."],
            ['Mail', 2, 8,  'Se désabonner de 3 newsletters inutiles', "Stoppe le spam récurrent à la source."],
            ['Mail', 2, 8,  'Vider la corbeille et les spams', "Le stockage a un coût énergétique côté serveur."],
            ['Mail', 3, 12, 'Archiver proprement les pièces jointes locales', "Réduit les doublons mail/cloud."],
            ['Mail', 4, 16, 'Règle auto pour trier les promos', "Less is more dans ta boîte de réception."],

            // Réseau
            ['Réseau', 1, 5,  'Couper le Wi-Fi du PC quand inutile', "Simple, efficace."],
            ['Réseau', 2, 8,  'Couper le partage de connexion mobile', "Évite la charge radio inutile."],
            ['Réseau', 3, 12, 'Planifier updates lourdes la nuit', "Lisse la demande réseau/énergie."],
            ['Réseau', 4, 16, 'Limiter le streaming à 720p si écoute audio', "L’audio n’a pas besoin de 4K."],

            // Cloud / Stockage
            ['Cloud', 1, 5,  'Vider la corbeille du cloud', "Nettoyage simple côté serveurs."],
            ['Cloud', 2, 8,  'Supprimer 1 Go de doublons', "Photos, vidéos, ISO oubliées..."],
            ['Cloud', 3, 12, 'Basculer un dossier en archive froide', "Moins cher, moins énergivore."],
            ['Cloud', 4, 16, 'Compresser un dossier ancien', "Moins de stockage, transferts plus légers."],
            ['Cloud', 5, 20, 'Politique de rétention 90 jours', "Automatise l’hygiène de données."],

            // Développement
            ['Dev', 1, 5,  'Supprimer vendor/.cache inutile', "Nettoie les artefacts locaux."],
            ['Dev', 2, 8,  'Limiter CI aux branches concernées', "Moins de jobs, même qualité."],
            ['Dev', 3, 12, 'Ajouter .dockerignore/.gitignore propres', "Évite d’envoyer des montagnes de fichiers."],
            ['Dev', 4, 16, 'Images Docker basées sur alpine', "Images légères, moins de transfert et stockage."],
            ['Dev', 5, 20, 'Activer build cache + registry local', "Évite pulls/pushs redondants."],

            // Matériel / Alim
            ['Matériel', 1, 5,  'Débrancher le chargeur inutilisé', "Les chargeurs chauffent pour rien."],
            ['Matériel', 2, 8,  'Multiprise avec interrupteur', "Stoppe les veilles fantômes."],
            ['Matériel', 3, 12, 'Nettoyer ventilations PC', "Moins de chauffe, moins de ventilation."],
            ['Matériel', 4, 16, 'Passer au SSD si encore HDD', "Consomme moins, plus rapide."],

            // Bureau / Habitudes
            ['Bureau', 1, 5,  'Fermer les fenêtres d’app inutiles', "Le chaos numérique consomme."],
            ['Bureau', 2, 8,  'Regrouper 3 tâches (batch)', "Moins de wakeups CPU."],
            ['Bureau', 3, 12, 'Pomodoro pour tâches lourdes', "Réduit les lancements inutiles."],
            ['Bureau', 4, 16, 'Profil d’énergie optimisé', "Mise en veille agressive."],

            // Mobile
            ['Mobile', 1, 5,  'Désactiver l’actualisation arrière-plan pour 3 apps', "Gagne batterie et data."],
            ['Mobile', 2, 8,  'Passer en 4G si 5G capte mal', "Plus stable, parfois moins énergivore."],
            ['Mobile', 3, 12, 'Désinstaller 10 apps inutiles', "Moins de sync, moins de push."],
        ];

        foreach ($rows as [$cat, $diff, $points, $title, $desc]) {
            $a = new Action();
            $a->setTitle($title);
            $a->setDescription($desc);
            $a->setBasePoints($points);
            $a->setCategory($cat);
            $a->setDifficulty($diff);
            $a->setIsActive(true);
            $a->setCreatedAt($now);
            $manager->persist($a);
        }

        $manager->flush();
    }
}
