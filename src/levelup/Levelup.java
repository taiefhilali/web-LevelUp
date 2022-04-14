/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package levelup;

import interfaces.ICommande;
import interfaces.ICommande_elem;
import interfaces.IPanier;
import interfaces.IPanier_elem;
import interfaces.Iadministrateur;
import interfaces.Icategorie;
import interfaces.Iclient;
import interfaces.Icomment;
import interfaces.Ifacture;
import interfaces.Ifournisseur;
import interfaces.Ilivraison;
import interfaces.Ilivreur;
import interfaces.Ipost;
import interfaces.Istock;
import interfaces.Iuser;
import interfaces.Iproduit;
import interfaces.Ireclamation;
import java.sql.Connection;
import models.Administrateur;
import models.Client;
import models.Fournisseur;
import models.Livreur;
import models.User;
import services.ServiceAdministrateur;
import services.ServiceClient;
import services.ServiceFournisseur;
import services.ServiceLivreur;
import services.ServiceUser;
import utils.MaConnexion;
import java.sql.Date;
import java.time.LocalDate;
import models.Categorie;
import models.Commande;
import models.Commande_elem;
import models.Comment;
import models.Facture;
import models.Livraison;
import models.Panier;
import models.Panier_elem;
import models.Post;
import models.Produit;
import models.Reclamation;
import models.Stock;
import services.ServiceCategorie;
import services.ServiceProduit;
import services.ServiceCommande;
import services.ServiceCommande_elem;
import services.ServiceFacture;
import services.ServiceLivraison;
import services.ServicePanier_elem;
import services.ServiceReclamation;
import services.ServiceStock;
import services.Servicecomment;
import services.Servicepost;
import services.servicePanier;

/**
 *
 * @author beldi
 */
public class Levelup {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        Connection cnx = MaConnexion.getInstance().getCnx();

        String str = "2018-03-21";
        Date date = Date.valueOf(str);

        String adr = "Ben arouse/Mourouj 1/Rue 234/2074";

        //  ******************************************* SERVICES *******************************************  //
        Iuser sp = new ServiceUser();
        Iclient sc = new ServiceClient();
        Ifournisseur sf = new ServiceFournisseur();
        Ilivreur sl = new ServiceLivreur();
        Iadministrateur sa = new ServiceAdministrateur();
        Iproduit sproduit = new ServiceProduit();
        Ifacture sfacture = new ServiceFacture();
        Istock ss = new ServiceStock();
        Icategorie scategorie = new ServiceCategorie();
        Ipost spost = new Servicepost();
        Icomment scomment = new Servicecomment();
        ICommande scommande = new ServiceCommande();
        IPanier spanier = new servicePanier();
        IPanier_elem spanier2 = new ServicePanier_elem();
        ICommande_elem spanier3 = new ServiceCommande_elem();
        Ilivraison slivraison = new ServiceLivraison();
        Ireclamation sr = new ServiceReclamation();

        //  ******************************************* USERS ******************************************* //
        User u = new User();
//        //Authentification
         //   System.out.println(sp.authentification("bayoudh.hazem@gmail.com",u.encrypt("password")));
////        System.out.println(sp.authentification("nouira.amal@gmail.com",u.encrypt("password")));
////        System.out.println(sp.authentification("bayoudh.hazem@gmail.com",u.encrypt("test"))); 
////        System.out.println(sp.authentification("iskander.bargaoui@gmail.com",u.encrypt("password")));

//        //getByEmail
        //System.out.println(sp.getByEmail("beldi.mariem@gmail.com"));
//        //Recherche par role 
     //  System.out.println(sp.afficherParRole("client"));
//        //Recherche par lettre nom
    ///  System.out.println(sp.afficherParLettre("H"));
//        //Rechercher par prenom
      // System.out.println(sp.afficherParPrenom("Mariem"));
//        //System.out.println(u.encrypt("password"));
//        //Affichage des utilisateurs
//        //Ajout des utilisateurs
//        // Client   
        User c7 = new User("essid.cyrine@gmail.com", "mariem", "client", "essid", "cyrine", adr, "26386558", date);

