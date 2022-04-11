/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Administrateur;
import models.User;

/**
 *
 * @author 21694
 */
public interface Iuser {
    
   //ajouter
    public long ajouterPersonne(User p);
    
    //lister
    public List<User> afficherPersonnes();
    public List<User> afficherParRole(String role);
    public List<User> afficherParLettre(String lettre);
    public List<User> afficherParPrenom(String prenom);
    public User getByEmail(String email);
    public String authentification(String e, String p);
    
    public boolean modifierPersonne(User p);
    
    public boolean supprimerPersonne(User p);
}
