/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;



import java.util.List;
import models.Comment;

/**
 *
 * @author msi
 */
public interface Icomment {
       //ajouter
    public boolean ajouterComment(Comment c);
    //lister
    public List<Comment> afficherComment();
    //update
      public boolean modifierComment(Comment c);
  //delete
      public boolean supprimerComment(Comment c);

    public void Commentsum();
}
     