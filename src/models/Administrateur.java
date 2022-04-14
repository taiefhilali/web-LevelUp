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
public class Administrateur extends User {

    private String cin;
    
    //Constructeur

    public Administrateur(String cin, int id, String email, String password, String role, String nom, String prenom, String adresse, String tel, Date dns) {
        super(id, email, password, role, nom, prenom, adresse, tel, dns);
        this.cin = cin;
    }

    public Administrateur(String cin, int id_user) {
        super(id_user);
        this.cin = cin;
    }

    public Administrateur() {
    }

    //Getters and Setters
    public String getCin() {
        return cin;
    }

    public void setCin(String cin) {
        this.cin = cin;
    }
 @Override
    public String toString() {
        return "Administrateur {" + "id=" + getId() + ", email=" + getEmail() + ", password=" + getPassword() + ", role=" + getRole() + ", nom=" + getNom() + ", prenom=" + getPrenom() + ", adresse=" + getAdresse() + ", tel=" + getTel() + ", dns=" + getDns()+" ,cin=" + cin + " }";
    }
    
    
  

}
