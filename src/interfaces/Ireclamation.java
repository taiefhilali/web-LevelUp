/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.sql.Date;
import java.util.List;
import models.Reclamation;
import models.User;

/**
 *
 * @author User
 */
public interface Ireclamation {
   
    
   //ajouter
    public boolean ajouterReclamation(Reclamation r);
    
    //lister
    public List<Reclamation> afficherReclamation();
    
    public boolean modifierReclamation(Reclamation r);
    
    public boolean supprimerReclamation(Reclamation r);
 
    //recherhce
     public List<Reclamation> rechercheReclamationParUser(User u);
      public List<Reclamation> triReclamationParUser();
    
}