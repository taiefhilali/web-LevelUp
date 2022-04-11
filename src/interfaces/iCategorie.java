package interfaces;

import java.util.List;
import models.Categorie;

/**
 *
 * @author Iskander
 */
public interface Icategorie {

    public void ajouterCategorie(Categorie c);

    public void modifierCategorie(Categorie c);

    public void supprimerCategorie(Categorie c);

    public List<Categorie> afficherCategorie();

    public Categorie retrieveCategorieById(int id);

}
