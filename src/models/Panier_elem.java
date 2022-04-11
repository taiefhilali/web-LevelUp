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
public class Panier_elem {
    
    //var
    private int id_elem;
    private Panier pan;
    private Produit produit;
    
    //constructeur

    public Panier_elem() {
    }

    public Panier_elem(int id_elem, Panier pan, Produit produit) {
        this.id_elem = id_elem;
        this.pan = pan;
        this.produit = produit;
    }
    
    //getters and setters

    public int getId_elem() {
        return id_elem;
    }

    public void setId_elem(int id_elem) {
        this.id_elem = id_elem;
    }

    public Panier getPan() {
        return pan;
    }

    public void setPan(Panier pan) {
        this.pan = pan;
    }

    public Produit getProduit() {
        return produit;
    }

    public void setProduit(Produit produit) {
        this.produit = produit;
    }


    @Override
    public String toString() {
        return "Panier_elem{" + "id_elem=" + id_elem + ", id_panier=" + pan + ", id_produit=" + produit +'\n'+ '}';
    }
    
    
    
}
