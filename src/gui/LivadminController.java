/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import java.io.IOException;
import java.net.URL;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.Date;
import java.util.List;
import java.util.ResourceBundle;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.stream.Collectors;
import javafx.collections.FXCollections;
import javafx.collections.ListChangeListener;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.DatePicker;
import javafx.scene.control.Label;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.AnchorPane;
import javafx.stage.Window;
import models.Commande;
import models.Livraison;
import models.Livreur;
import models.User;
import services.ServiceCommande;
import services.ServiceLivraison;
import services.ServiceLivreur;

/**
 * FXML Controller class
 *
 * @author User
 */
public class LivadminController implements Initializable {

    String action = "";
    @FXML
    private Button affecterBtn;
    @FXML
    private Button supprimerbtn;
    @FXML
    private TextField recherche;
    @FXML
    private TableColumn<Livraison, String> Date_liv;
    @FXML
    private TableColumn<Livraison, String> etat_livraison;
    @FXML
    private TableView<Object> livTableau;
    @FXML
    private ComboBox<Object> liste_livreur;
    @FXML
    private Button ajouterbtn;
    @FXML
    private AnchorPane formLivraison;
    @FXML
    private Button enregistrer;
    @FXML
    private Button modifierbtn;
    @FXML
    private TextField idlivForm;
    @FXML
    private ComboBox<Object> idcommandeform;
    @FXML
    private ComboBox<Object> idlivreurform;
    @FXML
    private ComboBox<String> etatLivraison;
    @FXML
    private Label etatLivraisonlabel;
    @FXML
    private Button gestionRecBTN;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        affecterBtn.setDisable(true);
        supprimerbtn.setDisable(true);
        modifierbtn.setDisable(true);
        formLivraison.setOpacity(0);
        ServiceLivraison sl = new ServiceLivraison();
        ServiceCommande sC = new ServiceCommande();

        ServiceLivreur serviceLivreur = new ServiceLivreur();
        List<Livraison> livFromdatabase = sl.afficherLivraison();
        List<Livreur> LivreurFromDatabase = serviceLivreur.afficherPersonnes();
        List<Commande> CommandeFromDataBase = sC.AfficherCommande();

        ObservableList<Object> ListeLivreurData = FXCollections.observableArrayList();
        ListeLivreurData.addAll(LivreurFromDatabase);

        ObservableList<Object> CommandeData = FXCollections.observableArrayList();
        CommandeData.addAll(CommandeFromDataBase);

        liste_livreur.setItems(ListeLivreurData);
        idlivreurform.setItems(ListeLivreurData);
        liste_livreur.setDisable(true);
        idcommandeform.setItems(CommandeData);

        ObservableList<Object> listLivData = FXCollections.observableArrayList();

        Date_liv.setCellValueFactory(new PropertyValueFactory<>("date"));
        etat_livraison.setCellValueFactory(new PropertyValueFactory<>("etat_livraison"));
        listLivData.addAll(livFromdatabase);
        livTableau.setItems(listLivData);
        ObservableList selectedCells = livTableau.getSelectionModel().getSelectedCells();

