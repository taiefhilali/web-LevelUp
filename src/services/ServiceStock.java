/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.Istock;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

import models.Stock;
import utils.MaConnexion;

/**
 *
 * @author user
 */
public class ServiceStock implements Istock {
     Connection cnx =MaConnexion.getInstance().getCnx();

    @Override
    public void ajouterStock(Stock s) {
   String request= "INSERT INTO `stock`( `nom`, `quantite`, `etat`) VALUES( '"+s.getNom()+"',"+s.getQuantite()+",'"+s.getEtat()+"')";
  
         try {
               Statement st = cnx.createStatement();
             st.executeUpdate(request);
         } catch (SQLException ex) {
             ex.printStackTrace();
         }
          
    }

    @Override
    public List<Stock> afficherStock() {
          List<models.Stock> stocks =new ArrayList<models.Stock>();
     String query = "SELECT * FROM stock";
      Statement st = null;
         try {
             st=cnx.createStatement();
             ResultSet rs= st.executeQuery(query);
             while (rs.next()){
             stocks.add(new models.Stock(rs.getInt("id"), rs.getString(2), rs.getInt("quantite"), rs.getString(4)));
         }
         } catch (SQLException ex) {
             ex.printStackTrace();
         }
             return stocks;
    }


    @Override
    public boolean modifierStock(Stock s) {
                 String req = "UPDATE `stock` SET `nom`='"+s.getNom()+"',`quantite`="+s.getQuantite()+",`etat`='"+s.getEtat()+"' WHERE `id` = "+s.getId()+" ";
         try {
             Statement st = cnx.createStatement();
              if (st.executeUpdate(req) == 1)
                return true;
            return false;
         } catch (SQLException ex) {
             ex.printStackTrace();
            return false;
         }
    }

    @Override
    public boolean supprimerStock(Stock s) {
   String req = "DELETE FROM `stock` WHERE `id` = "+s.getId()+" ";
         try {
             Statement st = cnx.createStatement();
             if (st.executeUpdate(req) == 1)
                return true;
            return false;
         } catch (SQLException ex) {
             ex.printStackTrace();
            return false;
         }
    }
    
    
    
}
