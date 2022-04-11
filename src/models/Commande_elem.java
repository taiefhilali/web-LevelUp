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
public class Commande_elem {
    
    //var 
    private int id_elemC;
    private Produit produit;
    private Commande cmd;
    private int Quantite;

    //constructeur
    public Commande_elem() {
    }

    public Commande_elem(int id_elemC, Produit produit, Commande cmd, int Quantite) {
        this.id_elemC = id_elemC;
        this.produit = produit;
        this.cmd = cmd;
        this.Quantite = Quantite;
    }
    
    //getters and setters

    public int getId_elemC() {
        return id_elemC;
    }

    public void setId_elemC(int id_elemC) {
        this.id_elemC = id_elemC;
    }

    public Produit getProduit() {
        return produit;
    }

    public void setProduit(Produit produit) {
        this.produit = produit;
    }



  public Commande getCmd() {
        return cmd;
    }

    public void setCmd(Commande cmd) {
        this.cmd = cmd;
    }

    public int getQuantite() {
        return Quantite;
    }

    public void setQuantite(int Quantite) {
        this.Quantite = Quantite;
    }

    @Override
    public String toString() {
        return "Commande_elem{" + "id_elemC=" + id_elemC + ", id_produit=" +  produit + ", id_commande=" + cmd + ", Quantite=" + Quantite + '}';
    }

  
    
    
    
}
