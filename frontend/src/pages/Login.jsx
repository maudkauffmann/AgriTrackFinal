import { useState } from 'react';
import axios from 'axios';
import { useNavigate, Link } from 'react-router-dom';
import './Login.css'; // Chargement de ton fichier de style séparé

const Login = () => {
    const navigate = useNavigate();
    const [credentials, setCredentials] = useState({
        telUtilisateur: '',
        password: ''
    });

    const handleChange = (e) => {
        setCredentials({ ...credentials, [e.target.name]: e.target.value });
    };

    const handleLogin = async (e) => {
        e.preventDefault();
        try {
            const apiUrl = import.meta.env.VITE_API_URL || 'https://127.0.0.1:8000';
            const res = await axios.post(`${apiUrl}/api/login`, {
                telUtilisateur: credentials.telUtilisateur,
                password: credentials.password
            }, {
                withCredentials: true
            });

            if (res.data.token) {
                localStorage.setItem('token', res.data.token);
                window.location.href = "/";
            }
        } catch (err) {
            console.error("Erreur détaillée :", err);
            alert("Erreur de connexion : " + (err.response?.data?.message || "Identifiants incorrects."));
        }
    };

    return (
        <div className="login-container">
            <div className="login-card">
                <h2 className="login-title">AgriTrack</h2>
                <p className="login-subtitle">Connexion sécurisée</p>
                <form onSubmit={handleLogin} className="login-form">
                    <input
                        name="telUtilisateur"
                        type="tel"
                        placeholder="Téléphone"
                        onChange={handleChange}
                        className="login-input"
                        required
                    />
                    <input
                        name="password"
                        type="password"
                        placeholder="Mot de passe"
                        onChange={handleChange}
                        className="login-input"
                        required
                    />
                    <button type="submit" className="login-button">Se connecter</button>
                </form>
                <div className="login-footer">
                    <Link to="/inscription" className="login-link">Créer un compte</Link>
                </div>
            </div>
        </div>
    );
};

export default Login;