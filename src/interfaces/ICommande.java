/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Commande;
import models.Commande_elem;

/**
 *
 * @author ASUS
 */
public interface ICommande {
    
    public void ajouterCommande(Commande c);
    
    public void SupprimerCommande(Commande c);
    
    public void modifierCommande(Commande c);
    
    public List<Commande> AfficherCommande();
    
    public List<Commande> AfficherCommandeId(int id);
    
    public List<Commande> Tri(List<Commande> l);
    
    public List<Commande> TrierCommandesParDate();
}
