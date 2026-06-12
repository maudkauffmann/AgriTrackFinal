import { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Inscription = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({ nom: '', tel: '', email: '', mdp: '' });

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const res = await axios.post('http://127.0.0.1:8000/api/user/add', formData);
            localStorage.setItem('user', JSON.stringify(res.data.user));
            alert(res.data.message);
            window.location.href = "/";
        } catch (err) {
            alert(err.response?.data?.message || "Erreur");
        }
    };

    return (
        <div style={styles.container}>
            <div style={styles.card}>
                <h2 style={styles.title}>AgriTrack</h2>
                <p style={styles.subtitle}>Créer un compte Propriétaire</p>

                <form onSubmit={handleSubmit} style={styles.form}>
                    <input name="nom" placeholder="Nom complet" onChange={handleChange} style={styles.input} required />
                    <input name="tel" type="tel" placeholder="N° de Téléphone" onChange={handleChange} style={styles.input} required />
                    <input name="email" type="email" placeholder="Email" onChange={handleChange} style={styles.input} required />
                    <input name="mdp" type="password" placeholder="Mot de passe" onChange={handleChange} style={styles.input} required />

                    <button type="submit" style={styles.button}>S'inscrire</button>
                </form>

                <button onClick={() => navigate('/')} style={styles.linkBtn}>
                    Déjà inscrit ? Se connecter
                </button>
            </div>
        </div>
    );
};

const styles = {
    container: {
        display: 'flex', justifyContent: 'center', alignItems: 'center',
        minHeight: '100vh', backgroundColor: '#f4f7f6', padding: '15px'
    },
    card: {
        backgroundColor: 'white', padding: '30px 20px', borderRadius: '15px',
        boxShadow: '0 4px 20px rgba(0,0,0,0.08)', width: '100%', maxWidth: '400px'
    },
    title: { color: '#2e7d32', textAlign: 'center', fontSize: '28px', marginBottom: '5px' },
    subtitle: { textAlign: 'center', color: '#666', marginBottom: '25px', fontSize: '15px' },
    form: { display: 'flex', flexDirection: 'column', gap: '15px' },
    input: {
        padding: '15px', borderRadius: '10px', border: '1px solid #ddd',
        fontSize: '16px', outline: 'none', backgroundColor: '#fafafa'
    },
    button: {
        backgroundColor: '#2e7d32', color: 'white', border: 'none',
        padding: '16px', borderRadius: '10px', fontSize: '16px',
        fontWeight: 'bold', cursor: 'pointer', marginTop: '10px'
    },
    linkBtn: {
        background: 'none', border: 'none', color: '#2e7d32',
        marginTop: '20px', cursor: 'pointer', width: '100%', fontSize: '14px', fontWeight: '500'
    }
};

export default Inscription;