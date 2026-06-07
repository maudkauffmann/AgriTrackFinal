import React, { useState, useEffect } from 'react';

const FormulaireAction = ({ parcelle, onSuccess }) => {
    const [taches, setTaches] = useState([]);
    const [ouvriers, setOuvriers] = useState([]);
    const [campagnes, setCampagnes] = useState([]);
    const [intrants, setIntrants] = useState([]);

    const [chosenTacheNom, setChosenTacheNom] = useState("");
    const [chosenOuvrier, setChosenOuvrier] = useState("");
    const [chosenCampagne, setChosenCampagne] = useState("");
    const [chosenIntrant, setChosenIntrant] = useState("");
    const [quantiteIntrant, setQuantiteIntrant] = useState("");
    const [dateRealisation, setDateRealisation] = useState(new Date().toISOString().split('T')[0]);

    useEffect(() => {
        const loadData = async () => {
            const token = localStorage.getItem('token');
            const h = { 'Authorization': `Bearer ${token}` };

            // On charge tout en parallèle sans bloquer l'affichage du composant
            fetch('https://127.0.0.1:8000/api/taches', { headers: h }).then(r => r.ok ? r.json() : []).then(setTaches);
            fetch('https://127.0.0.1:8000/api/ouvriers', { headers: h }).then(r => r.ok ? r.json() : []).then(setOuvriers);
            fetch('https://127.0.0.1:8000/api/campagnes', { headers: h }).then(r => r.ok ? r.json() : []).then(setCampagnes);
            fetch('https://127.0.0.1:8000/api/intrants', { headers: h }).then(r => r.ok ? r.json() : []).then(setIntrants);

            if (parcelle?.id_campagne) setChosenCampagne(String(parcelle.id_campagne));
        };
        loadData();
    }, [parcelle]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const token = localStorage.getItem('token');
        const tache = taches.find(t => t.nomTache === chosenTacheNom);

        const actionPayload = {
            uuid: crypto.randomUUID(),
            tache_id: tache ? String(tache.id_tache) : null,
            campagne_id: String(chosenCampagne),
            ouvrier_id: String(chosenOuvrier),
            date_realisation: dateRealisation,
            intrant_id: chosenIntrant || '0',
            quantite_intrant: quantiteIntrant || '0'
        };

        const res = await fetch('https://127.0.0.1:8000/api/synchro/tache', {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ actions: [actionPayload] })
        });
        if (res.ok) onSuccess(); else alert("Erreur d'enregistrement.");
    };

    return (
        <div className="form-action-container">
            <h3 className="box-title">Nouvelle tâche</h3>
            <form className="form-action" onSubmit={handleSubmit}>
                <label>Campagne *</label>
                <select value={chosenCampagne} onChange={e => setChosenCampagne(e.target.value)} required>
                    <option value="">{campagnes.length > 0 ? "-- Choisir une campagne --" : "Chargement..."}</option>
                    {campagnes.map(c => <option key={c.id_campagne} value={c.id_campagne}>{c.nomCampagne}</option>)}
                </select>

                <label>Action *</label>
                <select value={chosenTacheNom} onChange={e => setChosenTacheNom(e.target.value)} required>
                    <option value="">{taches.length > 0 ? "-- Choisir une action --" : "Chargement..."}</option>
                    {taches.map(t => <option key={t.id_tache} value={t.nomTache}>{t.nomTache}</option>)}
                </select>

                <label>Ouvrier *</label>
                <select value={chosenOuvrier} onChange={e => setChosenOuvrier(e.target.value)} required>
                    <option value="">{ouvriers.length > 0 ? "-- Choisir un ouvrier --" : "Chargement..."}</option>
                    {ouvriers.map(o => <option key={o.id_ouvrier} value={o.id_ouvrier}>{o.nomOuvrier}</option>)}
                </select>

                <label>Date de réalisation *</label>
                <input
                    type="date"
                    value={dateRealisation}
                    onChange={e => setDateRealisation(e.target.value)}
                    required
                />

                <label>Intrant</label>
                <select value={chosenIntrant} onChange={e => setChosenIntrant(e.target.value)}>
                    <option value="">{intrants.length > 0 ? "-- Aucun intrant --" : "Chargement..."}</option>
                    {intrants.map(i => <option key={i.id_intrant} value={i.id_intrant}>{i.nomIntrant} (Stock: {i.stock})</option>)}
                </select>

                <label>Quantité</label>
                <input type="number" step="0.01" value={quantiteIntrant} onChange={e => setQuantiteIntrant(e.target.value)} placeholder="0.00" />

                <button type="submit" className="btn-submit-action">Enregistrer l'action</button>
            </form>
        </div>
    );
};

export default FormulaireAction;