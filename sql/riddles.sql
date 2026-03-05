CREATE TABLE riddles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    riddle VARCHAR(255) NOT NULL,
    answer VARCHAR(100) NOT NULL,
    hint VARCHAR(255),
    roomId INT NOT NULL
);

INSERT INTO riddles (riddle, hint, answer, roomId) VALUES 
("Ik heb een sleutel maar geen slot. Ik open geen deur, maar wel informatie. Wat ben ik?", "Denk aan computers", "keyboard", 1), 
("Hoe meer je ervan afhaalt, hoe groter het wordt. Wat is het?", "Het is geen object", "gat", 1), ("Ik ben altijd in beweging maar ga nooit vooruit. 
Wat ben ik?", "Je ziet me op je telefoon", "klok", 1), ("Ik heb steden maar geen huizen, rivieren maar geen water. Wat ben ik?", "Je gebruikt me om te navigeren", "kaart", 2),
 ("Hoe noem je iets dat je kunt breken zonder het aan te raken?", "Het is iets dat mensen doen", "belofte", 2),
 ("Ik ben licht als een veer, maar zelfs de sterkste man kan me niet vasthouden. Wat ben ik?", "Je hebt het nodig om te leven", "adem", 2);