      //  Client client = new Client("femme", (int) sp.ajouterPersonne(c7));

//             sc.ajouterPersonne(client);
//        // AFFICHER Client
//        //  System.out.println(sc.afficherPersonnes());
//        // Fournisseur   
////        User user2 = new User("beldi.mohamed@gmail.com", u.encrypt("test"), "fournisseur", "beldi", "mohamed", adr, "26386558",date);
////        Fournisseur f =new Fournisseur("07227308","Arvea", (int)sp.ajouterPersonne(user2));
////        sf.ajouterPersonne(f);
//        // AFFICHER Fournisseur
//        // System.out.println(sf.afficherPersonnes());
//        // Livreur   
////        User user3 = new User("amara.manel@gmail.com", u.encrypt("test"), "livreur", "beldi", "mariem", adr, "26386558",date);
////        Livreur l =new Livreur("00000","07227308", (int)sp.ajouterPersonne(user3));
////        sl.ajouterPersonne(l);
//        // AFFICHER Livreur
//        //System.out.println(sl.afficherPersonnes());
//        // Administrateur   
////        User user4 = new User("beldi.mariem@gmail.com", u.encrypt("test"), "administrateur", "beldi", "mariem", adr, "26386558", date);
////        Administrateur a = new Administrateur("07227308", (int) sp.ajouterPersonne(user4));
////        sa.ajouterPersonne(a);
//        //AFFICHER Administrateur
//        //System.out.println(sa.afficherPersonnes());
//        //Modification des utilisateurs
//        // Client 
//        Client c = new Client("homme", 1, "beldi.mariem@gmail.com", u.encrypt("test"), "client", "beldi", "amal", adr, "26386558", date);
//
//        // System.out.println(sp.modifierPersonne(c));
//        //System.out.println(sc.modifierPersonne(c));
//        //System.out.println(sc.afficherPersonnes());
//        //Fournisseur
//        Fournisseur fu = new Fournisseur("08227308", "Arvea", 40, "beldi.mariem@gmail.com", u.encrypt("test"), "fournissuer", "beldi", "mariem", adr, "26386558", date);
////        System.out.println(sp.modifierPersonne(f));
////        System.out.println(sf.modifierPersonne(f));
//        // System.out.println(sf.afficherPersonnes());
//        //Livreur
//        Livreur lu = new Livreur("0234", "09227308", 41, "beldi.mariem@gmail.com", u.encrypt("test"), "livreur", "beldi", "mariem", adr, "26386558", date);
////        System.out.println(sp.modifierPersonne(l));
////        System.out.println(sl.modifierPersonne(l));
//        //System.out.println(sl.afficherPersonnes());
//        //Administrateur
//        Administrateur au = new Administrateur("06227308", 42, "beldi.mariem@gmail.com", u.encrypt("test"), "administrateur", "beldi", "mariem", adr, "26386558", date);
//        System.out.println(sp.modifierPersonne(a));
//        System.out.println(sa.modifierPersonne(a));
        //System.out.println(sa.afficherPersonnes());
        //Supression des utilisateurs
        //Client
        // System.out.println(sp.supprimerPersonne(cu));
        //System.out.println(sc.supprimerPersonne(cu));
        //Fournisseur
//        System.out.println(sp.supprimerPersonne(fu));
//        System.out.println(sf.supprimerPersonne(fu));
        //Livreur
//        System.out.println(sp.supprimerPersonne(lu));
//        System.out.println(sl.supprimerPersonne(lu));
        //Administrateur
//        System.out.println(sp.supprimerPersonne(au));
//        System.out.println(sa.supprimerPersonne(au));
        
        
        
        
        
        
        //  *******************************************  Forum *******************************************   //
        User c = new User(9, "taief.hilali@esprit.com", "mariem", "client", "hilali", "taief", adr, "26386558", date);
        Post post1 = new Post("sujet11", "JEUX VIDEOS", date, c, 30, 12);
        Post post2 = new Post("Sujet2", " fournisseur", date, c, 4, 1);
        Post post4 = new Post("newss", " viDEOS", date, c, 4, 2);
        Post post5 = new Post(74, "sujet5", "videogame", date, c, 45, 0);
        //Comment
        Comment comment1 = new Comment("firstcomment", "comm", 12, c, post5);
        Comment comment2 = new Comment("secondcomment", "commtwo", 10, c, post5);

        // ****************AJOUT post********************************//
        
        
        //   System.out.println("********************AJOUT post************************!");
       // System.out.println(spost.ajouterPost(post1));
        //System.out.println(spost.ajouterPost(post2));
        
        
        
        //****************Afficherpost*******************************************//
//        System.out.println("******Afficher post************************");
//        System.out.println(spost.afficherPost());
        
        
        //****************AjoutComment**************************//
       
