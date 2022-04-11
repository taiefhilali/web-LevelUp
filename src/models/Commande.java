/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package models;

import java.sql.Date;

/**
 *
 * @author ASUS
 */
public class Commande {
    
    //var
   private int id_commande;
   private Client client;
   private float prix_livraison;
   private Date date_commande;
   
    
   //Constructeur
    public Commande() {
    }

    public Commande(int id_commande, Client client, float prix_livraison, Date date_commande) {
        this.id_commande = id_commande;
        this.client = client;
        this.prix_livraison = prix_livraison;
        this.date_commande = date_commande;
    }

    public Commande(int id_commande, float prix_livraison, Date date_commande) {
        this.id_commande = id_commande;
        this.prix_livraison = prix_livraison;
        this.date_commande = date_commande;
    }

    public Commande(int id_commande) {
        this.id_commande = id_commande;
    }
    
    
    //Getters and setters
    public int getId_commande() {
        return id_commande;
    }

    public void setId_commande(int id_commande) {
        this.id_commande = id_commande;
    }

    public Client getClient() {
        return client;
    }

    public void setClient(Client client) {
        this.client = client;
    }

 

    public float getPrix_livraison() {
        return prix_livraison;
    }

    public void setPrix_livraison(float prix_livraison) {
        this.prix_livraison = prix_livraison;
    }

    public Date getDate_commande() {
        return date_commande;
    }

    public void setDate_commande(Date date_commande) {
        this.date_commande = date_commande;
    }

    @Override
    public String toString() {
        return "Commande{" + "id_commande=" + id_commande + '}';
    }
   
    
   
   
}
