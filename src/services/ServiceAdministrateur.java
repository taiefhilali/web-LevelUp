/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.Iadministrateur;
import interfaces.Iuser;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import models.Administrateur;
import utils.MaConnexion;

/**
 *
 * @author 21694
 */
public class ServiceAdministrateur implements Iadministrateur {
    
    //var
    Connection cnx = MaConnexion.getInstance().getCnx();

    @Override
    public boolean ajouterPersonne(Administrateur p) {
        
        
        
        String request = "INSERT INTO `administrateur`(`id_user`, `cin`) VALUES ('"+p.getId()+"','"+p.getCin()+"')";
        try {
            Statement st = cnx.createStatement();
            if (st.executeUpdate(request) == 1)
                return true;
            return false;
        } catch (SQLException ex) {
            ex.printStackTrace();
            return false;
        }
    }
    
   @Override
    public List<Administrateur> afficherPersonnes() {
        List<Administrateur> personnes = new ArrayList<Administrateur>();

        String req="SELECT * from user JOIN administrateur WHERE user.id_user= administrateur.id_user;";
        
        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while(rs.next()){
//                String[] adr= rs.getString("adresse").split("/"); ;
//                Adresse adresse=new Adresse(adr[0], adr[1], adr[2], adr[3]);
                personnes.add(new Administrateur(rs.getString("cin"), rs.getInt("id_user"), rs.getString("email"), rs.getString("password"),
                    rs.getString("role"), rs.getString("nom"), rs.getString("prenom"), rs.getString("adresse"), rs.getString("tel"), rs.getDate("dns")));
            }

        } 
        catch (SQLException e) {
            e.printStackTrace();
        }


        return personnes;
    }
    
    
    
    
    
    
    @Override
    public boolean modifierPersonne(Administrateur p) {
        String req = "UPDATE `administrateur` SET `cin`='"+p.getCin()+"' WHERE `id_user` = "+p.getId()+" ";
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
    public boolean supprimerPersonne(Administrateur p) {
       String req = "DELETE FROM `administrateur` WHERE `id_user` = "+p.getId()+" ";
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
