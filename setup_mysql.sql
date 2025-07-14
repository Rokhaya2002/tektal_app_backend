-- Script pour configurer MySQL sans erreur 1701
-- À exécuter avant php artisan migrate:fresh --seed

-- Désactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Réactiver les vérifications de clés étrangères (à exécuter après les migrations)
-- SET FOREIGN_KEY_CHECKS = 1;
