import React, { useState, useEffect } from 'react';

/**
 * @typedef {Object} Plantation
 * @property {string} id
 * @property {string} nomPlantation
 * @property {string} ville
 */

const ListePlantation = ({ onSelectPlantation }) => {
    /** @type {[Plantation[], Function]} */
    const [plantations, setPlantations] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchPlantations = async () => {
            const token = localStorage.getItem('token');

            try {
                const response = await fetch('https://127.0.0.1:8000/api/plantations', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    setPlantations(Array.isArray(data) ? data : []);
                } else if (response.status === 401) {
                    setError("Votre session a expiré. Veuillez vous reconnecter.");
                    localStorage.removeItem('token');
                    setTimeout(() => { window.location.href = "/login"; }, 2000);
                } else {
                    setError("Impossible de charger vos données de plantation.");
                }
            } catch (err) {
                console.error("Erreur de communication avec Symfony :", err);
                setError("Serveur hors ligne (Vérifie que Symfony est lancé sur le port 8000)");
            } finally {
                setLoading(false);
            }
        };

        fetchPlantations().catch(() => {});
    }, []);

    return (
        <div className="plantations-section">
            <h3 className="section-title">Mes Plantations</h3>
            <div className="plantations-grid">
                {loading ? (
                    <div className="loading-message">Chargement de vos parcelles...</div>
                ) : error ? (
                    <div className="error-message">⚠️ {error}</div>
                ) : plantations.length > 0 ? (
                    plantations.map((p) => (
                        <div
                            key={p.id}
                            onClick={() => onSelectPlantation(p)}
                            className="plantation-card"
                        >
                            <span className="icon">🌳</span>
                            <strong className="plantation-name">{p?.nomPlantation}</strong>
                            <span className="plantation-location">
                                📍 {p.ville || 'Lieu non spécifié'}
                            </span>
                        </div>
                    ))
                ) : (
                    <div className="empty-message">Aucune plantation enregistrée pour votre compte.</div>
                )}
            </div>
        </div>
    );
};

export default ListePlantation;