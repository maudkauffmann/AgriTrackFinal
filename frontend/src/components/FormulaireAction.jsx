import React, { useState, useEffect } from 'react';

const FormulaireAction = ({ parcelle, onSuccess }) => {
    const [taches, setTaches] = useState([]);
    const [ouvriers, setOuvriers] = useState([]);
    const [chosenTache, setChosenTache] = useState("");
    const [chosenOuvrier, setChosenOuvrier] = useState("");
    const [chosenDate, setChosenDate] = useState(new Date().toISOString().split('T')[0]);

    useEffect(() => {
        // on tente de récupérer le cache si on est hors-ligne
        const token = localStorage.getItem('token');
        const headers = { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' };

        fetch('https://127.0.0.1:8000/api/taches', { headers })
            .then(res => res.json())
            .then(data => {
                const list = Array.isArray(data) ? data : (data['hydra:member'] || []);
                setTaches(list);
                localStorage.setItem('cached_taches', JSON.stringify(list)); // Sauvegarde pour le hors-ligne
            })
            .catch(() => {
                // Si le serveur est inaccessible, on charge le cache
                const cached = localStorage.getItem('cached_taches');
                if (cached) setTaches(JSON.parse(cached));
            });

        fetch('https://127.0.0.1:8000/api/ouvriers', { headers })
            .then(res => res.json())
            .then(data => {
                const list = Array.isArray(data) ? data : (data['hydra:member'] || []);
                setOuvriers(list);
                localStorage.setItem('cached_ouvriers', JSON.stringify(list));
            })
            .catch(() => {
                const cached = localStorage.getItem('cached_ouvriers');
                if (cached) setOuvriers(JSON.parse(cached));
            });
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const token = localStorage.getItem('token');

        const actionPayload = {
            tacheId: chosenTache,
            ouvrierId: chosenOuvrier,
            dateRealisation: chosenDate,
            parcelleId: parcelle.id,
            nomParcelle: parcelle.nomParcelle // pour l'affichage temporaire
        };

        // Si le navigateur est HORS-LIGNE
        if (!navigator.onLine) {
            // On récupère la file d'attente existante ou on en crée une vide
            const offlineQueue = JSON.parse(localStorage.getItem('offline_actions') || '[]');
            offlineQueue.push(actionPayload);
            localStorage.setItem('offline_actions', JSON.stringify(offlineQueue));

            alert("📴 Mode hors-ligne : L'action a été enregistrée localement sur votre téléphone. Elle sera envoyée dès que vous retrouverez du réseau !");
            onSuccess();
            return;
        }

        // Si on est en ligne, envoi classique
        try {
            const res = await fetch(`https://127.0.0.1:8000/api/parcelles/${parcelle.id}/creer-action`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                body: JSON.stringify(actionPayload)
            });

            if (res.ok) {
                alert("🚀 Action enregistrée en ligne avec succès !");
                onSuccess();
            } else {
                alert("⚠️ Erreur serveur lors de l'enregistrement.");
            }
        } catch (err) {
            console.error(err);
        }
    };

    return (
        <div className="form-action-container">
            <h3>Nouvelle tâche sur la parcelle</h3>
            <form onSubmit={handleSubmit} className="form-action">

                <label htmlFor="tache-select">Type d'action :</label>
                <select id="tache-select" value={chosenTache} onChange={e => setChosenTache(e.target.value)} required>
                    <option value="">-- Choisir une tâche --</option>
                    {taches.map(t => <option key={t.id} value={t.id}>{t.nomTache || t.nom}</option>)}
                </select>

                <label htmlFor="ouvrier-select">Ouvrier :</label>
                <select id="ouvrier-select" value={chosenOuvrier} onChange={e => setChosenOuvrier(e.target.value)} required>
                    <option value="">-- Choisir un ouvrier --</option>
                    {ouvriers.map(o => <option key={o.id} value={o.id}>{o.nomOuvrier || o.nomUtilisateur}</option>)}
                </select>

                <label htmlFor="date-input">Date d'exécution :</label>
                <input id="date-input" type="date" value={chosenDate} onChange={e => setChosenDate(e.target.value)} required />

                <button type="submit" className="btn-submit-action">
                    Enregistrer l'action
                </button>
            </form>
        </div>
    );
};

export default FormulaireAction;