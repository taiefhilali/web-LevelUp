/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package models;

import java.sql.Date;

/**
 *
 * @author beldi
 */
public class Fournisseur extends User {

    private String cin;
    private String nom_marque;
//Constructeur

    public Fournisseur(String cin, String nomMarque, int id, String email, String password, String role, String nom, String prenom, String adresse, String tel, Date dns) {
        super(id, email, password, role, nom, prenom, adresse, tel, dns);
        this.cin = cin;
        this.nom_marque = nomMarque;
    }
    

    public Fournisseur(String cin, String nom_marque, int id_user) {
        super(id_user);
        this.cin = cin;
        this.nom_marque = nom_marque;
    }
    

    //Getters and Setters
    public String getCin() {
        return cin;
    }

    public void setCin(String cin) {
        this.cin = cin;
    }

    public String getNomMarque() {
        return nom_marque;
    }

    public void setNomMarque(String nomMarque) {
        this.nom_marque = nomMarque;
    }
      @Override
    public String toString() {
        return "Fournisseur {" + "id=" + getId() + ", email=" + getEmail() + ", password=" + getPassword() + ", role=" + getRole() + ", nom=" + getNom() + ", prenom=" + getPrenom() + ", adresse=" + getAdresse() + ", tel=" + getTel() + ", dns=" + getDns()+" ,cin=" + cin + " ,nom marque=" + nom_marque + ", }";
    }

}
