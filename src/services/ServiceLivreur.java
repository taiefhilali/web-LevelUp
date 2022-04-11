/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.Ilivreur;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import models.Administrateur;
import models.Livreur;
import utils.MaConnexion;

/**
 *
 * @author 21694
 */
public class ServiceLivreur implements Ilivreur {

    //var
    Connection cnx = MaConnexion.getInstance().getCnx();

    @Override
    public boolean ajouterPersonne(Livreur p) {

        String request = "INSERT INTO `livreur`(`id_user`, `cin`, `vehicule`) VALUES ('" + p.getId() + "','" + p.getCin() + "','" + p.getVehicule() + "')";
        try {
            Statement st = cnx.createStatement();
            if (st.executeUpdate(request) == 1) {
                return true;
            }

            return false;
        } catch (SQLException ex) {
            ex.printStackTrace();
            return false;
        }
    }

    @Override
    public List<Livreur> afficherPersonnes() {
        List<Livreur> personnes = new ArrayList<Livreur>();

        String req = "SELECT * from user JOIN livreur WHERE user.id_user= livreur.id_user;";

        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while (rs.next()) {
                personnes.add(new Livreur(rs.getString("vehicule"), rs.getString("cin"), rs.getInt("id_user"), rs.getString("email"), rs.getString("password"),
                        rs.getString("role"), rs.getString("nom"), rs.getString("prenom"), rs.getString("adresse"), rs.getString("tel"), rs.getDate("dns")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return personnes;
    }

    @Override
    public boolean modifierPersonne(Livreur p) {
        String req = "UPDATE `livreur` SET `vehicule`='" + p.getVehicule() + "',`cin`='" + p.getCin() + "' WHERE `id_user` = " + p.getId() + " ";
        try {
            Statement st = cnx.createStatement();
            if (st.executeUpdate(req) == 1) {
                return true;
            }
            return false;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    @Override
    public boolean supprimerPersonne(Livreur p) {
        String req = "DELETE FROM `livreur` WHERE `id_user` = " + p.getId() + " ";
        try {
            Statement st = cnx.createStatement();
            if (st.executeUpdate(req) == 1) {
                return true;
            }
            return false;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }

    }
}
