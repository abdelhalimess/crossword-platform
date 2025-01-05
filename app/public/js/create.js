var formData = new FormData();

function Next() {
  // Récupérer les données du formulaire

  const rows = parseInt(document.getElementById("rows").value);
  const columns = parseInt(document.getElementById("columns").value);
  const name = document.getElementById("name").value;
  const difficulty = document.getElementById("difficulty").value;

  // Vérification des champs
  if (!name || !rows || !columns || !difficulty) {
    alert("Merci de  remplir tous les champs correctement !");
    return;
  }

  // Vérification des limites pour rows et columns
  if (rows < 4 || rows > 12) {
    alert("le nombre de ligne doit être compris entre 4 et 12.");
    return;
  }

  if (columns < 4 || columns > 12) {
    alert("le nombre de colonnes doit être compris entre 4 et 12.");
    return;
  }

  // Si toutes les validations passent, on ajoute les données au formData
  formData.append("name", name);
  formData.append("rows", rows);
  formData.append("columns", columns);
  formData.append("difficulty", difficulty);

  // Conteneur principal
  const mainContainer = document.createElement("div");
  mainContainer.style.display = "flex";
  mainContainer.style.gap = "20px"; // Espacement entre la grille et les indices
  mainContainer.style.justifyContent = "center"; // Centrer horizontalement

  // Créer la grille
  const gridContainer = createGrid(rows, columns);
  mainContainer.appendChild(gridContainer);

  // Créer les indices horizontaux
  const horizontalCluesContainer = createHorizontalClues(rows);
  mainContainer.appendChild(horizontalCluesContainer);

  // Créer les indices verticaux
  const verticalCluesContainer = createVerticalClues(columns);
  mainContainer.appendChild(verticalCluesContainer);

  // Remplacer le contenu du formulaire par le conteneur principal
  const formContent = document.getElementById("formContent");
  formContent.innerHTML = ""; // Vider le contenu existant
  formContent.appendChild(mainContainer);
}

let blackCells = []; // Tableau pour stocker les positions des cellules noires

// Fonction pour convertir un numéro de colonne en lettre (par exemple, 0 -> 'A', 1 -> 'B', etc.)
function columnToLetter(col) {
  return String.fromCharCode(65 + col); // 65 est le code ASCII pour 'A'
}