        // System.out.println("COMMENT added!");
          //   System.out.println("********************AJOUT commentaire************************!");
        //System.out.println(scomment.ajouterComment(comment2));
        
        
        
        
        //********************Affichercomment**************************************//
          //   System.out.println("********************AFFICHER commentaire************************!");
        // System.out.println(scomment.afficherComment());
        
        
        
        
        //********************** MODIFIERPOST**************************//
        Post post3 = new Post(75, "sujet2", "great news", date, c, 4, 2);
//        //System.out.println("POST Updated\n");
//          System.out.println("********************MODIFIER post************************!");
//        System.out.println(spost.modifierPost(post3));
        
//        //********************updatecomment******************//
//
//        Comment comment3 = new Comment(14, "THIRDcomment", "HAHAAHA", 13, c, post5);
//        //  Comment comment3= new Comment(24, "THIRDcomment", "HAHAAHA", 13,c,post4);

//        ///***********jointure*************************//
//        System.out.println(scomment.ajouterComment(comment1));
        //System.out.println("COMMENT Updated\n");
        // System.out.println(scomment.modifierComment(comment3));
        //*****************deletepost
        // System.out.println("POST Deleted");
        // System.out.println(spost.supprimerPost(post3));
        //*******************deleteComment
//        // System.out.println("COMMENT Deleted");
//        //System.out.println(scomment.supprimerComment(comment3));
        
        
        
//*******TRIE*****************//
        //ArrayList<Post> posts= new ArrayList<>();
        //    posts=spost.AfficherTrie();
        
        
        
        //**************rechercher post par titre******//
//System.out.println("RECHERCHE SELON <titre>  DES POSTS :"+spost.findBytitle("Sujet2"));
        
        
////**********************trie selon date********** stream//
//System.out.println("TRIE SELON <DATE>  DES POSTS :"+spost.sortByDate());
        
        
        
//        // *** count*********//
 //     scomment.Commentsum();
        
        
        
//        // ******************SORT POST WITH GREATEST post with  LIKES//
   //   System.out.println("TRIE SELON <NBLIKE>  DES POSTS :" + spost.sortBynblike());
//        
        
        
//        ///****************BEST POST**********//////
//      
 //System.out.println("LES Meilleures POSTS :" + spost.bestpost(29));
        //******************************************* LIVRAISON ET RECLAMATION *****************************************  //
        // Ajout livraison 
       // Client client = new Client("femme",9);
//       // Commande commande1 = new Commande(8, client, 20, date);
//        Livraison l1 = new Livraison(commande1, c, date, "en cours");
//        //System.out.println(slivraison.ajouterLivraison(l1));
//        //Ajout reclamation
//        Livraison livraison = new Livraison(9, commande1, c, date, "en cours");
//        Reclamation r1 = new Reclamation(c, livraison, "satisfait");
//       // System.out.println(sr.ajouterReclamation(r1));
//
//        //Modifier livraison 
//        Livraison l2 = new Livraison(9, commande1, c, date, "en cours");
//        //System.out.println(slivraison.modifierLivraison(l2));
//        //Modifier reclamation
//        Reclamation r2 = new Reclamation(14, c, livraison, "satisfait");
//        //System.out.println(sr.modifierReclamation(r2));
//
//        //SUPPRIMER livraison
//        //System.out.println(slivraison.supprimerLivraison(livraison));
//        //SUPPRIMER reclamation
        //System.out.println(sr.supprimerReclamation(r2));
        //afficher
//   System.out.println("test affichage livraison");
//     System.out.println(slivraison.afficherLivraison());
//  System.out.println("test affichage reclamation");
        //  System.out.println (sr.afficherReclamation());
//        System.out.println("rechercher par User");
        User u2 = new User(9);
//      System.out.println(sr.rechercheReclamationParUser(u2));
//      System.out.println(sr.triReclamationParUser());
        
        
        
        // *******************************************  STOCKS *******************************************   //
      Stock s = new Stock("jeux", 13, "en repture");
      Fournisseur fu =new Fournisseur("07227308","Arvea", (int)sp.ajouterPersonne(c7));
       Facture f = new Facture(date, "90.990dt", fu);
       
       
//        //***************************Ajout  stock****************************
       
      
//        //ss.ajouterStock(s);
       
