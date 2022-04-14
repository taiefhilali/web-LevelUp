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
public class Client extends User {

    private String sexe;

    //Constructeur

    public Client(String sexe, int id_user) {
        super(id_user);
        this.sexe = sexe;
    }

    public Client(String sexe, int id_user, String email, String password, String role, String nom, String prenom, String adresse, String tel, Date dns) {
        super(id_user, email, password, role, nom, prenom, adresse, tel, dns);
        this.sexe = sexe;
    }
    
    

    //Getters and Setters
    public String getSexe() {
        return sexe;
    }

    public void setSexe(String sexe) {
        this.sexe = sexe;
    }
 @Override
    public String toString() {
        return "Client {" + "id=" + getId() + ", email=" + getEmail() + ", password=" + getPassword() + ", role=" + getRole() + ", nom=" + getNom() + ", prenom=" + getPrenom() + ", adresse=" + getAdresse() + ", tel=" + getTel() + ", dns=" + getDns()+" ,sexe=" + sexe + " }";
    }
}
