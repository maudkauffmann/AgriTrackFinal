import React, { useState, useEffect } from 'react';
import DetailsParcelle from './DetailsParcelle.jsx';

/**
 * @typedef {Object} Parcelle
 * @property {string} id
 * @property {string} nom_parcelle
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
                    const rawList = Array.isArray(data) ? data : (data['hydra:member'] || []);

                    setParcelles(Array.isArray(data) ? data : []);
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
            <p className="subtitle-info"><i>Cliquez sur une parcelle pour voir ses détails et ses campagnes</i></p>
            <div className="parcelles-grid">
                {loading ? (
                    <div className="loading-message">Chargement des parcelles...</div>
                ) : error ? (
                    <div className="error-message">⚠️ {error}</div>
                ) : parcelles.length > 0 ? (
                    parcelles.map((parcelle) => (
                        <div
                            key={parcelle.id}
                            onClick={() => handleSelectParcelle(parcelle)}
                            className="parcelle-card"
                        >
                            🌾 <strong>{parcelle.nomParcelle}</strong>
                            <p>Le nom de la parcelle est {parcelle.id}</p>
                            <span className="parcelle-surface">{parcelle.superficieParc} ha</span>
                        </div>
                    ))
                ) : (
                    <div className="empty-message">Aucune parcelle trouvée pour cette plantation.</div>
                )}
            </div>
        </div>
    );
};

export default GestionParcelles;