         //*****************************Ajout facture***************************
       
//        //sfacture.ajouterFacture(f);
       
       
//        //**************************Affichage stocks**************************
//
//        //System.out.println(ss.afficherStock());
       
       
        //*******************************Affichage factures****************************
       
        //System.out.println(sfacture.afficherFacture());
       
       
//        //******************************Modification stock**********************
       
        //Stock s1 = new Stock(56, "pc", 22, "en stock");
       //System.out.println(ss.modifierStock(s1));
       
        //**************************Modification facture*********************************
       
//        Facture f1 = new Facture(27, date, "61.650dt", fu);
//        //System.out.println(sfacture.modifierFacture(f1));
       
       
        //****************************************Suppression stock******************************** 
       
        //System.out.println(ss.supprimerStock(s1));
       
       //*****************************************Suppression  facture**************************************
       
        //System.out.println(sfacture.supprimerFacture(f1));
       
       
       //****************************************prix facture******************************
       
        //sfacture.prix();
       
       
        //  *******************************************  PRODUITS ET CATEGORIES *******************************************   //    
        User u1 = new User(1, "Amalouira@gmail.com", "test", "client", "beldi", "mariem", adr, "26386558", date);
        // Test 2 : aprés jointure
        // Insertion d'apres phpmyadmin insert value vide id
        Categorie c1 = new Categorie(1, "Souris");
        Categorie c3 = new Categorie(2, "Accessoires PC");
//        scategorie.ajouterCategorie(c3);
//   Produit p1 = new Produit("Logitech G56", "0X0009", c1, 500, " Un casque pour les Gamers pro ", u1, 50);
//    sproduit.ajouterProduit(p1);
        Produit p1 = new Produit(21, "Razer Kraken Pro v8", "0x005", c1, 500, " Un casque pour les Gamers pro ", u1, 60);
        Produit p2 = new Produit(50, "Produit x promotion", "0x0006", c1, 700, " test promo", u1, 80);
//         Produit p4 = new Produit(50, "Produit x promotion v2 ", "0x0006", c1, 700, " test promo", user1, 40);

                                                    /// TEST AJOUT ET SUPPRESSION //
        
                                                // System.out.println(" **** Ajout des Produits **** ");
        // Produit p1 = new Produit(48, "Razer Kraken Pro v7", "0x005", c1, 900, "Casque que pour les gamers", user1, 20);
//        sproduit.ajouterProduit(p1);
        //Produit p2 = new Produit("HyperX Cloud II", "0x001", c1, 350, "Casque que pour les gamers", user1, 50);
//        sproduit.ajouterProduit(p2);
//        sproduit.modifierProduit(p1);
//        sproduit.supprimerProduit(p1);
//        // sproduit.ajouterProduit(p7);
////            sproduit.ajouterProduit(p6);
////        Produit p3 = 
////        sproduit.ajouterProduit(p3);
////        sproduit.ajouterProduit(p2);
////        sproduit.ajouterProduit(p4);
                
