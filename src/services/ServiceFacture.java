/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.Ifacture;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

import models.Facture;
import models.Post;
import models.User;

import utils.MaConnexion;

/**
 *
 * @author user
 */
public class ServiceFacture implements Ifacture {
     Connection cnx =MaConnexion.getInstance().getCnx();

    @Override
    public void ajouterFacture(Facture f) {
        String request= "INSERT INTO `facture`( `date`, `prix_total`,`id_user`) VALUES( '"+f.getDate()+"','"+f.getPrix_total()+"',"+f.getUser().getId()+")";
             
         try {
              Statement st = cnx.createStatement();
              st.executeUpdate(request);
         } catch (SQLException ex) {
             ex.printStackTrace();
         }
             
    }

    @Override
    public List<Facture> afficherFacture() {
        List<models.Facture> factures =new ArrayList<models.Facture>();
     String query = "SELECT * FROM facture f  inner JOIN user u  where f.id_user=u.id_user";
      Statement st = null;
         try {
             st=cnx.createStatement();
              ResultSet rs= st.executeQuery(query);
             while (rs.next()){
            factures.add(new Facture(rs.getInt("id_facture"),
                    rs.getDate("date"),
                    rs.getString("prix_total"),
              new User(rs.getInt("id_user"))));
         }
         } catch (SQLException ex) {
               ex.printStackTrace();
         }
            return factures;
    }

    @Override
    public boolean modifierFacture(Facture f) {
                 String req = "UPDATE `facture` SET `date`='"+f.getDate()+"',`prix_total`='"+f.getPrix_total()+"',`id_user`="+f.getUser().getId()+" WHERE `id_facture` = "+f.getId_facture()+" ";
                
         try {
           Statement  st = cnx.createStatement();
           if (st.executeUpdate(req) == 1)
                return true;
            return false;
         } catch (SQLException ex) {
            ex.printStackTrace();
            return false;
         }
              
    }

    @Override
    public boolean supprimerFacture(Facture f) {
         try {
             String req = "DELETE FROM `facture` WHERE `id_facture` = "+f.getId_facture()+" ";
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
    public void prix() {
       Scanner clavier = new Scanner (System.in);
       double HT=0,tva=0,ttc=0,r=0,netc=0;
        System.out.println("Entrer nombre article");
        int n =clavier.nextInt();
        for (int i=0;i<n;i++){
            System.out.println("Entrer prix ");
            double prix=clavier.nextDouble();
            HT+=prix;
        }
        if (HT>5000){
            r=HT*0.1;
            netc=HT-r;
        }
        tva=netc*0.2;
        ttc=netc+tva;
        System.out.println("le Montant est "+HT+"DT");
         System.out.println("la remise est 1% "+r+"DT");
          System.out.println("net commercial est "+netc+"DT");
           System.out.println("TVA est "+tva+"DT");
            System.out.println("TTC est "+ttc+"DT");
    }
     
}
