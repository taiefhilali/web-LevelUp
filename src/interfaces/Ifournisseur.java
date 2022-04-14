/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Fournisseur;

/**
 *
 * @author 21694
 */
public interface Ifournisseur {
    
   //ajouter
    public boolean ajouterPersonne(Fournisseur p);
    
    //lister
    public List<Fournisseur> afficherPersonnes();
    
    public boolean modifierPersonne(Fournisseur p);
    
    public boolean supprimerPersonne(Fournisseur p);
}
