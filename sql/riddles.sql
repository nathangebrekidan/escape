CREATE TABLE riddles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    riddle VARCHAR(255) NOT NULL,
    answer VARCHAR(100) NOT NULL,
    hint VARCHAR(255),
    roomId INT NOT NULL
);

INSERT INTO riddles (riddle, hint, answer, roomId) VALUES

("Er staan vier bekers met gekleurde vloeistoffen: rood, blauw, groen en geel. Meng rood met blauw, blauw met geel en rood met groen. Hoeveel unieke kleuren ontstaan als we alle mengsels tellen?", "Mengsels van dezelfde kleuren tellen als één", "3", 1),
("Er staat een flesje met ethanol op tafel. In dit molecuul zijn er koolstofatomen, waterstofatomen en zuurstofatomen. Wat is het aantal waterstofatomen in ethanol?", "Ethanol: C2H6O", "6", 1),
("ATG CGT TAA GGC. Wat is het laatste cijfer van de code?", "Aantal codons dat start met T modulo 4", "1", 1),


("Je vindt een kaartje met de code 010-B-3. Het verwijst naar een plek in de bibliotheek. Waar staat de B voor?", "Denk aan categorieën in een bibliotheek", "Boekensectie", 2),
("In de Centrale Bibliotheek Rotterdam staat een beroemd standbeeld van een schrijver. Wie is het?", "Zijn naam begint met M", "Multatuli", 2),
("Je moet drie boeken vinden. De code zegt: 'Neem het boek met het laagste nummer in sectie R'. Wat betekent de R?", "R = Rotterdam boeken", "Rotterdam-collectie", 2),


("Ik ben een brug die lijkt op een zwaan en ik verbind Noord en Zuid. Wat ben ik?", "Je ziet me op bijna elke Rotterdam foto", "Erasmusbrug", 3),
("Ik ben een stadion waar duizenden mensen zingen en schreeuwen. Wat ben ik?", "De trots van Zuid", "De Kuip", 3),
("Ik ben een hoge toren waar je de hele stad kunt zien. Wat ben ik?", "Je kunt er in een lift omhoog", "Euromast", 3);