/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Administrateur;

/**
 *
 * @author 21694
 */
public interface Iadministrateur {
    
   //ajouter
    public boolean ajouterPersonne(Administrateur p);
    
    //lister
    public List<Administrateur> afficherPersonnes();
    
   
    public boolean modifierPersonne(Administrateur p);
    
    public boolean supprimerPersonne(Administrateur p);
}
