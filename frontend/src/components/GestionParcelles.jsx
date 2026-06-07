import React, { useState, useEffect } from 'react';
import DetailsParcelle from './DetailsParcelle.jsx';

/**
 * @typedef {Object} Parcelle
 * @property {string} id_parcelle
 * @property {string} nomParcelle
 * @property {float} superficieParc
 */

const GestionParcelles = ({ plantation, onBack }) => {
    const [parcelles, setParcelles] = useState([]);
    const [selectedParcelle, setSelectedParcelle] = useState(() => {
        const savedParcelle = sessionStorage.getItem('selected_parcelle');
        return savedParcelle ? JSON.parse(savedParcelle) : null;
    });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const handleSelectParcelle = (parcelle) => {
        setSelectedParcelle(parcelle);
        sessionStorage.setItem('selected_parcelle', JSON.stringify(parcelle));
    };

    const handleBackToList = () => {
        setSelectedParcelle(null);
        sessionStorage.removeItem('selected_parcelle');
    };

    useEffect(() => {
        const fetchParcelles = async () => {
            const token = localStorage.getItem('token');
            try {
                const response = await fetch(`https://127.0.0.1:8000/api/plantations/${plantation.id}/parcelles`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log("DONNÉES RECUES DE SYMFONY :", data);

                    // Adaptation aux formats possibles (Array direct ou Hydra API Platform)
                    const rawList = Array.isArray(data) ? data : (data['hydra:member'] || []);
                    setParcelles(rawList);
                } else {
                    setError("Impossible de charger les parcelles de cette plantation.");
                }
            } catch (err) {
                setError("Erreur de connexion réseau.");
            } finally {
                setLoading(false);
            }
        };

        if (plantation?.id) {
            fetchParcelles();
        }
    }, [plantation]);

    if (selectedParcelle) {
        return (
            <DetailsParcelle
                parcelle={selectedParcelle}
                onBack={handleBackToList}
            />
        );
    }

    return (
        <div className="parcelles-container">
            <button onClick={onBack} className="btn-back">← Retour aux plantations</button>
            <h2>Parcelles de : <span className="highlight-text">{plantation?.nomPlantation}</span></h2>
            <p className="subtitle-info"><i>Cliquez sur une parcelle pour voir ses détails et enregistrer des actions</i></p>
            <div className="parcelles-grid">
                {loading ? (
                    <div className="loading-message">Chargement des parcelles...</div>
                ) : error ? (
                    <div className="error-message">⚠️ {error}</div>
                ) : parcelles.length > 0 ? (
                    parcelles.map((parcelle, index) => {
                        // Récupération sécurisée de l'ID de la parcelle
                        const currentId = parcelle.id_parcelle || parcelle.id || index;

                        return (
                            <div
                                key={currentId}
                                onClick={() => handleSelectParcelle(parcelle)}
                                className="parcelle-card"
                            >
                                🌾 <strong>{parcelle.nomParcelle || 'Parcelle sans nom'}</strong>
                                <span className="parcelle-surface">{parcelle.superficieParc || 0} ha</span>
                            </div>
                        );
                    })
                ) : (
                    <div className="empty-message">Aucune parcelle trouvée pour cette plantation.</div>
                )}
            </div>
        </div>
    );
};

export default GestionParcelles;