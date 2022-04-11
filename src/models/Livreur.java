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
public class Livreur extends User {

    private String vehicule;
    private String cin;

      //constructeur

    public Livreur(String vehicule, String cin, int id_user) {
        super(id_user);
        this.vehicule = vehicule;
        this.cin = cin;
    }
    
    
    
    public Livreur(String vehicule, String cin, int id, String email, String password, String role, String nom, String prenom, String adresse, String tel, Date dns) {
        super(id, email, password, role, nom, prenom, adresse, tel, dns);
        this.vehicule = vehicule;
        this.cin = cin;
    }
    //Getters and Setters

   

    public String getVehicule() {
        return vehicule;
    }

    public void setVehicule(String vehicule) {
        this.vehicule = vehicule;
    }

    public String getCin() {
        return cin;
    }

    public void setCin(String cin) {
        this.cin = cin;
    }
     @Override
    public String toString() {
        return " email=" + getEmail();
    }

}
