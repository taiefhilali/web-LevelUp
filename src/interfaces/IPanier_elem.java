/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;


import java.util.List;
import models.Panier;
import models.Panier_elem;

/**
 *
 * @author ASUS
 */
public interface IPanier_elem {
    
    public void AjouterElementPanier(Panier_elem p);
    
    
    public void supprimerElementPanier(Panier_elem p);
    
    
    public List<Panier_elem> afficherPanier(int id_panier);
    
}
