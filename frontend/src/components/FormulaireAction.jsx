import React, { useState, useEffect } from 'react';

const FormulaireAction = ({ parcelle, onSuccess }) => {
    const [taches, setTaches] = useState([]);
    const [ouvriers, setOuvriers] = useState([]);
    const [campagnes, setCampagnes] = useState([]);
    const [intrants, setIntrants] = useState([]);
    const [perms, setPerms] = useState({ creationOuvrier: false });
    const [loading, setLoading] = useState(true);
    const [showAjoutOuvrier, setShowAjoutOuvrier] = useState(false);
    const [nouvelOuvrierNom, setNouvelOuvrierNom] = useState("");
    const [nouvelOuvrierTel, setNouvelOuvrierTel] = useState("");
    const [chosenTacheNom, setChosenTacheNom] = useState("");
    const [chosenOuvrier, setChosenOuvrier] = useState("");
    const [chosenCampagne, setChosenCampagne] = useState("");
    const [chosenIntrant, setChosenIntrant] = useState("");
    const [quantiteIntrant, setQuantiteIntrant] = useState("");
    const [dateRealisation, setDateRealisation] = useState(new Date().toISOString().split('T')[0]);

    useEffect(() => {
        const loadData = async () => {
            const h = { 'Authorization': `Bearer ${localStorage.getItem('token')}` };
            try {
                const [rT, rO, rC, rI, rM] = await Promise.all([
                    fetch('https://127.0.0.1:8000/api/taches', { headers: h }),
                    fetch('https://127.0.0.1:8000/api/ouvriers', { headers: h }),
                    fetch('https://127.0.0.1:8000/api/campagnes', { headers: h }),
                    fetch('https://127.0.0.1:8000/api/intrants', { headers: h }),
                    fetch('https://127.0.0.1:8000/api/me', { headers: h })
                ]);
                setTaches(rT.ok ? await rT.json() : []);
                setOuvriers(rO.ok ? await rO.json() : []);
                setCampagnes(rC.ok ? await rC.json() : []);
                setIntrants(rI.ok ? await rI.json() : []);
                const meData = rM.ok ? await rM.json() : { permissions: {} };
                setPerms(meData.permissions || {});
                if (parcelle?.id_campagne) setChosenCampagne(String(parcelle.id_campagne));
            } catch (err) {
                console.error("Erreur chargement:", err);
            }
            setLoading(false);
        };
        loadData();
    }, [parcelle]);

    const handleAjouterOuvrier = async () => {
        if (!nouvelOuvrierNom || !nouvelOuvrierTel) return alert("Champs manquants");
        const h = { 'Authorization': `Bearer ${localStorage.getItem('token')}`, 'Content-Type': 'application/json' };

        const res = await fetch('https://127.0.0.1:8000/api/ouvriers/create', {
            method: 'POST', headers: h,
            body: JSON.stringify({ nomOuvrier: nouvelOuvrierNom, telOuvrier: nouvelOuvrierTel })
        });

        if (res.ok) {
            const data = await res.json();
            setOuvriers([...ouvriers, { id_ouvrier: data.id_ouvrier, nomOuvrier: nouvelOuvrierNom }]);
            setChosenOuvrier(data.id_ouvrier);
            setNouvelOuvrierNom(""); setNouvelOuvrierTel(""); setShowAjoutOuvrier(false);
        } else {
            alert("Erreur lors de la création de l'ouvrier.");
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const payload = {
            uuid: crypto.randomUUID(),
            tache_id: taches.find(t => t.nomTache === chosenTacheNom)?.id_tache,
            campagne_id: chosenCampagne,
            ouvrier_id: chosenOuvrier,
            date_realisation: dateRealisation,
            intrant_id: chosenIntrant || '0',
            quantite_intrant: quantiteIntrant || '0'
        };

        const res = await fetch('https://127.0.0.1:8000/api/synchro/tache', {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ actions: [payload] })
        });
        if (res.ok) onSuccess(); else alert("Erreur d'enregistrement de l'action.");
    };

    return (
        <div className="form-action-container">
            <h3 className="box-title">Nouvelle tâche</h3>
            <form className="form-action" onSubmit={handleSubmit}>
                <label>Campagne *</label>
                <select value={chosenCampagne} onChange={e => setChosenCampagne(e.target.value)} required>
                    {loading ? <option>Chargement...</option> : (
                        <>
                            <option value="">-- Choisir une campagne --</option>
                            {campagnes.map(c => <option key={c.id_campagne} value={c.id_campagne}>{c.nomCampagne}</option>)}
                        </>
                    )}
                </select>

                <label>Action *</label>
                <select value={chosenTacheNom} onChange={e => setChosenTacheNom(e.target.value)} required>
                    <option value="">-- Choisir une action --</option>
                    {taches.map(t => <option key={t.id_tache} value={t.nomTache}>{t.nomTache}</option>)}
                </select>

                <label>Ouvrier *</label>
                <select value={chosenOuvrier} onChange={e => setChosenOuvrier(e.target.value)} required>
                    <option value="">-- Choisir un ouvrier --</option>
                    {ouvriers.map(o => <option key={o.id_ouvrier} value={o.id_ouvrier}>{o.nomOuvrier}</option>)}
                </select>

                {perms.creationOuvrier && (
                    <button type="button" onClick={() => setShowAjoutOuvrier(!showAjoutOuvrier)}>+ Ajouter un ouvrier</button>
                )}

                {showAjoutOuvrier && (
                    <div className="add-ouvrier-box">
                        <input placeholder="Nom" value={nouvelOuvrierNom} onChange={e => setNouvelOuvrierNom(e.target.value)} />
                        <input placeholder="Téléphone" value={nouvelOuvrierTel} onChange={e => setNouvelOuvrierTel(e.target.value)} />
                        <button type="button" onClick={handleAjouterOuvrier}>Valider</button>
                    </div>
                )}

                <label>Date *</label>
                <input type="date" value={dateRealisation} onChange={e => setDateRealisation(e.target.value)} required />

                <label>Intrant</label>
                <select value={chosenIntrant} onChange={e => setChosenIntrant(e.target.value)}>
                    <option value="">-- Aucun intrant --</option>
                    {intrants.map(i => <option key={i.id_intrant} value={i.id_intrant}>{i.nomIntrant}</option>)}
                </select>

                <label>Quantité</label>
                <input type="number" step="0.01" value={quantiteIntrant} onChange={e => setQuantiteIntrant(e.target.value)} placeholder="0.00" />

                <button type="submit" className="btn-submit-action">Enregistrer l'action</button>
            </form>
        </div>
    );
};

export default FormulaireAction;