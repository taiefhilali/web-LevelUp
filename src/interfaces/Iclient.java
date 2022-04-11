/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Client;
import models.User;

/**
 *
 * @author 21694
 */
public interface Iclient {
    
   //ajouter
    public boolean ajouterPersonne(Client p);
    
    //lister
    public List<Client> afficherPersonnes();
    
    public boolean modifierPersonne(Client p);
    
    public boolean supprimerPersonne(Client p);
}
