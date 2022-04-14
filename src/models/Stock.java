/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package models;

/**
 *
 * @author user
 */
public class Stock {
     private int id;
    private String  nom;
    private int quantite;
    private String  etat;

    public Stock() {
    }

    public Stock(int id, String nom, int quantite, String etat) {
        this.id = id;
        this.nom = nom;
        this.quantite = quantite;
        this.etat = etat;
    }

    public Stock(String nom, int quantite, String etat) {
        this.nom = nom;
        this.quantite = quantite;
        this.etat = etat;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public int getQuantite() {
        return quantite;
    }

    public void setQuantite(int quantite) {
        this.quantite = quantite;
    }

    public String getEtat() {
        return etat;
    }

    public void setEtat(String etat) {
        this.etat = etat;
    }

    @Override
    public String toString() {
        return "Stock{" + "id=" + id + ", nom=" + nom + ", quantit\u00e9=" + quantite + ", etat=" + etat + '}';
    }
    
    
    
}
