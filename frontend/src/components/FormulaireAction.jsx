import React, { useState, useEffect } from 'react';
import { bddLocale } from '../bddlocale';

const FormulaireAction = ({ parcelle, onSuccess }) => {
    // Listes pour alimenter les selects
    const [taches, setTaches] = useState([]);
    const [ouvriers, setOuvriers] = useState([]);
    const [campagnes, setCampagnes] = useState([]);
    const [intrants, setIntrants] = useState([]);

    // États du formulaire pour CHAQUE attribut de la table
    const [chosenTacheNom, setChosenTacheNom] = useState("");
    const [chosenOuvrier, setChosenOuvrier] = useState("");
    const [chosenCampagne, setChosenCampagne] = useState("");
    const [chosenIntrant, setChosenIntrant] = useState("");
    const [quantiteIntrant, setQuantiteIntrant] = useState("");
    // Initialise la date de réalisation avec la date du jour au format YYYY-MM-DD
    const [dateRealisation, setDateRealisation] = useState(new Date().toISOString().split('T')[0]);

    useEffect(() => {
        const token = localStorage.getItem('token');
        const headers = {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        };

        // Si la parcelle change, on pré-sélectionne sa campagne par défaut si elle existe
        if (parcelle?.id_campagne || parcelle?.campagne_id) {
            setChosenCampagne(String(parcelle.id_campagne || parcelle.campagne_id));
        }

        // 1. Charger les tâches
        fetch('https://127.0.0.1:8000/api/taches', { headers })
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                if (data && data.length > 0) {
                    setTaches(data);
                    localStorage.setItem('cached_taches', JSON.stringify(data));
                }
            })
            .catch(() => {
                const localData = localStorage.getItem('cached_taches');
                setTaches(localData ? JSON.parse(localData) : []);
            });

        // 2. Charger les ouvriers
        fetch('https://127.0.0.1:8000/api/ouvriers', { headers })
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                if (data && data.length > 0) {
                    setOuvriers(data);
                    localStorage.setItem('cached_ouvriers', JSON.stringify(data));
                }
            })
            .catch(() => {
                const localData = localStorage.getItem('cached_ouvriers');
                setOuvriers(localData ? JSON.parse(localData) : []);
            });

        // 3. Charger les campagnes
        fetch('https://127.0.0.1:8000/api/campagnes', { headers })
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                if (data && data.length > 0) {
                    setCampagnes(data);
                    localStorage.setItem('cached_campagnes', JSON.stringify(data));
                }
            })
            .catch(() => {
                const localData = localStorage.getItem('cached_campagnes');
                setCampagnes(localData ? JSON.parse(localData) : []);
            });

        // 4. Charger les intrants
        fetch('https://127.0.0.1:8000/api/intrants', { headers })
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                if (data && data.length > 0) {
                    setIntrants(data);
                    localStorage.setItem('cached_intrants', JSON.stringify(data));
                }
            })
            .catch(() => {
                const localData = localStorage.getItem('cached_intrants');
                setIntrants(localData ? JSON.parse(localData) : []);
            });
    }, [parcelle]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const token = localStorage.getItem('token');

        const tacheSelectionnee = taches.find(t => t.nomTache === chosenTacheNom);
        const tacheId = tacheSelectionnee ? (tacheSelectionnee.id_tache || tacheSelectionnee.id) : null;

        // Validation stricte uniquement sur ce que la table 'realiser' exige obligatoirement
        if (!tacheId || !chosenCampagne || !chosenOuvrier || !dateRealisation) {
            console.error("❌ Données obligatoires manquantes");
            alert("Erreur : Veuillez renseigner tous les champs obligatoires (*).");
            return;
        }

        // Nettoyage crucial : transforme les chaînes vides "" du select en vrais null pour MySQL
        const cleanIntrantId = chosenIntrant && chosenIntrant !== "" ? String(chosenIntrant) : null;
        const cleanQuantite = quantiteIntrant && quantiteIntrant !== "" ? String(quantiteIntrant) : null;

        // Payload nettoyé et 100% en phase avec les colonnes de la BDD
        const actionPayload = {
            uuid: crypto.randomUUID(),
            nom: chosenTacheNom,
            tache_id: String(tacheId),
            campagne_id: String(chosenCampagne),
            ouvrier_id: String(chosenOuvrier),
            date_realisation: dateRealisation, // Format YYYY-MM-DD attendu par le type 'date' de MySQL
            intrant_id: cleanIntrantId,
            quantite_intrant: cleanQuantite
        };

        if (!navigator.onLine) {
            await bddLocale.taches.add(actionPayload);
            if (onSuccess) onSuccess();
            return;
        }

        try {
            const res = await fetch('https://127.0.0.1:8000/api/synchro/tache', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ actions: [actionPayload] })
            });

            if (res.ok) {
                console.log("✅ Action enregistrée avec succès en BDD MySQL !");
            } else {
                await bddLocale.taches.add(actionPayload);
                console.warn(`⚠️ Statut serveur ${res.status} reçu. Enregistré localement dans Dexie.`);
            }
        } catch (err) {
            await bddLocale.taches.add(actionPayload);
            console.error("❌ Échec réseau. Sauvegardé localement dans Dexie :", err);
        }

        // Reset du formulaire après envoi
        setChosenTacheNom("");
        setChosenOuvrier("");
        setChosenIntrant("");
        setQuantiteIntrant("");
        setDateRealisation(new Date().toISOString().split('T')[0]);
        if (onSuccess) onSuccess();
    };

    return (
        <div className="form-action-container" style={{ padding: '15px', border: '1px solid #ccc', borderRadius: '5px', background: '#fff' }}>
            <div className="parcelle-details-mini">
                Fiche Détails : <strong>{parcelle?.nomParcelle || 'Parcelle inconnue'}</strong> <br />
                Superficie : <strong>{parcelle?.superficieParc || 0} ha</strong>
            </div>
            <hr />
            <h3>Nouvelle tâche sur la parcelle</h3>
            <form onSubmit={handleSubmit} className="form-action">

                {/* 1. Sélection de la Campagne (Obligatoire) */}
                <label htmlFor="campagne-select">Campagne * :</label>
                <select id="campagne-select" value={chosenCampagne} onChange={e => setChosenCampagne(e.target.value)} required style={{ width: '100%', padding: '5px', margin: '5px 0' }}>
                    <option value="">-- Choisir une campagne --</option>
                    {campagnes.map((c, i) => (
                        <option key={c.id_campagne || i} value={c.id_campagne}>{c.nomCampagne || c.libelle || `Campagne ${c.id_campagne}`}</option>
                    ))}
                </select>

                {/* 2. Sélection du Type d'action / Tâche (Obligatoire) */}
                <label htmlFor="tache-select">Type d'action * :</label>
                <select id="tache-select" value={chosenTacheNom} onChange={e => setChosenTacheNom(e.target.value)} required style={{ width: '100%', padding: '5px', margin: '5px 0' }}>
                    <option value="">-- Choisir une tâche --</option>
                    {taches.map((t, i) => (
                        <option key={t.id_tache || i} value={t.nomTache}>{t.nomTache}</option>
                    ))}
                </select>

                {/* 3. Sélection de l'Ouvrier (Obligatoire) */}
                <label htmlFor="ouvrier-select">Ouvrier * :</label>
                <select id="ouvrier-select" value={chosenOuvrier} onChange={e => setChosenOuvrier(e.target.value)} required style={{ width: '100%', padding: '5px', margin: '5px 0' }}>
                    <option value="">-- Choisir un ouvrier --</option>
                    {ouvriers.map((o, i) => (
                        <option key={o.id_ouvrier || i} value={o.id_ouvrier}>{o.nomOuvrier}</option>
                    ))}
                </select>

                {/* 4. Sélection de la Date de Réalisation (Obligatoire) */}
                <label htmlFor="date-realisation">Date de réalisation * :</label>
                <input id="date-realisation" type="date" value={dateRealisation} onChange={e => setDateRealisation(e.target.value)} required style={{ width: '100%', padding: '5px', margin: '5px 0' }} />

                {/* 5. Sélection de l'Intrant (Optionnel) */}
                <label htmlFor="intrant-select">Intrant utilisé (Optionnel) :</label>
                <select id="intrant-select" value={chosenIntrant} onChange={e => setChosenIntrant(e.target.value)} style={{ width: '100%', padding: '5px', margin: '5px 0' }}>
                    <option value="">-- Aucun intrant utilisé --</option>
                    {intrants.map((intrant, i) => (
                        <option key={intrant.id_intrant || i} value={intrant.id_intrant}>{intrant.nomIntrant || intrant.nom}</option>
                    ))}
                </select>

                {/* 6. Saisie de la Quantité d'Intrant (Optionnel) */}
                <label htmlFor="quantite-input">Quantité d'intrant (Optionnel) :</label>
                <input id="quantite-input" type="number" step="0.01" min="0" value={quantiteIntrant} onChange={e => setQuantiteIntrant(e.target.value)} placeholder="Ex: 2.5" style={{ width: '100%', padding: '5px', margin: '5px 0' }} />

                <button type="submit" style={{ marginTop: '15px', padding: '10px', width: '100%', cursor: 'pointer', backgroundColor: '#2e7d32', color: '#fff', border: 'none', borderRadius: '4px', fontWeight: 'bold' }}>
                    Enregistrer la tâche complétée
                </button>
            </form>
        </div>
    );
};

export default FormulaireAction;