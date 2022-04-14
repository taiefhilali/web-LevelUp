/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.IPanier;
import java.sql.Connection;
import java.sql.SQLException;
import java.sql.Statement;
import models.Panier;
import utils.MaConnexion;

/**
 *
 * @author ASUS
 */
public class servicePanier implements IPanier{
    //var
    Connection cnx = MaConnexion.getInstance().getCnx();
    
    
    //Ajouter Panier
    @Override
    public void ajouterPanier(Panier p) {
        String request ="INSERT INTO `panier`(`id_user`) VALUES ("+p.getClient().getId()+")";
         try {
            Statement st = cnx.createStatement();
            st.executeUpdate(request);
            System.out.println("Panier Ajouter avec succés");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    //Supprimer Panier
    @Override
    public void supprimerPanier(Panier p) {
        String request ="DELETE FROM `panier` WHERE id_panier ="+p.getId_panier()+"";
        try {
            Statement st = cnx.createStatement();
            st.executeUpdate(request);
            System.out.println("Panier Supprimé avec succés");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }

    }
    
}
