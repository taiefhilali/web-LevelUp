/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.Ireclamation;
import java.sql.Connection;
import java.sql.Date;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import models.Livraison;
import models.Reclamation;
import models.User;
import utils.MaConnexion;

/**
 *
 * @author 21694
 */
public class ServiceReclamation implements Ireclamation{
    
    //var
    Connection cnx = MaConnexion.getInstance().getCnx();

    public boolean ajouterReclamation(Reclamation r) {
        String request = "INSERT INTO `Reclamation`(`id_user`, `id_livraison`, `description`) VALUES ('"+r.getUser().getId()+"','"+r.getLivraison().getId_livraison()+"','"+r.getDescription()+"')";
        try {
            Statement st = cnx.createStatement();
            if (st.executeUpdate(request) == 1 )
                return true;
            return false;
        } catch (SQLException ex) {
            ex.printStackTrace();
            return false;
        }
    }

    public List<Reclamation> afficherReclamation() {
        List<Reclamation> reclamation = new ArrayList<Reclamation>();

        String req="SELECT * FROM `reclamation`";
        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while(rs.next()){
                reclamation.add(new Reclamation(rs.getInt("id_reclamation"),new User(rs.getInt("id_user")),new Livraison (rs.getInt("id_livraison")),rs.getString("Description"),rs.getBoolean("WARN")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }


        return reclamation;
    }
    
    public boolean modifierReclamation(Reclamation r) {
        String req = "UPDATE `reclamation` SET `id_user`='"+r.getUser().getId()+"',`id_livraison`='"+r.getLivraison().getId_livraison()+"',`description`='"+r.getDescription()+"' WHERE `id_reclamation` = "+r.getId_reclamation()+" ";
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

    public boolean supprimerReclamation(Reclamation r) {
        String req = "DELETE FROM `reclamation` WHERE `id_reclamation` = "+r.getId_reclamation()+" ";

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
    public List<Reclamation> rechercheReclamationParUser(User u) {
            List<Reclamation> reclamation = new ArrayList<Reclamation>();

        String req="SELECT * FROM `reclamation` where `id_user`="+u.getId()+" ";
        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while(rs.next()){
                reclamation.add(new Reclamation(rs.getInt("id_reclamation"),new User(rs.getInt("id_user")),new Livraison (rs.getInt("id_livraison")),rs.getString("Description")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }


        return reclamation;
    
    }

    @Override
    public List<Reclamation> triReclamationParUser() {
        List<Reclamation> reclamation = new ArrayList<Reclamation>();

        String req="SELECT * FROM `reclamation` order by `id_user` desc ";
        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //
            while(rs.next()){
                reclamation.add(new Reclamation(rs.getInt("id_reclamation"),new User(rs.getInt("id_user")),new Livraison (rs.getInt("id_livraison")),rs.getString("Description")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }


        return reclamation;
        
    }

    public boolean warn(Reclamation r) {

       String req = "UPDATE `reclamation` SET `warn`=1  WHERE `id_reclamation` = "+r.getId_reclamation()+" ";
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
     public List<Reclamation> ReclamationParLivreur(int idLivreur) {
        List<Reclamation> reclamation = new ArrayList<Reclamation>();

        String req="SELECT * FROM reclamation r , livraison l where (l.id_livraison = r.id_livraison) and r.WARN=1 and l.id_user="+idLivreur;
        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while(rs.next()){
                reclamation.add(new Reclamation(rs.getInt("id_reclamation"),new User(rs.getInt("id_user")),new Livraison (rs.getInt("id_livraison")),rs.getString("Description"),rs.getBoolean("WARN")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }


        return reclamation;
    }
    
    
}