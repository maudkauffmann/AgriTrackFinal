import React, { useState, useEffect } from 'react';
import FormulaireAction from './FormulaireAction.jsx';

const DetailsParcelle = ({ parcelle, onBack }) => {
    const [actionsCampagne, setActionsCampagne] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [showForm, setShowForm] = useState(false);

    const fetchActions = async () => {
        const token = localStorage.getItem('token');
        try {
            const response = await fetch(`https://127.0.0.1:8000/api/parcelles/${parcelle.id}/actions`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                setActionsCampagne(Array.isArray(data) ? data : []);
            } else {
                setError("Impossible de charger l'historique.");
            }
        } catch (err) {
            setError("Erreur réseau lors de la récupération des actions.");
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (parcelle?.id) {
            fetchActions();
        }
    }, [parcelle]);

    const handleActionAjoutee = () => {
        setShowForm(false);
        fetchActions();
    };

    return (
        <div className="details-parcelle-container">
            <button onClick={onBack} className="btn-back">← Retour aux parcelles</button>

            <div className="parcelle-header-box">
                <h2>Fiche Détails : <span className="highlight-text">{parcelle?.nomParcelle || 'Nom inconnu'}</span></h2>
                <p className="parcelle-meta">Superficie : <strong>{parcelle?.superficieParc ?? '0'} ha</strong></p>
            </div>

            <div className="campagne-section">
                <div className="campagne-header">
                    <h3>Suivi de la campagne en cours</h3>
                    <button onClick={() => setShowForm(!showForm)} className="btn-trigger-action">
                        {showForm ? "✖ Annuler l'action" : "➕ Planifier une action"}
                    </button>
                </div>

                {showForm && (
                    <FormulaireAction
                        parcelle={parcelle}
                        onSuccess={handleActionAjoutee}
                    />
                )}

                <h4 className="sub-title">Historique des actions de la campagne</h4>
                <div className="actions-list">
                    {loading ? (
                        <div className="loading-message">Chargement des actions...</div>
                    ) : error ? (
                        <div className="error-message">⚠️ {error}</div>
                    ) : actionsCampagne.length > 0 ? (
                        actionsCampagne.map(action => (
                            <div key={action.id} className="action-item-card">
                                <div className="action-info">
                                    <span className="action-badge">🛠️ {action.tache?.nomTache || "Action"}</span>
                                    <span className="action-worker">👤 Ouvrier : {action.ouvrier?.nomOuvrier || action.ouvrier?.nomUtilisateur || "Non assigné"}</span>
                                </div>
                                <div className="action-date">
                                    📅 {action.dateRealisation ? new Date(action.dateRealisation).toLocaleDateString('fr-FR') : 'Date inconnue'}
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="empty-message">Aucune action n'a encore été enregistrée pour cette campagne.</div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default DetailsParcelle;