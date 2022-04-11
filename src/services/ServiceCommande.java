/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package services;

import interfaces.ICommande;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;
import models.Client;
import models.Commande;
import utils.MaConnexion;

/**
 *
 * @author ASUS
 */
public class ServiceCommande implements ICommande{

      //var
    Connection cnx = MaConnexion.getInstance().getCnx();
    
    //Ajouter Commande
    @Override
    public void ajouterCommande(Commande c) {
         String request = "INSERT INTO `commande`(`id_user`, `prix_livraison`, `date_commande`) VALUES ("+c.getClient().getId()+","+c.getPrix_livraison()+",'"+c.getDate_commande()+"')";
        try {
            Statement st = cnx.createStatement();
           st.executeUpdate(request);
            System.out.println("Commande Ajouté avec succés");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    //Supprimer Commande
    @Override
    public void SupprimerCommande(Commande c) {
        String request ="DELETE FROM `commande` WHERE id_commande ="+c.getId_commande()+"";
         try {
            Statement st = cnx.createStatement();
           st.executeUpdate(request);
            System.out.println("Commande Supprimé avec succés");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    //Modifier Commande
    @Override
    public void modifierCommande(Commande c) {
        String request ="UPDATE `commande` SET `id_commande`="+c.getId_commande()+",`id_user`="+c.getClient().getId()+",`prix_livraison`="+c.getPrix_livraison()+",`date_commande`='"+c.getDate_commande()+"' WHERE id_commande ="+c.getId_commande()+"";
      try {
            Statement st = cnx.createStatement();
           st.executeUpdate(request);
            System.out.println("Commande Modifié avec succés");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    //Afficher les Commandes
    @Override
    public List<Commande> AfficherCommande() {
        List<Commande> commandes = new ArrayList<Commande>();
         String req="SELECT * FROM commande c inner join user u on c.id_user = u.id_user inner join client cl on u.id_user = cl.id_user";        
        try {
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            while(rs.next()){
         
               commandes.add(new Commande(rs.getInt("c.id_commande"),new Client(rs.getString("cl.sexe"),rs.getInt("u.id_user"),rs.getString("u.email"),rs.getString("u.password"),rs.getString("u.role"),
                 rs.getString("u.nom"),rs.getString("u.prenom"),rs.getString("u.adresse"),rs.getString("u.tel"),rs.getDate("u.dns")),rs.getInt("c.prix_livraison"),rs.getDate("c.date_commande")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
        return commandes;
    }
    
    
    //Afficher les commandes par id_client
    @Override
    public List<Commande> AfficherCommandeId(int id) {
        List<Commande> commandes = new ArrayList<Commande>();
         String req="SELECT * FROM commande c inner join user u on c.id_user = u.id_user inner join client cl on u.id_user = cl.id_user where c.id_user ="+id+"";        
        try {
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            while(rs.next()){
         
               commandes.add(new Commande(rs.getInt("c.id_commande"),new Client(rs.getString("cl.sexe"),rs.getInt("u.id_user"),rs.getString("u.email"),rs.getString("u.password"),rs.getString("u.role"),
                 rs.getString("u.nom"),rs.getString("u.prenom"),rs.getString("u.adresse"),rs.getString("u.tel"),rs.getDate("u.dns")),rs.getInt("c.prix_livraison"),rs.getDate("c.date_commande")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
        return commandes;
    }

    
    /************Tri par date*********/
    @Override
    public List<Commande> TrierCommandesParDate() {
        List<Commande> commandes = new ArrayList<Commande>();
         String req="SELECT * FROM commande c inner join user u on c.id_user = u.id_user inner join client cl on u.id_user = cl.id_user";        
        try {
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(req);

            while(rs.next()){
         
               commandes.add(new Commande(rs.getInt("c.id_commande"),new Client(rs.getString("cl.sexe"),rs.getInt("u.id_user"),rs.getString("u.email"),rs.getString("u.password"),rs.getString("u.role"),
                 rs.getString("u.nom"),rs.getString("u.prenom"),rs.getString("u.adresse"),rs.getString("u.tel"),rs.getDate("u.dns")),rs.getInt("c.prix_livraison"),rs.getDate("c.date_commande")));
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }
        return Tri(commandes);
    }
    
    public List<Commande> Tri(List<Commande> l){
            return l.stream()
            .sorted((a, b) -> a.getDate_commande().compareTo(b.getDate_commande()))
             .collect(Collectors.toList());
    }
}
