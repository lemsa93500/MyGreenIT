<?php

namespace App\DataFixtures;

use App\Entity\Action;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $em): void
    {
        $rows = [
            // title, description, points, category, difficulty, isActive
            ['Éteindre l’écran quand tu quittes le poste', 'Réduire la luminosité/veille écran quand tu t’absentes.', 3, 'Bureau', 1, true],
            ['Fermer les apps lourdes inutiles', 'Quitte les IDE/VM non utilisés pour libérer CPU/RAM.', 4, 'PC', 2, true],
            ['Débrancher le chargeur la nuit', 'Le bloc consomme en veille. Débranche hors usage.', 5, 'Matériel', 1, true],
            ['Activer la mise en veille à 5 min', 'Réglage économie d’énergie sur l’OS.', 4, 'PC', 1, true],
            ['Nettoyer le bureau (fichiers) et corbeille', 'Moins d’indexation, moins d’I/O.', 3, 'PC', 1, true],
            ['Archiver sur cloud froid', 'Déplacer gros fichiers sur stockage “cold”.', 6, 'Cloud', 2, true],
            ['Limiter les pièces jointes lourdes', 'Préférer liens expirables.', 4, 'Mail', 1, true],
            ['Compresser images avant upload', 'WebP/AVIF selon besoin.', 6, 'Dev', 2, true],
            ['Couper les onglets auto-refresh', 'Désactiver les auto-reload inutiles.', 5, 'Réseau', 2, true],
            ['Mettre à jour le navigateur', 'Mieux optimisé, moins gourmand.', 3, 'PC', 1, true],
            ['Mode sombre + réduction éclairage', 'Moins de consommation écran.', 2, 'Mobile', 1, true],
            ['Planifier extinction VM/containers', 'Arrêt auto hors plages de dev.', 8, 'Dev', 3, true],
        ];

        foreach ($rows as [$t,$d,$p,$c,$diff,$active]) {
            $a = new Action();
            $a->setTitle($t);
            $a->setDescription($d);
            $a->setBasePoints($p);
            $a->setCategory($c);
            $a->setDifficulty($diff);
            $a->setIsActive($active);
            $a->setCreatedAt(new \DateTimeImmutable());
            $em->persist($a);
        }
        $em->flush();
    }
}