// Fonction pour créer la grille
function createGrid(rows, columns) {
  const gridContainer = document.createElement("div");
  gridContainer.style.display = "grid";
  gridContainer.style.gridTemplateRows = `repeat(${rows}, 60px)`;
  gridContainer.style.gridTemplateColumns = `repeat(${columns}, 60px)`;
  gridContainer.style.gap = "2px"; // Espacement entre les cases
  gridContainer.style.width = "60%";
  gridContainer.style.marginTop = "50px";
  gridContainer.style.gap = "0";
  gridContainer.style.marginLeft = "5vh"; // Centrer horizontalement

  // Ajouter les cellules dans la grille
  for (let i = 0; i < rows * columns; i++) {
    const cell = document.createElement("div");
    cell.style.outline = "1px solid black";
    cell.style.backgroundColor = "white"; // Couleur de fond des cellules
    cell.style.display = "flex";
    cell.style.alignItems = "center";
    cell.style.justifyContent = "center";
    cell.style.position = "relative"; // Nécessaire pour positionner l'input

    const row = Math.floor(i / columns) + 1; // Calcule la ligne (commence à 1)
    const col = i % columns; // Calcule la colonne (commence à 0)
    cell.setAttribute("data-row", row);
    cell.setAttribute("data-col", col);
    let input = null; // Déclaration pour l'input

    // Ajout de l'événement pour le clic sur la cellule
    cell.addEventListener("click", () => {
      if (cell.style.backgroundColor == "white") {
        if (!input) {
          input = document.createElement("input");
          input.type = "text";
          input.maxLength = 1; // Limiter à une seule lettre
          input.style.width = "100%";
          input.style.height = "100%";
          input.style.textAlign = "center";
          input.style.fontSize = "40px";
          input.style.border = "none";
          input.style.backgroundColor = "white";

          input.oninput = (e) => {
            e.target.value = e.target.value
              .toUpperCase()
              .replace(/[^A-Z]/g, "");
          };

          cell.appendChild(input);
          input.focus(); // Focus immédiat sur le champ
        }
      }
    });

    // Ajout de l'événement pour le double clic sur la cellule
    cell.addEventListener("dblclick", () => {
      console.log("Double-click detected!");

      if (input) {
        cell.removeChild(input); // Supprimer l'input
        input = null; // Réinitialiser
      }

      // Récupérer les positions ligne et colonne
      const cellRow = row; // Position de la ligne
      const cellCol = col + 1; // Position de la colonne

      // Mettre à jour la couleur de la cellule et stocker les positions
      if (cell.style.backgroundColor === "white") {
        cell.style.backgroundColor = "black";
        // Ajouter la position dans le tableau blackCells avec les colonnes en lettre
        blackCells.push({ row: cellRow, col: cellCol }); // Ajouter la position
      } else {
        cell.style.backgroundColor = "white";
        // Supprimer la position si elle redevient blanche
        blackCells = blackCells.filter(
          (pos) => !(pos.row === cellRow && pos.col === cellCol)
        );
      }
    });

    gridContainer.appendChild(cell);
  }

  const buttons = document.getElementById("buttons");

  const button = document.createElement("button");
  const notice = document.createElement("h3");
  notice.style.margin = "40px";

  notice.innerHTML =
    "Double-cliquez sur une case pour la rendre noire ou la rétablir.";
  button.innerText = "Publier";
  button.type = "button";

  // Événement au clic sur le bouton
  button.addEventListener("click", () => {
    const cellContents = getAllCellContents(rows, columns); // Récupérer le contenu des cellules
    const Hori_Clues = getHorizontalCluesContents(rows); // get row clues
    const Verti_Clues = getVerticlCluesContents(columns); // get vertical clues

    // Fonction pour valider que toutes les cellules sont remplies
    function areAllCellsFilled(cellData) {
      for (const cell of cellData) {
        if (cell.content !== "black" && cell.content.trim() === "") {
          alert(
            `La cellule à la ligne ${cell.row}, colonne ${cell.col} est vide !`
          );
          return false;
        }
      }
      return true;
    }

    // Vérifications
    if (!areAllCellsFilled(cellContents)) {
      console.error("Erreur : Toutes les cellules ne sont pas remplies.");
      return;
    }

    if (!Hori_Clues || !Verti_Clues) {
      console.error(
        "Tous les définitions doivent être remplis correctement avant de soumettre."
      );
      alert("Veuillez remplir tous les définitions avant de soumettre.");
      return; // Arrête l'exécution si la validation échoue
    }

    const gridObject = formDataToObject(formData);

    // Préparer les données à envoyer au serveur
    const dataToSend = {
      grid: gridObject,
      cells: cellContents,
      Hori_Clues: Hori_Clues,
      Verti_Clues: Verti_Clues,
    };

    // Envoyer les données via fetch à votre script PHP
    fetch("", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(dataToSend),
    })
      .then((response) => response.json()) // Attendez la réponse du serveur
      .then((data) => {
        if (data.success) {
          console.log("Cellules et clues insérées avec succès:", data.message);
          window.location.replace("../../index.php");
        } else {
          console.error("Erreur lors de l'insertion:", data.errors);
        }
      })
      .catch((error) => console.error("Erreur réseau:", error)); // Gestion des erreurs réseau
  });

  buttons.appendChild(button);
  buttons.appendChild(notice);

  return gridContainer;
}

// Fonction pour créer les indices horizontaux
function createHorizontalClues(rows) {
  const horizontalCluesContainer = document.createElement("div");
  horizontalCluesContainer.style.width = "30%";
  horizontalCluesContainer.style.marginTop = "50px";

  // Titre
  const horizontalTitle = document.createElement("h3");
  horizontalTitle.innerText = "Horizontalement";
  horizontalCluesContainer.appendChild(horizontalTitle);

  // Liste des indices
  const horizontalCluesList = document.createElement("ul");
  horizontalCluesList.style.listStyle = "none"; // Retirer les puces
  horizontalCluesList.style.padding = "0";

  for (let i = 1; i <= rows; i++) {
    const listItem = document.createElement("li");
    listItem.style.marginBottom = "10px"; // Espacement entre les éléments

    const label = document.createElement("label");
    label.innerText = `${i}. `; // Numéro de la ligne

    const input = document.createElement("input");
    input.type = "text";
    input.placeholder = `Définition ligne ${i}`;
    input.style.width = "80%"; // Taille de l'input
    input.style.padding = "5px"; // Espacement interne
    input.style.fontSize = "14px";

    listItem.appendChild(label);
    listItem.appendChild(input);
    horizontalCluesList.appendChild(listItem);
  }

  horizontalCluesContainer.appendChild(horizontalCluesList);

  return horizontalCluesContainer;
}

