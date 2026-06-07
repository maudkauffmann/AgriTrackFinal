import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

const ParametresCompte = () => {
    const [userData, setUserData] = useState({ nomUtilisateur: '', telUtilisateur: '' });
    const [isEditing, setIsEditing] = useState(false);
    const [form, setForm] = useState({ nomUtilisateur: '', telUtilisateur: '', newPassword: '' });

    useEffect(() => {
        fetch('https://127.0.0.1:8000/api/user/me', {
            headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        })
            .then(res => res.json())
            .then(data => {
                setUserData(data);
                setForm(data);
            });
    }, []);

    const handleSave = async () => {
        const payload = { ...form };
        if (!payload.newPassword) delete payload.newPassword;

        const res = await fetch('https://127.0.0.1:8000/api/user/update', {
            method: 'PATCH',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            const data = await res.json();
            setUserData(data.user);
            setIsEditing(false);
            alert("Profil mis à jour !");
        }
    };

    return (
        <div className="dashboard-layout">
            <Link to="/" className="btn-back">← Retour</Link>

            <div className="parametres-container">
                <div className="parametres-header">
                    <h2>⚙️ Mon Profil</h2>
                </div>

                {isEditing ? (
                    <div className="form-settings">
                        <div className="form-group">
                            <label>Nom</label>
                            <input className="form-control" value={form.nomUtilisateur} onChange={e => setForm({...form, nomUtilisateur: e.target.value})} />
                        </div>
                        <div className="form-group">
                            <label>Téléphone</label>
                            <input className="form-control" value={form.telUtilisateur} onChange={e => setForm({...form, telUtilisateur: e.target.value})} />
                        </div>
                        <div className="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" className="form-control" placeholder="Laisser vide pour ne pas changer" onChange={e => setForm({...form, newPassword: e.target.value})} />
                        </div>
                        <button className="btn-save-settings" onClick={handleSave}>Enregistrer</button>
                        <button className="btn-back" style={{width: '100%', marginTop: '10px'}} onClick={() => setIsEditing(false)}>Annuler</button>
                    </div>
                ) : (
                    <div>
                        <div className="value-display">
                            <span><strong>Nom :</strong> {userData.nomUtilisateur}</span>
                        </div>
                        <div className="value-display" style={{marginTop: '10px'}}>
                            <span><strong>Téléphone :</strong> {userData.telUtilisateur}</span>
                        </div>
                        <div className="value-display" style={{marginTop: '10px'}}>
                            <span><strong>Mot de passe :</strong> ********</span>
                        </div>
                        <button className="btn-trigger-action" style={{width: '100%', marginTop: '20px'}} onClick={() => setIsEditing(true)}>Modifier</button>
                    </div>
                )}
            </div>
        </div>
    );
};

export default ParametresCompte;