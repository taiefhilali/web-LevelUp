/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.Iclient;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import models.Administrateur;
import models.Client;
import utils.MaConnexion;

/**
 *
 * @author 21694
 */
public class ServiceClient implements Iclient {

    //var
    Connection cnx = MaConnexion.getInstance().getCnx();

    @Override
    public boolean ajouterPersonne(Client p) {

        String request = "INSERT INTO `client`(`id_user`, `sexe`) VALUES ('" + p.getId() + "','" + p.getSexe() + "')";
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
    public List<Client> afficherPersonnes() {
        List<Client> personnes = new ArrayList<Client>();

        String req = "SELECT * from user JOIN client WHERE user.id_user= client.id_user;";

        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while (rs.next()) {
               
                personnes.add(new Client(rs.getString("sexe"), rs.getInt("id_user"), rs.getString("email"), rs.getString("password"),
                        rs.getString("role"), rs.getString("nom"), rs.getString("prenom"), rs.getString("adresse"), rs.getString("tel"), rs.getDate("dns")));

            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return personnes;
    }

    @Override
    public boolean modifierPersonne(Client p) {
        String req = "UPDATE `client` SET `sexe`='"+p.getSexe()+"' WHERE `id_user` = "+p.getId()+"";
        
            try {
            Statement st = cnx.createStatement();
            if (st.executeUpdate(req) == 1)
                return true;
            return false;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    @Override
    public boolean supprimerPersonne(Client p) {
       String req = "DELETE FROM `client` WHERE `id_user` = "+p.getId()+" ";
          try {
            Statement st = cnx.createStatement();
            if (st.executeUpdate(req) == 1)
                return true;
            return false;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }

    }
}