// Fonction pour créer les indices verticaux
function createVerticalClues(rows) {
  const verticalCluesContainer = document.createElement("div");
  verticalCluesContainer.style.width = "30%";
  verticalCluesContainer.style.marginTop = "50px";

  // Titre
  const verticalTitle = document.createElement("h3");
  verticalTitle.innerText = "Verticalement";
  verticalCluesContainer.appendChild(verticalTitle);

  // Liste des indices
  const verticalCluesList = document.createElement("ul");
  verticalCluesList.style.listStyle = "none"; // Retirer les puces
  verticalCluesList.style.padding = "0";

  for (let i = 1; i <= rows; i++) {
    const listItem = document.createElement("li");
    listItem.style.marginBottom = "10px"; // Espacement entre les éléments

    const label = document.createElement("label");
    label.innerText = `${i}. `; // Numéro de la ligne

    const input = document.createElement("input");
    input.type = "text";
    input.placeholder = `Définition colonne ${i}`;
    input.style.width = "80%"; // Taille de l'input
    input.style.padding = "5px"; // Espacement interne
    input.style.fontSize = "14px";

    listItem.appendChild(label);
    listItem.appendChild(input);
    verticalCluesList.appendChild(listItem);
  }

  verticalCluesContainer.appendChild(verticalCluesList);

  return verticalCluesContainer;
}

// Fonction pour récupérer toutes les cellules et leurs contenus
function getAllCellContents(rows, columns) {
  const cellData = []; // Tableau pour stocker les données récupérées

  // Parcourir toutes les cellules
  for (let row = 1; row <= rows; row++) {
    for (let col = 0; col < columns; col++) {
      const cell = document.querySelector(
        `[data-row="${row}"][data-col="${col}"]`
      );
      let content = "";

      if (cell) {
        // Vérifier si la cellule est noire
        if (cell.style.backgroundColor === "black") {
          content = "black";
        } else {
          // Vérifier si la cellule contient un input
          const input = cell.querySelector("input");
          if (input) {
            content = input.value.trim(); // Récupérer la valeur de l'input
          } else {
            content = cell.innerText.trim(); // Sinon, récupérer le texte brut
          }
        }

        // Ajouter la cellule et son contenu dans le tableau
        cellData.push({
          row: row,
          col: col + 1, // Ajouter 1 à la colonne pour que ce soit plus naturel (1-indexed)
          content: content,
        });
      }
    }
  }

  return cellData; // Retourner les données collectées
}

// Fonction pour convertir FormData en objet simple
function formDataToObject(formData) {
  const obj = {};
  formData.forEach((value, key) => {
    obj[key] = value;
  });
  return obj;
}

// Fonction pour récupérer toutes les cellules et leurs contenus
function getAll_Horiz_CluesContent(rows, columns) {
  const cellData = []; // Tableau pour stocker les données récupérées

  // Parcourir toutes les cellules
  for (let row = 1; row <= rows; row++) {
    for (let col = 0; col < columns; col++) {
      const cell = document.querySelector(
        `[data-row="${row}"][data-col="${col}"]`
      );
      let content = "";

      if (cell) {
        // Vérifier si la cellule est noire
        if (cell.style.backgroundColor === "black") {
          content = "black";
        } else {
          // Vérifier si la cellule contient un input
          const input = cell.querySelector("input");
          if (input) {
            content = input.value.trim(); // Récupérer la valeur de l'input
          } else {
            content = cell.innerText.trim(); // Sinon, récupérer le texte brut
          }
        }

        // Ajouter la cellule et son contenu dans le tableau
        cellData.push({
          row: row,
          col: col + 1, // Ajouter 1 à la colonne pour que ce soit plus naturel (1-indexed)
          content: content,
        });
      }
    }
  }

  return cellData; // Retourner les données collectées
}

function getHorizontalCluesContents(rows) {
  const clues = []; // Tableau pour stocker les indices

  for (let i = 1; i <= rows; i++) {
    const input = document.querySelector(
      `input[placeholder="Définition ligne ${i}"]`
    );

    if (input) {
      const value = input.value.trim(); // Récupère la valeur et enlève les espaces superflus
      if (!value) {
        console.error(`Le champ pour la définition horizontale ${i} est vide.`);
        return null; // Retourne null si une validation échoue
      }
      clues.push({
        id: i,
        content: value,
      });
    } else {
      console.error(
        `Le champ pour la définition horizontale ${i} est introuvable.`
      );
      return null; // Retourne null si un champ est manquant
    }
  }

  return clues; // Retourne les indices si tout est correct
}

function getVerticlCluesContents(cols) {
  const clues = []; // Tableau pour stocker les indices

  for (let i = 1; i <= cols; i++) {
    const input = document.querySelector(
      `input[placeholder="Définition colonne ${i}"]`
    );

    if (input) {
      const value = input.value.trim(); // Récupère la valeur et enlève les espaces superflus
      if (!value) {
        console.error(`Le champ pour la définition verticale ${i} est vide.`);
        return null; // Retourne null si une validation échoue
      }
      clues.push({
        id: i,
        content: value,
      });
    } else {
      console.error(
        `Le champ pour la définition verticale ${i} est introuvable.`
      );
      return null; // Retourne null si un champ est manquant
    }
  }

  return clues; // Retourne les indices si tout est correct
}