        selectedCells.addListener(new ListChangeListener() {
            @Override
            public void onChanged(Change c) {
                Livraison livSelected = (Livraison) livTableau.getSelectionModel().getSelectedItem();
                System.out.println("selected value " + livSelected);
                if (livSelected != null) {
                    if (livSelected.getUser().getId() != 0 || livSelected.getUser() == null) {
                        affecterBtn.setText("Annuler l'affectation");
                    } else {
                        affecterBtn.setText("Affecter");

                    }
                    liste_livreur.setDisable(true);
                    modifierbtn.setDisable(false);
                    affecterBtn.setDisable(false);
                    supprimerbtn.setDisable(false);

                } else {

                    affecterBtn.setDisable(false);
                    supprimerbtn.setDisable(false);
                }
            }
        });

    }

    void mise_a_jourbase() {
        ServiceLivraison sl = new ServiceLivraison();
        List<Livraison> livFromdatabase = sl.afficherLivraison();
        ObservableList<Object> listLivData = FXCollections.observableArrayList();

        Date_liv.setCellValueFactory(new PropertyValueFactory<>("date"));
        etat_livraison.setCellValueFactory(new PropertyValueFactory<>("etat_livraison"));
        listLivData.addAll(livFromdatabase);
        livTableau.setItems(listLivData);

    }

    @FXML
    private void SupprimerLiv(ActionEvent event) {
        Livraison livSelected = (Livraison) livTableau.getSelectionModel().getSelectedItem();
        ServiceLivraison sl = new ServiceLivraison();
        sl.supprimerLivraison(livSelected);
        mise_a_jourbase();

    }

    @FXML
    private void Chercher(ActionEvent event) {

        ServiceLivraison sl = new ServiceLivraison();
        ObservableList<Object> list = FXCollections.observableArrayList();

        list.addAll(sl.afficherLivraison().stream().
                filter(l -> l.getEtat_livraison().contains(recherche.getText())).collect(Collectors.toList())
        );
        livTableau.setItems(list);

    }

    @FXML
    private void affecterAction(ActionEvent event) {
        Livraison livSelected = (Livraison) livTableau.getSelectionModel().getSelectedItem();
        if (livSelected.getUser().getId() != 0 || livSelected.getUser() == null) {
            livSelected.setUser(null);
            ServiceLivraison sl = new ServiceLivraison();
            sl.annulerAffectation(livSelected);
            mise_a_jourbase();

        } else {
            liste_livreur.setDisable(false);

        }
    }

    @FXML
    private void AffecterLivreurLivraison(ActionEvent event) {
        Livraison livSelected = (Livraison) livTableau.getSelectionModel().getSelectedItem();
        Livreur livreurSelected = (Livreur) liste_livreur.getSelectionModel().getSelectedItem();
        ServiceLivraison sl = new ServiceLivraison();
        sl.Affectation(livSelected, livreurSelected);
        mise_a_jourbase();
        liste_livreur.setDisable(true);

    }

    @FXML
    private void ajouter(ActionEvent event) {

        action = "ajouter";
        formLivraison.setOpacity(1);
        idlivForm.setText("");
        idlivForm.setDisable(false);
        etatLivraisonlabel.setOpacity(0);
        etatLivraison.setOpacity(0);
    }

    @FXML
    private void Modifier(ActionEvent event) {
        action = "modifier";
        formLivraison.setOpacity(1);
        Livraison livSelected = (Livraison) livTableau.getSelectionModel().getSelectedItem();
        idlivForm.setText(String.valueOf(livSelected.getId_livraison()));
        idlivForm.setDisable(true);
        etatLivraisonlabel.setOpacity(1);
        etatLivraison.setOpacity(1);
        etatLivraison.getItems().add(0, "En cours");
        etatLivraison.getItems().add(1, "Confirmee");
        etatLivraison.getItems().add(2, "Livree");

    }

    private void showAlert(Alert.AlertType alertType, Window owner, String title, String message) {
        Alert alert = new Alert(alertType);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.initOwner(owner);
        alert.show();
    }

    @FXML
    private void enregistrer(ActionEvent event) {

        if (idlivForm.getText().isEmpty()) {

            showAlert(Alert.AlertType.ERROR, ((Node) event.getSource()).getScene().getWindow(),
                    "Error!", "id livraison  vide ");

        } else if (idcommandeform.getValue() == null) {
             showAlert(Alert.AlertType.ERROR, ((Node) event.getSource()).getScene().getWindow(),
                    "Error!", "id commande  vide ");

        } else if (idlivreurform.getValue() == null) {
             showAlert(Alert.AlertType.ERROR, ((Node) event.getSource()).getScene().getWindow(),
                    "Error!", "mail livreur  vide ");
       
        }
        else{
        if (action.compareTo("ajouter") == 0) {
            Commande CommandeSelected = (Commande) idcommandeform.getSelectionModel().getSelectedItem();
            User LivreurSelected = (Livreur) idlivreurform.getSelectionModel().getSelectedItem();
            Date d = new Date();

            String EtatLiv = (String) etatLivraison.getSelectionModel().getSelectedItem();
            Livraison l = new Livraison(Integer.valueOf(idlivForm.getText()), CommandeSelected, LivreurSelected, d.toString(), "En cours");
            ServiceLivraison sl = new ServiceLivraison();
            sl.ajouterLivraison(l);
            mise_a_jourbase();

        } else {

            Livraison livSelected = (Livraison) livTableau.getSelectionModel().getSelectedItem();
            Commande CommandeSelected = (Commande) idcommandeform.getSelectionModel().getSelectedItem();
            User LivreurSelected = (Livreur) idlivreurform.getSelectionModel().getSelectedItem();

            livSelected.setCommande(CommandeSelected);
            livSelected.setUser(LivreurSelected);
            String EtatLiv = (String) etatLivraison.getSelectionModel().getSelectedItem();
            livSelected.setEtat_livraison(EtatLiv);
            ServiceLivraison sl = new ServiceLivraison();
            sl.modifierLivraison(livSelected);
            mise_a_jourbase();
        }
        formLivraison.setOpacity(0);
        idlivForm.setText("");
        idlivForm.setDisable(false);
        etatLivraisonlabel.setOpacity(0);
        etatLivraison.setOpacity(0);
        }
    }

    @FXML
    private void gotToREC(ActionEvent event) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("GestionReclmation.fxml"));
            Parent root = loader.load();
            gestionRecBTN.getScene().setRoot(root);
        } catch (IOException ex) {
            Logger.getLogger(GestionReclmationController.class.getName()).log(Level.SEVERE, null, ex);

        }
    }


}
