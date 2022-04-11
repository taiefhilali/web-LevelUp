/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Livreur;

/**
 *
 * @author beldi
 */
public interface Ilivreur {
       //ajouter
    public boolean ajouterPersonne(Livreur p);
    
    //lister
    public List<Livreur> afficherPersonnes();
    
    public boolean modifierPersonne(Livreur p);
    
    public boolean supprimerPersonne(Livreur p);
}
