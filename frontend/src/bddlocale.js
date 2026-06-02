import Dexie from 'dexie';

export const bddLocale = new Dexie('AgriTrackDB');

// définition d'une table "taches" avec l'UUID comme clé primaire
bddLocale.version(1).stores({
    taches: 'uuid, nom, parcelle_id, ouvrier_id'
});