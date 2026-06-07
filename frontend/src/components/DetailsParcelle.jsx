import React, { useState, useEffect } from 'react';
import FormulaireAction from './FormulaireAction.jsx';

const DetailsParcelle = ({ parcelle, onBack }) => {
    const [actionsCampagne, setActionsCampagne] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [showForm, setShowForm] = useState(false);

    const parcelleId = parcelle?.id_parcelle || parcelle?.id;

    const fetchActions = async () => {
        if (!parcelleId) return;

        const token = localStorage.getItem('token');
        try {
            const response = await fetch(`https://127.0.0.1:8000/api/parcelles/${parcelleId}/actions`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (response.ok) {
                const data = await response.json();
                console.log("JSON reçu du serveur :", data);
                setActionsCampagne(Array.isArray(data) ? data : []);
                setError(null);
            } else {
                setError("Impossible de charger l'historique.");
            }
        } catch (err) {
            setError("Erreur réseau.");
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (parcelleId) fetchActions();
    }, [parcelleId]);

    return (
        <div className="details-parcelle-container">
            <button onClick={onBack} className="btn-back">← Retour aux parcelles</button>

            <div className="parcelle-header-box">
                <h2>Fiche Détails : <span className="highlight-text">{parcelle?.nomParcelle || 'Nom inconnu'}</span></h2>
            </div>

            <div className="campagne-section">
                <div className="campagne-header">
                    <h3>Suivi de la campagne</h3>
                    <button onClick={() => setShowForm(!showForm)} className="btn-trigger-action">
                        {showForm ? "✖ Annuler" : "➕ Enregistrer une action"}
                    </button>
                </div>

                {showForm && <FormulaireAction parcelle={parcelle} onSuccess={() => { setShowForm(false); fetchActions(); }} />}

                <h4 className="sub-title">Historique</h4>
                <div className="actions-list">
                    {loading ? <div className="loading-message">Chargement...</div> : error ? <div className="error-message">{error}</div> : actionsCampagne.length > 0 ? (
                        actionsCampagne.map((rawAction, index) => {
                            // NORMALISATION : On force toutes les clés en minuscules pour éviter les erreurs
                            const a = Object.keys(rawAction).reduce((acc, key) => { acc[key.toLowerCase()] = rawAction[key]; return acc; }, {});

                            return (
                                <div key={a.id_realisation || index} className="action-item-card">
                                    <div className="action-info">
                                        <span className="action-badge">🛠️ {a.nomtache || "Action"}</span>
                                        <span className="action-worker">👤 {a.nomouvrier || "Inconnu"}</span>
                                        {a.nomintrant && (
                                            <div className="action-intrant-details">
                                                📦 {a.nomintrant} : <strong>{a.quantiteintrant} {a.unite}</strong>
                                            </div>
                                        )}
                                    </div>
                                    <div className="action-date">
                                        📅 {a.daterealisation ? new Date(a.daterealisation).toLocaleDateString() : 'Date inconnue'}
                                    </div>
                                </div>
                            );
                        })
                    ) : <div className="empty-message">Aucune action enregistrée.</div>}
                </div>
            </div>
        </div>
    );
};

export default DetailsParcelle;