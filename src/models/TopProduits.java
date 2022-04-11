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
public class TopProduits {
    
    private int id;
    private int nbrachat;

    public TopProduits() {
    }

    public TopProduits(int id, int nbrachat) {
        this.id = id;
        this.nbrachat = nbrachat;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getNbrachat() {
        return nbrachat;
    }

    public void setNbrachat(int nbrachat) {
        this.nbrachat = nbrachat;
    }

    @Override
    public String toString() {
        return "Produit{" + "id_produit=" + id + ", nbrachat=" + nbrachat + '}';
    }
    
}
