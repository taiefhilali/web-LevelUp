/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package services;

import interfaces.Ilivraison;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import models.Commande;
import models.Livraison;
import models.Livreur;
import models.Reclamation;
import models.User;
import utils.MaConnexion;

/**
 *
 * @author 21694
 */
public class ServiceLivraison implements Ilivraison{
    
    //var
    Connection cnx = MaConnexion.getInstance().getCnx();

    public boolean ajouterLivraison(Livraison l) {
        String request = "INSERT INTO `livraison`( `id_commande`, `id_user`, `date`, `etat_livraison`) VALUES ("+l.getCommande().getId_commande()+","+l.getUser().getId()+",'"+l.getDate().toString()+"','"+l.getEtat_livraison()+"')";
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

    public List<Livraison> afficherLivraison() {
        List<Livraison> livraison = new ArrayList<Livraison>();

        String req="SELECT * FROM `livraison`";
        Statement st = null;
        try {
            st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            //SOB HEDHA FI HEDHA
            while(rs.next()){
                livraison.add(new Livraison(rs.getInt("id_livraison"),new Commande(rs.getInt("id_commande")),new User (rs.getInt("id_user")),rs.getString("date"),rs.getString("etat_livraison")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }


        return livraison;
    }
    
    public boolean modifierLivraison(Livraison l) {
        String req = "UPDATE `livraison` SET `id_commande`='"+l.getCommande().getId_commande()+"',`id_user`= "+l.getUser().getId()+",`date`='"+l.getDate()+"',`etat_livraison`='"+l.getEtat_livraison()+"' WHERE `id_livraison` = "+l.getId_livraison()+" ";
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

    public boolean supprimerLivraison(Livraison l) {
        String req = "DELETE FROM `livraison` WHERE `id_livraison` = "+l.getId_livraison()+" ";

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
 public boolean annulerAffectation(Livraison l){
     String req="UPDATE `livraison` SET `id_user`=NULL WHERE id_livraison='"+l.getId_livraison()+"'";
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

    public boolean Affectation(Livraison l, Livreur livreurSelected) {
     String req="UPDATE `livraison` SET `id_user`="+livreurSelected.getId()+" WHERE id_livraison= "+l.getId_livraison()+" ";

    
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
    public List<Livraison> rechercheLivraisonparEtat_Livraison(String etat_livraison) {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }

    @Override
    public List<Livraison> triLivraisonparEtat_Livraison() {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
 


    
}