/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import models.Post;

/**
 *
 * @author msi
 */
public interface Ipost {
     //ajouter
    public boolean ajouterPost(Post p);
     //lister
    public List<Post> afficherPost();
    //update
    
      public boolean modifierPost(Post p);
      //delete
          public boolean supprimerPost(Post p);
      

    public ArrayList<Post> AfficherTrie();
public Map<Integer, List<String>> bestpost(int nblike);
    public ArrayList<Post> findBytitle(String title);
   public ArrayList<Post> sortBynblike();
    public ArrayList<Post> sortByDate();
          
}
