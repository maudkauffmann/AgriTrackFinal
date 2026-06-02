import { bddLocale } from '../bddlocale'; // Vérifie que le chemin remonte bien vers ton fichier bddlocale.js

export const synchroniserDonneesHorsLigne = async () => {
    const token = localStorage.getItem('token');
    if (!token) return;

    try {
        const actionsHorsLigne = await bddLocale.taches.toArray();

        if (actionsHorsLigne.length === 0) return;

        console.log(`🔄 Synchro : Envoi automatique de ${actionsHorsLigne.length} actions stockées localement...`);

        // Utilisation de l'adresse fonctionnelle HTTPS 127.0.0.1
        const res = await fetch('https://127.0.0.1:8000/api/synchro/tache', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ actions: actionsHorsLigne })
        });

        if (res.ok) {
            const data = await res.json();
            const uuidsSynises = data.synced_uuids || [];

            if (uuidsSynises.length > 0) {
                // Nettoyage de la base locale uniquement pour les lignes validées par MySQL
                await bddLocale.taches.bulkDelete(uuidsSynises);
                console.log(`✅ Synchro réussie ! ${uuidsSynises.length} lignes envoyées vers MySQL.`);
            }
        } else {
            console.error(`⚠️ Échec de la synchronisation automatique (Statut ${res.status}).`);
        }
    } catch (error) {
        console.error("❌ Erreur réseau lors de la synchronisation en tâche de fond :", error);
    }
};

// Écouteur global pour lancer la synchronisation dès que la connexion internet revient
if (typeof window !== 'undefined') {
    window.addEventListener('online', () => {
        console.log("🌐 Réseau détecté ! Lancement de la synchronisation des données...");
        synchroniserDonneesHorsLigne();
    });
}