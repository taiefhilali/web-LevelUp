/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package models;

import java.util.Date;

public class Livraison {
    
    //var
    private int id_livraison;
    private Commande commande;
    private User user;
    private String date;
    private String etat_livraison;
    
    //constructeur

    public Livraison() {
    }

    public Livraison(int id_livraison, Commande commande, User user, String date, String etat_livraison) {
        this.id_livraison = id_livraison;
        this.commande = commande;
        this.user = user;
        this.date = date;
        this.etat_livraison = etat_livraison;
    }

    public Livraison(Commande commande, User user, String date, String etat_livraison) {
        this.commande = commande;
        this.user = user;
        this.date = date;
        this.etat_livraison = etat_livraison;
    }

    public Livraison(int id_livraison) {
        this.id_livraison = id_livraison;
    }

   

  

  

    
    //getter et setter

    public int getId_livraison() {
        return id_livraison;
    }

    public Commande getCommande() {
        return commande;
    }

    public User getUser() {
        return user;
    }

    public String getDate() {
        return date;
    }

    public String getEtat_livraison() {
        return etat_livraison;
    }

    public void setId_livraison(int id_livraison) {
        this.id_livraison = id_livraison;
    }

    public void setCommande(Commande commande) {
        this.commande = commande;
    }

    public void setUser(User user) {
        this.user = user;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public void setEtat_livraison(String etat_livraison) {
        this.etat_livraison = etat_livraison;
    }

    @Override
    public String toString() {
        return "Livraison{" + "id_livraison=" + id_livraison +  '}';
    }


 

   
    
}