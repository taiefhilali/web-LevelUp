/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Pos;
import javafx.scene.Node;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.stage.Window;
import models.Livraison;
import models.Reclamation;
import models.User;
import org.controlsfx.control.Notifications;
import services.ServiceLivraison;
import services.ServiceReclamation;

/**
 * FXML Controller class
 *
 * @author User
 */
public class ClientReclamationController implements Initializable {

    @FXML
    private TextField idReclamation;
    @FXML
    private TextField idUser;
    @FXML
    private ComboBox<Object> idLivraison;
    @FXML
    private TextArea Description;
    @FXML
    private Button Enregistrer;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        ServiceLivraison sl = new ServiceLivraison();

   List<Livraison> livFromdatabase= sl.afficherLivraison();
  ObservableList<Object> listLivData = FXCollections.observableArrayList();
        listLivData.addAll(livFromdatabase);
idLivraison.getItems().addAll(listLivData);

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
    private void Enregistrer(ActionEvent event) {
     //   int id_reclamation, User user, Livraison livraison, String description
      if (idReclamation.getText().isEmpty())
     {
         showAlert(Alert.AlertType.ERROR, ((Node) event.getSource()).getScene().getWindow(),
                    "Error!","Id reclamation non valid ");
     
     }
     else if (idUser.getText().isEmpty())
     {
         showAlert(Alert.AlertType.ERROR, ((Node) event.getSource()).getScene().getWindow(),
                    "Error!","Id user non valid ");
     
     } 
     else if (idLivraison.getValue()==null)
     {
     showAlert(Alert.AlertType.ERROR, ((Node) event.getSource()).getScene().getWindow(),
                    "Error!","Id livraison non valid ");
     }
     
     else  if (Description.getText().isEmpty())
     {
     showAlert(Alert.AlertType.ERROR, ((Node) event.getSource()).getScene().getWindow(),
                    "Error!","description vide ");
     }
     else {
      User u = new User();
                u.setId(Integer.valueOf(idUser.getText()));
                Livraison LivSelected = (Livraison) idLivraison.getSelectionModel().getSelectedItem();

        Reclamation r = new Reclamation(Integer.valueOf(idReclamation.getText()),u,LivSelected,Description.getText());
        ServiceReclamation rs = new ServiceReclamation();
        rs.ajouterReclamation(r);
        idReclamation.setText("");
        Description.setText("");}
  Notifications notificationBuilder = Notifications.create()
                .title("RECLAMATION").text("RECLAMATION AJOUTEE AVEC SUCCES").graphic(null).hideAfter(javafx.util.Duration.seconds(5))
                .position(Pos.CENTER_LEFT)
                .onAction(new EventHandler<ActionEvent>() {
                    public void handle(ActionEvent event) {
                        System.out.println("clicked ON ");
                    }
                });
        notificationBuilder.darkStyle();
        notificationBuilder.show();

    }
}
