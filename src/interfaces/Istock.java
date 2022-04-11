/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.List;
import models.Stock;

/**
 *
 * @author user
 */
public interface Istock {
     //ajouter 
    public void ajouterStock (Stock s);
 
    //lister 
    public List<Stock> afficherStock();
     public boolean modifierStock(Stock s);
    
    public boolean supprimerStock(Stock s);
    
    
}
