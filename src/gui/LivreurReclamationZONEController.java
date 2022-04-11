/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gui;

import java.net.URL;
import java.util.List;
import java.util.ResourceBundle;
import java.util.stream.Collectors;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.TextArea;
import models.Livraison;
import models.Reclamation;
import services.ServiceLivraison;
import services.ServiceReclamation;

/**
 * FXML Controller class
 *
 * @author User
 */
public class LivreurReclamationZONEController implements Initializable {
static int LivreurConnectedId=3;
    @FXML
    private TextArea Liste;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        Liste.setEditable(false);
        ServiceReclamation SR = new ServiceReclamation();
        List<Reclamation> Rlist = SR.ReclamationParLivreur(LivreurConnectedId);
        for(Reclamation r : Rlist){
        Liste.setText(Liste.getText() + " You are warned : " + r.getDescription());
        }
        
 }    }

