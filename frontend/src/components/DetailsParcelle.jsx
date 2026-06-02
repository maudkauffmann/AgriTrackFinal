import React, { useState, useEffect } from 'react';
import FormulaireAction from './FormulaireAction.jsx';

const DetailsParcelle = ({ parcelle, onBack }) => {
    const [actionsCampagne, setActionsCampagne] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [showForm, setShowForm] = useState(false);

    // Extraction sécurisée de l'ID de la parcelle (id_parcelle ou id)
    const parcelleId = parcelle?.id_parcelle || parcelle?.id;

    const fetchActions = async () => {
        if (!parcelleId) {
            setError("Identifiant de parcelle introuvable.");
            setLoading(false);
            return;
        }

        const token = localStorage.getItem('token');
        try {
            // URL absolue HTTPS vers ton API Symfony
            const response = await fetch(`https://127.0.0.1:8000/api/parcelles/${parcelleId}/actions`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                setActionsCampagne(Array.isArray(data) ? data : []);
                setError(null);
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
        if (parcelleId) {
            fetchActions();
        }
    }, [parcelleId]);

    const handleActionAjoutee = () => {
        setShowForm(false);
        setLoading(true);
        fetchActions(); // Recharge instantanément l'historique après un ajout réussi
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
                        actionsCampagne.map((action, index) => {
                            // Extraction robuste de l'identifiant unique (MySQL vs Doctrine)
                            const actionKey = action.id_realisation || action.idRealisation || action.id || index;

                            // Association flexible des libellés (gère le format à plat SQL et l'objet imbriqué)
                            const nomTache = action.nomTache || action.tache?.nomTache || action.nom || "Action";
                            const nomOuvrier = action.nomOuvrier || action.ouvrier?.nomOuvrier || action.ouvrier?.nomUtilisateur || `Ouvrier #${action.id_ouvrier || action.idOuvrier || ''}`;

                            // Lecture rigoureuse de la date (gère dateRealisation et date_realisation)
                            const bruteDate = action.dateRealisation || action.date_realisation;

                            return (
                                <div key={actionKey} className="action-item-card">
                                    <div className="action-info">
                                        <span className="action-badge">🛠️ {nomTache}</span>
                                        <span className="action-worker">👤 {nomOuvrier}</span>
                                    </div>
                                    <div className="action-date">
                                        📅 {bruteDate ? new Date(bruteDate).toLocaleDateString('fr-FR') : 'Date inconnue'}
                                    </div>
                                </div>
                            );
                        })
                    ) : (
                        <div className="empty-message">Aucune action n'a encore été enregistrée pour cette campagne.</div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default DetailsParcelle;