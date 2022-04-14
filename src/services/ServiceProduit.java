package services;

import interfaces.Iproduit;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;
import models.Categorie;
import models.User;
import models.Produit;
import utils.MaConnexion;

/**
 *
 * @author Iskander
 */
public class ServiceProduit implements Iproduit {

    // Variable 1 : Variable de connexion 
    Connection cnx = MaConnexion.getInstance().getCnx();

    @Override
    // Crud d'ajouter d'un produit selon un produit saisie {{ WITHOUT USER ID ]] 

    public void ajouterProduit(Produit p) {

        String request = "INSERT INTO `produit`(`nom`, `reference`, `id_categorie`, `prix`, `description`,`id_user`,`promotion`) "
                + "VALUES ('" + p.getNom() + "','"
                + p.getReference() + "','"
                + p.getCategorie().getId_categorie() + "','"
                + p.getPrix() + "','"
                + p.getDescription() + "','"
                + p.getUser().getId() + "','"
                + p.getPromotion() + "')";
        try {
            // Définier une var Statement responsable de la connexion
            Statement st = cnx.createStatement();
            //Execution da la fonction statement
            //execute update pour l'éxecution même au cours du modification i lfait maj
            st.executeUpdate(request);
            System.out.println(" ** L'ajout du produit est établie avec succés! **  ");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    // Crud: Modification d'un produit existant selon son objet p et non l'id
    @Override
    public void modifierProduit(Produit p) {
        String request = "UPDATE `produit` SET `nom`='" + p.getNom()
                + "',`reference`='" + p.getReference()
                + "',`id_categorie`=" + p.getCategorie().getId_categorie()
                + ",`prix`=" + p.getPrix()
                + ",`description`='" + p.getDescription()
                + "',`id_user`=" + p.getUser().getId()
                + ",`promotion`=" + p.getPromotion()
                + " WHERE id_produit =" + p.getId_produit() + " ";
        try {
            Statement st = cnx.createStatement();
            st.executeUpdate(request);
            System.out.println(" ** Produit modifié avec succés ** ");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    // Crud suppression selon l'appel d'un id d'un produit
    @Override
    public void supprimerProduit(Produit p) {
        String request = "DELETE FROM `produit` WHERE `id_produit` = " + p.getId_produit() + " ";
        try {
            Statement st = cnx.createStatement();
            st.executeUpdate(request);
            System.out.println(" ** Produit supprimé avec succés ** ");
        } catch (SQLException ex) {
            ex.printStackTrace();
            System.out.println(" Erreur de suppression ");
        }
    }

    // Affichage de tout les produits du magasin sans exception 
    @Override
    public List<Produit> afficherProduit() {
        List<Produit> Produits = new ArrayList<Produit>();
        String query = "SELECT * FROM produit p inner join categorie c on p.id_categorie  = c.id_categorie";
        try {
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(query);
            while (rs.next()) {

                Produits.add(new Produit(rs.getInt("id_produit"),
                        rs.getString(2),
                        rs.getString(3),
                        new Categorie(rs.getInt("c.id_categorie"),
                                rs.getString("c.nom_categorie")),
                        rs.getDouble(5),
                        rs.getString(6),
                        new User(rs.getInt("id_user")),
                        rs.getDouble(8)
                //,rs.getString("email"),rs.getString("password"),rs.getString("role"),rs.getString("nom"),rs.getString("prenom"),rs.getString("adresse"),rs.getString("tel"),rs.getDate("dns"))
                ));
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
        return Produits;
//    }    // Exemple métier : Top 10 des produits selon chaque categories ( selon table commande) 
    }

    // Fonction de tri des produits par prix croissants ( du prix le plus faible vers le prix le plus elevée ) 
    @Override
    public void TrierProduitParPrix() {
        List<Produit> Produits = new ArrayList<Produit>();
        String query = "SELECT * FROM produit p inner join categorie c on p.id_categorie  = c.id_categorie join user u on p.id_user = u.id_user ";
        try {
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(query);
            while (rs.next()) {
                Produits.add(new Produit(rs.getInt("id_produit"),
                        rs.getString(2),
                        rs.getString(3),
                        new Categorie(rs.getInt("c.id_categorie"), rs.getString("c.nom_categorie")),
                        rs.getDouble(5),
                        rs.getString(6),
                        new User(rs.getInt("id_user"), rs.getString("email"),
                                rs.getString("password"),
                                rs.getString("role"),
                                rs.getString("nom"),
                                rs.getString("prenom"),
                                rs.getString("adresse"),
                                rs.getString("tel"),
                                rs.getDate("dns")),
                        rs.getDouble(8)
                ));
            }
            // Métier : Tri ascendant des Prix + // Devis transaction
            Produits.stream()
                    .sorted((a, b) -> Double.compare(a.getPrix(), b.getPrix())) // Desc + GroupBY DESC
                    .forEach(x -> System.out.println(" Le prix triée selon l'ordre croissant est : " + x));
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    // ================  Métier : calcul de promotion avec input de l'administrateur et non automatiquement ============== //
    @Override
    public Produit calculerPromotion(Produit p) {
        if (p.getPromotion() != 0) {
            p.setPrix(p.getPrix() - (p.getPrix() * (p.getPromotion() / 100)));
            System.out.println(" Le prix final aprés promotion est : " + p.getPrix());
        }
        return p;
    }

    @Override
    public List<Produit> chercherProduitDynamiquement(String s, List<Produit> l) {
        List<Produit> strList = l.stream()
                .map(Produit::concat)
                .filter(pt -> pt.contains(s)) //starts with (only searches for a person's id)
                .map(pt -> new Produit(
                Integer.parseInt(pt.split("-")[0]),
                pt.split("-")[1],
                pt.split("-")[2],
                new Categorie(Integer.parseInt(pt.split("-")[3]), pt.split("-")[4]),
                Double.parseDouble(pt.split("-")[5]),
                pt.split("-")[6],
                new User(Integer.parseInt(pt.split("-")[7])),
                Double.parseDouble(pt.split("-")[8])
        ))
                //Dans le cas ou je veut limiter le nombre des produits affichés         .limit(5)
                .collect(Collectors.toList());
        return strList;
    }
}
