/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Facture;


/**
 *
 * @author user
 */
public interface Ifacture {
     //ajouter 
    public void ajouterFacture (Facture f);
 
    //lister 
    public List<Facture> afficherFacture();
     public boolean modifierFacture(Facture f);
    
    public boolean supprimerFacture(Facture f);
    public void prix ();
}
