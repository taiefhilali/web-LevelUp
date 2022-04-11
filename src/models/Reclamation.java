
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package models;



/**
 *
 * @author User
 */
public class Reclamation {
   
    
    //var
    private int id_reclamation;
    private User user;
    private Livraison livraison;
    private String description;
    private boolean warn;
        public Reclamation() {
        }

    public Reclamation(int id_reclamation, User user, Livraison livraison, String description) {
        this.id_reclamation = id_reclamation;
        this.user = user;
        this.livraison = livraison;
        this.description = description;
    }

    public Reclamation(User user, Livraison livraison, String description) {
        this.user = user;
        this.livraison = livraison;
        this.description = description;
    }

    public Reclamation(int id_reclamation, User user, Livraison livraison, String description, boolean warn) {
        this.id_reclamation = id_reclamation;
        this.user = user;
        this.livraison = livraison;
        this.description = description;
        this.warn = warn;
    }

  

    public int getId_reclamation() {
        return id_reclamation;
    }

    public User getUser() {
        return user;
    }

    public Livraison getLivraison() {
        return livraison;
    }

    public String getDescription() {
        return description;
    }

    public void setId_reclamation(int id_reclamation) {
        this.id_reclamation = id_reclamation;
    }

    public void setUser(User user) {
        this.user = user;
    }

    public void setLivraison(Livraison livraison) {
        this.livraison = livraison;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public boolean isWarn() {
        return warn;
    }

    public void setWarn(boolean warn) {
        this.warn = warn;
    }

    @Override
    public String toString() {
        return "Reclamation{" + "id_reclamation=" + id_reclamation + ", user=" + user + ", livraison=" + livraison + ", description=" + description + '}';
    }

 
    }