                                            // TEST AFFICHAGE DES PRODUITS ET CATEGORIES //
        
//        System.out.println("\n **********Affichage de touts les produits********** \n");
//        System.out.println(sproduit.afficherProduit());
        //sproduit.ajouterProduit(p2);
        //sproduit.ajouterProduit(p2);
//        sproduit.modifierProduit(p2);
//        sproduit.supprimerProduit(p2);
        //     System.out.println(sproduit.afficherProduit());
//        sproduit.modifierProduit(p2);
//        sproduit.supprimerProduit(p2);
        Categorie c2 = new Categorie(5, "Bureaux");
//        scategorie.ajouterCategorie(c2);
        
        
            // TEST METIERS PRODUITS ET CATEGORIESSSSSS //

//        
//        System.out.println("\n *******Affichage de toutes les catégories***** \n  ");
//        System.out.println(scategorie.afficherCategorie());
//        System.out.println("\n *******Affichage de toutes les catégories triés par leurs IDS: ***** \n  ");
//        System.out.println(scategorie.retrieveCategorieById(1));
//        System.out.println(scategorie.retrieveCategorieById(2));
//        System.out.println(scategorie.retrieveCategorieById(35));
//        System.out.println("\n *******Rercheche avancée dynamique: ***** \n  ");
//        System.out.println(sproduit.chercherProduitDynamiquement("H", sproduit.afficherProduit()));
//        System.out.println("\n Trier les produits par prix croissant \n");
//        sproduit.TrierProduitParPrix(); // Prix ascendant
//
//        System.out.println(" \n *********** Calcul prix arpés promotion ******** \n");
//
//        sproduit.calculerPromotion(p1);
//        sproduit.calculerPromotion(p2);
//        sproduit.afficherProduit();

//        // Ajout de deux produits 
//        // Produit p = new Produit("HyperX Cloud II", "0x001", 1, 0, " Un casque pour les Gamers pro.", " En Stock ", 365); //en TND
//        // Produit p1 = new Produit("Razer Krake Pro v7", "0x001", 2, 600, " Un casque pour les Gamers pro.", " En Stock ", 2);
//        // Produit p2 = new Produit("Chaise v1", "0X000", 2, 60, "Chaise pour les Gamers", " En stock", 4);    // produit avec id
//        // Test 1 : (without joints)  Produit p2 = new Produit(38, "Chaise", ")Xààç", 3, 660, "chaise pour les gamer ", " En stock", 6);
//        //test modification
//        //Produit p2 = new Produit("Chaise v1 modifiée", "0X000", 2, 60, "Chaise pour les Gamers", " En stock", 4);    // produit avec id
//        // (Test1 : Without joints) Produit p3 = new Produit(7, " Bureaux TX510", "0X0070", 3, 250, " Un bureau pour les gamers ", "  Out of stock ", 4);
//        // Ajout des produits 
//        //sproduit.ajouterProduit(p2);
//        //  sproduit.modifierProduit(p2);
//        // TEST AJOUT //
//        // sproduit.supprimerProduit(p2);
//        //sproduit.ajouterProduit(p2);
//        // sproduit.ajouterProduit(p3);
//        // TEST MODIFICATION //
//        //sproduit.modifierProduit(p2);
//        //sproduit.supprimerProduit(p3);
//        // sproduit.ajouterProduit(p);
//        // Modification des produits
//        //sproduit.modifierProduit(p1);
//        // Suppression des produits
//        //Produit p2 = new Produit(41, "Chaise", "0x005", 2, 100, "Chaise pour Gamers", "Out of stock", 1);
//        //sproduit.modifierProduit(p2);
//        // sproduit.ajouterProduit(p2);
//        // sproduit.supprimerProduit(p2);
//        // Affichage ajout produit
//        // System.out.println(sproduit.afficherProduit());
//        // Ajout d'une catégorie
//        // Categorie c = new Categorie("Chaises");
//        // Test ajout categorie
//        //  Categorie c1 = new Categorie(16, "Souris");
//        //  Categorie c4 = new Categorie(17, "Bureau 1");
//        //  scactegorie.ajouterCategorie(c4);
//        // scategorie.supprimerCategorie(c4); //
//        //scactegorie.ajouterCategorie(c1);
//        //scactegorie.modifierCategorie(c1);
//        //scactegorie.supprimerCategorie(c1);
        //  *******************************************  COMMANDE ET PANIER *******************************************  //
        // Client cu8 = new Client("homme",2);
        //Produit p3 = new Produit(47, "Razer Kraken Pro v7", "0x005", c1, 900, "Casque que pour les gamers", user1, 20);
        // Commande commande1 = new Commande(10,cu8, 25, date);
        //Panier panier1 = new Panier(4, cu8);
        //Panier_elem e = new Panier_elem(11, panier1, p3);
        //Commande_elem e1 = new Commande_elem(9, p3, commande1,3);
        //Affichage
        //System.err.println(spanier2.afficherPanier(4));
        //System.err.println(spanier3.AfficherCommande(10));
        //System.err.println(scommande.AfficherCommande());
        //System.err.println(scommande.AfficherCommandeId(9));
        //AJOUT
        //spanier.ajouterPanier(panier1);
        //spanier2.AjouterElementPanier(e);
        // spanier3.ajouterElementCommande(e1);
        //scommande.ajouterCommande(commande1);
        ///Modification
        //spanier3.modifierElementCommande(e1);
        //scommande.modifierCommande(commande1);
        //SUPPRESSION
        //spanier.supprimerPanier(panier1);
        //spanier3.supprimerElementCommande(e1);
        // spanier2.supprimerElementPanier(e);
        //scommande.SupprimerCommande(commande1);
        /**
         * *****Top Products*****
         */
         //System.err.println(spanier3.TopProduits(2));
        //System.err.println(scommande.TrierCommandesParDate());
    }

}
