/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Commande;
import models.Commande_elem;
import models.TopProduits;

/**
 *
 * @author ASUS
 */
public interface ICommande_elem {
    
    public void ajouterElementCommande(Commande_elem e);
    
    public void supprimerElementCommande(Commande_elem e);
    
    public void modifierElementCommande(Commande_elem e);
    
     public List<Commande_elem> AfficherCommande(int i);
    
     public List<TopProduits> TopProduits(int n);
}