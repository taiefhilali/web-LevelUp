/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.Ifournisseur;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import models.Administrateur;
import models.Fournisseur;
import utils.MaConnexion;

/**
 *
 * @author 21694
 */
public class ServiceFournisseur implements Ifournisseur {
    
    //var
    Connection cnx = MaConnexion.getInstance().getCnx();

    @Override
    public boolean ajouterPersonne(Fournisseur p) {
        
        
        
        String request = "INSERT INTO `fournisseur`(`id_user`, `cin`, `nom_marque`) VALUES ('"+p.getId()+"','"+p.getCin()+"','"+p.getNomMarque()+"')";
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
        public List<Fournisseur> afficherPersonnes() {
        List<Fournisseur> personnes = new ArrayList<Fournisseur>();

        String req="SELECT * from user JOIN fournisseur WHERE user.id_user= fournisseur.id_user;";
        
        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while(rs.next()){
                
                personnes.add(new Fournisseur(rs.getString("cin"), rs.getString("nom_marque"), rs.getInt("id_user"), rs.getString("email"), rs.getString("password"),
                    rs.getString("role"), rs.getString("nom"), rs.getString("prenom"), rs.getString("adresse"), rs.getString("tel"), rs.getDate("dns")));
    
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
        return personnes;
        }

    
    @Override
    public boolean modifierPersonne(Fournisseur p) {
        String req = "UPDATE `fournisseur`  SET `cin`='"+p.getCin()+"', `nom_marque`='"+p.getNomMarque()+"' WHERE `id_user` = "+p.getId()+" ";
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
    public boolean supprimerPersonne(Fournisseur p) {
       String req = "DELETE FROM `fournisseur` WHERE `id_user` = "+p.getId()+" ";
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
