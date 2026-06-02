import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import Inscription from "./pages/Inscription.jsx";
import ListePlantation from "./components/ListePlantations.jsx";
import GestionParcelles from "./components/GestionParcelles.jsx";
import './App.css';
// Import du service qui contient toute la logique métier de synchro
import { synchroniserDonneesHorsLigne } from './services/synchroService';

function App() {
    const token = localStorage.getItem('token');
    const isAuthenticated = token && token !== "undefined" && token !== "null";

    // Écouteur global pour la synchronisation automatique au retour d'Internet
    useEffect(() => {
        const handleOnline = async () => {
            console.log("🌐 Réseau retrouvé ! Lancement de la synchronisation...");
            try {
                // Appel unique au service centralisé.
                // Il gère le localStorage, le format JSON, et l'envoi au serveur.
                await synchroniserDonneesHorsLigne();
                console.log("✅ Synchronisation terminée.");
            } catch (err) {
                console.error("Erreur lors de la synchronisation automatique :", err);
            }
        };

        window.addEventListener('online', handleOnline);
        return () => window.removeEventListener('online', handleOnline);
    }, []);

    return (
        <Router>
            <Routes>
                <Route path="/inscription" element={<Inscription />} />
                <Route path="/login" element={<Login />} />
                <Route
                    path="/"
                    element={isAuthenticated ? <Dashboard /> : <Navigate to="/login" replace />}
                />
                <Route path="*" element={<Navigate to="/" replace />} />
            </Routes>
        </Router>
    );
}

const Dashboard = () => {
    const [view, setView] = useState(() => {
        return sessionStorage.getItem('current_view') || "plantations";
    });

    const [selectedPlantation, setSelectedPlantation] = useState(() => {
        const savedPlantation = sessionStorage.getItem('selected_plantation');
        return savedPlantation ? JSON.parse(savedPlantation) : null;
    });

    const handleSelectPlantation = (plantation) => {
        setSelectedPlantation(plantation);
        setView("parcelles");
        sessionStorage.setItem('current_view', "parcelles");
        sessionStorage.setItem('selected_plantation', JSON.stringify(plantation));
    };

    const handleBackToPlantations = () => {
        setView("plantations");
        setSelectedPlantation(null);
        sessionStorage.setItem('current_view', "plantations");
        sessionStorage.removeItem('selected_plantation');
    };

    const handleLogout = () => {
        localStorage.removeItem('token');
        sessionStorage.clear();
        window.location.href = "/login";
    };

    return (
        <div className="dashboard-layout">
            <header className="main-header">
                <h1>AgriTrack</h1>
                <button onClick={handleLogout} className="btn-logout">Déconnexion</button>
            </header>

            <div className="welcome-banner">
                <h2>Bienvenue sur votre exploitation 👋</h2>
                <p>Espace de gestion connecté</p>
            </div>

            {view === "plantations" ? (
                <ListePlantation onSelectPlantation={handleSelectPlantation} />
            ) : (
                <GestionParcelles
                    plantation={selectedPlantation}
                    onBack={handleBackToPlantations}
                />
            )}

            <div className="system-admin-box">
                <p className="box-title">Gestion système</p>
                <a href="http://127.0.0.1:8000/admin" target="_blank" rel="noopener noreferrer" className="admin-link">
                    ⚙️ Panneau Administration Symfony
                </a>
            </div>
        </div>
    );
};

export default App;