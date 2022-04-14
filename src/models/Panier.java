/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package models;

/**
 *
 * @author ASUS
 */
public class Panier {
    
    //var
    private int id_panier;
    private Client client;

    //constructeur

    public Panier() {
    }
    
    public Panier(int id_panier) {
        this.id_panier = id_panier;
    }
    
    public Panier(int id_panier, Client client) {
        this.id_panier = id_panier;
        this.client = client;
    }

    public int getId_panier() {
        return id_panier;
    }

    public void setId_panier(int id_panier) {
        this.id_panier = id_panier;
    }

    public Client getClient() {
        return client;
    }

    public void setClient(Client client) {
        this.client = client;
    }



    @Override
    public String toString() {
        return "Panier{" + "id_panier=" + id_panier + ", client=" + client + '}';
    }
    
    
    
    
    
}
