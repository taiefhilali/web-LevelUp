/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package models;

import java.sql.Date;

/**
 *
 * @author msi
 */
public class Post {
    int id ; 
    User id_user;
    int nblike;
    int nbdislike;
    String title; 
    String content; 
    Date datep;

    public Post() {
    }

    public Post(int id, String title, String content, Date datep,User id_user,int nblike, int nbdislike) {
        this.id = id;
         this.nblike = nblike;
          this.nbdislike = nbdislike;
        this.id_user = id_user;
        this.title = title;
        this.content = content;
        this.datep = datep;
    }

    public Post(String title, String content, Date datep,User id_user,int nblike, int nbdislike) {
        this.id_user = id_user;
         this.nbdislike = nbdislike;
          this.nblike = nblike;
        this.title = title;
        this.content = content;
        this.datep = datep;
    }

    public Post(String gaming, String accesoires_et_jeux_videos, java.util.Date date) {
        //  throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
   }

    public int getNblike() {
        return nblike;
    }

    public void setNblike(int nblike) {
        this.nblike = nblike;
    }

    public int getNbdislike() {
        return nbdislike;
    }

    public void setNbdislike(int nbdislike) {
        this.nbdislike = nbdislike;
    }

    public User getId_user() {
        return id_user;
    }

    public void setId_user(User id_user) {
        this.id_user = id_user;
    }

   

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public Date getDatep() {
        return datep;
    }

    public void setDatep(Date datep) {
        this.datep = datep;
    }

    //to sTRING//

    @Override
    public String toString() {
        return "Post{" + "id=" + id + ", id_user=" + id_user + ", nblike=" + nblike + ", nbdislike=" + nbdislike + ", title=" + title + ", content=" + content + ", datep=" + datep + '}';
    }

  
    
  
    
    
}
