package interfaces;

import java.util.List;
import models.Produit;

/**
 *
 * @author Iskander
 */
public interface Iproduit {

    public void ajouterProduit(Produit p);

    public void modifierProduit(Produit p);

    public void supprimerProduit(Produit p);

    public List<Produit> afficherProduit();

    public List<Produit> chercherProduitDynamiquement(String s, List<Produit> l);

    public void TrierProduitParPrix();

//  Pour calculer la promotion :   
    public Produit calculerPromotion(Produit p);
}
