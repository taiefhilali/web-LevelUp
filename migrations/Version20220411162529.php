<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411162529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY fk_comment_post');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FKPOST');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FKUSER');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE vote');
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_user');
        $this->addSql('ALTER TABLE administrateur CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E86B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_u');
        $this->addSql('ALTER TABLE client CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404556B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_user1');
        $this->addSql('ALTER TABLE commande CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES client (id_user)');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY fk_commande1');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY fk_pr');
        $this->addSql('ALTER TABLE detail_commande CHANGE id id INT DEFAULT NULL, CHANGE id_commande id_commande INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA6BF396750 FOREIGN KEY (id) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA63E314AE8 FOREIGN KEY (id_commande) REFERENCES commande (id_commande)');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY fk_facture');
        $this->addSql('ALTER TABLE facture CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664106B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY u');
        $this->addSql('ALTER TABLE fournisseur CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA326B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY fk_livraison_commande');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY fk_livraison_user');
        $this->addSql('ALTER TABLE livreur DROP FOREIGN KEY ul');
        $this->addSql('ALTER TABLE livreur CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE livreur ADD CONSTRAINT FK_EB7A4E6D6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY fk_panier_user');
        $this->addSql('DROP INDEX fk_cl ON panier');
        $this->addSql('ALTER TABLE panier CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF26B3CA4B FOREIGN KEY (id_user) REFERENCES client (id_user)');
        $this->addSql('ALTER TABLE panier_elem DROP FOREIGN KEY fk_panier');
        $this->addSql('ALTER TABLE panier_elem DROP FOREIGN KEY fk_pr1');
        $this->addSql('DROP INDEX id_panier ON panier_elem');
        $this->addSql('ALTER TABLE panier_elem CHANGE id_panier id_panier INT DEFAULT NULL, CHANGE id id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier_elem ADD CONSTRAINT FK_B31E4D17BF396750 FOREIGN KEY (id) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE panier_elem ADD CONSTRAINT FK_B31E4D172FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY fk_CategorieProduit');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY fk_idFournisseur');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE prix_final prix_final DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC276B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_2');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_livraison id_livraison INT DEFAULT NULL, CHANGE WARN WARN TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640426392338 FOREIGN KEY (id_livraison) REFERENCES livraison (id_livraison)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY fk_stock_produit');
        $this->addSql('ALTER TABLE stock CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660BF396750 FOREIGN KEY (id) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE user CHANGE locked locked TINYINT(1) DEFAULT NULL, CHANGE tentative tentative INT NOT NULL, CHANGE image image VARCHAR(254) DEFAULT \'"\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (idc INT AUTO_INCREMENT NOT NULL, id_post INT NOT NULL, contenu TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, resp INT NOT NULL, INDEX fk_comment_post (id_post), PRIMARY KEY(idc)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, datep DATE NOT NULL, INDEX fkpost_user (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vote (idv INT AUTO_INCREMENT NOT NULL, id INT NOT NULL, id_user INT NOT NULL, vote_type INT NOT NULL, INDEX fk_post_vote (id), INDEX fk_user_vote (id_user), PRIMARY KEY(idv)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_comment_post FOREIGN KEY (id_post) REFERENCES post (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT fk_post_user FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FKPOST FOREIGN KEY (id) REFERENCES post (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FKUSER FOREIGN KEY (id_user) REFERENCES post (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E86B3CA4B');
        $this->addSql('ALTER TABLE administrateur CHANGE id_user id_user INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_user FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404556B3CA4B');
        $this->addSql('ALTER TABLE client CHANGE id_user id_user INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_u FOREIGN KEY (id_user) REFERENCES user (id_user) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('ALTER TABLE commande CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_user1 FOREIGN KEY (id_user) REFERENCES client (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA6BF396750');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA63E314AE8');
        $this->addSql('ALTER TABLE detail_commande CHANGE id id INT NOT NULL, CHANGE id_commande id_commande INT NOT NULL');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT fk_commande1 FOREIGN KEY (id_commande) REFERENCES commande (id_commande) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT fk_pr FOREIGN KEY (id) REFERENCES produit (id_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664106B3CA4B');
        $this->addSql('ALTER TABLE facture CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT fk_facture FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA326B3CA4B');
        $this->addSql('ALTER TABLE fournisseur CHANGE id_user id_user INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT u FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT fk_livraison_commande FOREIGN KEY (id_commande) REFERENCES commande (id_commande) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT fk_livraison_user FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livreur DROP FOREIGN KEY FK_EB7A4E6D6B3CA4B');
        $this->addSql('ALTER TABLE livreur CHANGE id_user id_user INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE livreur ADD CONSTRAINT ul FOREIGN KEY (id_user) REFERENCES user (id_user) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF26B3CA4B');
        $this->addSql('ALTER TABLE panier CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT fk_panier_user FOREIGN KEY (id_user) REFERENCES client (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX fk_cl ON panier (id_user)');
        $this->addSql('ALTER TABLE panier_elem DROP FOREIGN KEY FK_B31E4D17BF396750');
        $this->addSql('ALTER TABLE panier_elem DROP FOREIGN KEY FK_B31E4D172FBB81F');
        $this->addSql('ALTER TABLE panier_elem CHANGE id id INT NOT NULL, CHANGE id_panier id_panier INT NOT NULL');
        $this->addSql('ALTER TABLE panier_elem ADD CONSTRAINT fk_panier FOREIGN KEY (id_panier) REFERENCES panier (id_panier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_elem ADD CONSTRAINT fk_pr1 FOREIGN KEY (id) REFERENCES produit (id_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX id_panier ON panier_elem (id_panier, id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC276B3CA4B');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE produit CHANGE id_user id_user INT NOT NULL, CHANGE id_categorie id_categorie INT NOT NULL, CHANGE prix_final prix_final DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_CategorieProduit FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_idFournisseur FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640426392338');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation CHANGE id_livraison id_livraison INT NOT NULL, CHANGE id_user id_user INT NOT NULL, CHANGE WARN WARN TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_2 FOREIGN KEY (id_livraison) REFERENCES livraison (id_livraison) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660BF396750');
        $this->addSql('ALTER TABLE stock CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT fk_stock_produit FOREIGN KEY (id) REFERENCES produit (id_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE locked locked TINYINT(1) DEFAULT 0, CHANGE tentative tentative INT DEFAULT 0 NOT NULL, CHANGE image image VARCHAR(254) DEFAULT \'""\'');
    }
}
