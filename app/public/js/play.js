document.addEventListener("DOMContentLoaded", function () {
  // Récupérer les données JSON de l'élément HTML
  const cellData = document.getElementById("cell-data");
  const cellData2 = document.getElementById("cell-data2");
  const cellData3 = document.getElementById("cell-data3");
  const cellData4 = document.getElementById("cell-data4");

  const cellData5 = document.getElementById("cell-data5");

  // Vérifier si les données sont disponibles
  if (cellData) {
    const cells = JSON.parse(cellData.getAttribute("data-cell"));
    const cells2 = JSON.parse(cellData2.getAttribute("data-cell2"));
    const cells3 = JSON.parse(cellData3.getAttribute("data-cell3"));
    const cells4 = JSON.parse(cellData4.getAttribute("data-cell4"));
    const cells5 = JSON.parse(cellData5.getAttribute("data-cell5"));

    const title = document.getElementById("name");

    title.innerHTML = cells[0].name;

    // Si vous voulez accéder à un élément spécifique, par exemple:
    if (cells.length > 0) {
      // Conteneur principal
      const mainContainer = document.createElement("div");
      mainContainer.style.display = "flex";
      mainContainer.style.gap = "20px"; // Espacement entre la grille et les indices
      mainContainer.style.justifyContent = "center"; // Centrer horizontalement
      mainContainer.style.height = "100%";

      // Créer la grille
      const gridContainer = createGrid(
        cells5,
        cells[0].num_rows,
        cells[0].num_columns
      );

      // Colorier les cellules noires en fonction de cells2

      //if

      const blackCells = cells2.filter((item) => item.content === "black");
      colorBlackCells(gridContainer, blackCells);

      fillCellsWithContent(gridContainer, cells5);

      //ellse
      const buttons = document.getElementById("buttons");

      const button = document.createElement("button");

      button.innerText = "Valider";
      button.id = "validerButton";
      button.type = "button";

      button.addEventListener("click", () => {
        const cellContents = getAllCellContents(
          cells[0].num_rows,
          cells[0].num_columns
        );
        compareGrids(cellContents, cells2);
      });

      const userIdElement = document.getElementById("userInfo");
      const userId = userIdElement
        ? userIdElement.getAttribute("data-user-id")
        : null;

      if (userId) {
        const button2 = document.getElementById("SauvegarderButton");

        button2.innerText = "Sauvegarder";
        button2.type = "button";

        button2.addEventListener("click", () => {
          window.location.replace("../../index.php");

          const cellContents = getAllCellContents(
            cells[0].num_rows,
            cells[0].num_columns
          );

          const dataToSend = { cells: cellContents };
          fetch("", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(dataToSend),
          });
        });

        buttons.appendChild(button2);
      }
      buttons.appendChild(button);
      mainContainer.appendChild(gridContainer);

      // Créer les indices horizontaux
      const horizontalCluesContainer = createHorizontalClues(cells3);
      mainContainer.appendChild(horizontalCluesContainer);

      // Créer les indices verticaux
      const verticalCluesContainer = createVerticalClues(cells4);
      mainContainer.appendChild(verticalCluesContainer);

      cellData.innerHTML = ""; // Vider le contenu existant
      cellData.appendChild(mainContainer);
    } else {
      console.log("Aucun résultat trouvé.");
    }
  } else {
    console.log("Aucune donnée d'enregistrement trouvée.");
  }
});

// Fonction pour créer la grille
function createGrid(cells5, rows, columns) {
  const gridContainer = document.createElement("div");
  gridContainer.style.display = "grid";
  gridContainer.style.gridTemplateRows = `repeat(${rows}, 80px)`;
  gridContainer.style.gridTemplateColumns = `repeat(${columns}, 80px)`;
  gridContainer.style.gap = "2px"; // Espacement entre les cases
  gridContainer.style.width = "40%";
  gridContainer.style.marginTop = "100px";
  gridContainer.style.marginLeft = "17vh"; // Centrer horizontalement

  // Créer les cellules de la grille
  for (let i = 0; i < rows * columns; i++) {
    const cell = document.createElement("div");
    cell.style.outline = "1px solid black";
    cell.style.backgroundColor = "white"; // Par défaut, la cellule est blanche
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
        if (cells5.length == 0) {
          if (!input) {
            input = document.createElement("input");
            input.type = "text";
            input.maxLength = 1; // Limiter à une seule lettre
            input.style.width = "100%";
            input.style.height = "100%";
            input.style.textAlign = "center";
            input.style.fontSize = "40px";
            input.style.border = "none";

            input.oninput = (e) => {
              e.target.value = e.target.value
                .toUpperCase()
                .replace(/[^A-Z]/g, "");
            };
            cell.appendChild(input);
            input.focus(); // Focus immédiat sur le champ
          }
        }
      }
    });

    gridContainer.appendChild(cell);
  }

  return gridContainer;
}

// Fonction pour colorier les cellules noires
function colorBlackCells(gridContainer, blackCells) {
  // Boucle sur les positions des cases noires dans blackCells
  blackCells.forEach((cellData) => {
    // Trouver la cellule correspondante à la position rowa et col
    const cell = gridContainer.querySelector(
      `[data-row="${cellData.rowa}"][data-col="${cellData.col - 1}"]`
    );
    if (cell) {
      cell.style.backgroundColor = "black"; // Colorier la cellule en noir
    }
  });
}
// Fonction pour remplir les cellules avec un input
function fillCellsWithContent(gridContainer, cellDataArray) {
  // Boucle sur les objets de l'array cellDataArray
  cellDataArray.forEach((cellData) => {
    // Trouver la cellule correspondante à la position rowa et col
    const cell = gridContainer.querySelector(
      `[data-row="${cellData.rowa}"][data-col="${cellData.col - 1}"]`
    );

    if (cell) {
      // Vérifier que la cellule n'a pas déjà un contenu "black"
      if (cellData.content !== "black") {
        // Créer un élément input
        const input = document.createElement("input");
        input.type = "text";
        input.maxLength = 1; // Limiter à une seule lettre
        input.style.width = "100%";
        input.style.height = "100%";
        input.style.textAlign = "center";
        input.style.fontSize = "40px";
        input.style.border = "none";

        // Assurer que la saisie soit uniquement en majuscule et avec des lettres A-Z
        input.oninput = (e) => {
          e.target.value = e.target.value.toUpperCase().replace(/[^A-Z]/g, "");
        };

        // Remplir l'input avec le contenu de 'content'
        input.value = cellData.content;

        // Ajouter l'input à la cellule
        cell.innerHTML = ""; // Vider la cellule avant d'ajouter un input
        cell.appendChild(input);

        // Mettre le focus sur l'input
        input.focus();
      }
    }
  });
}

// Fonction pour créer les indices horizontaux
function createHorizontalClues(rows) {
  const horizontalCluesContainer = document.createElement("div");
  horizontalCluesContainer.style.width = "30%";
  horizontalCluesContainer.style.marginTop = "100px";
  horizontalCluesContainer.style.marginLeft = "0px";

  // Titre
  const horizontalTitle = document.createElement("h2");
  horizontalTitle.innerText = "Horizontalement";
  horizontalCluesContainer.appendChild(horizontalTitle);

  // Liste des indices
  const horizontalCluesList = document.createElement("ul");
  horizontalCluesList.style.listStyle = "none"; // Retirer les puces
  horizontalCluesList.style.padding = "0";

  for (let i = 1; i <= rows.length; i++) {
    const listItem = document.createElement("li");
    listItem.style.marginBottom = "10px"; // Espacement entre les éléments

    const input = document.createElement("h3");
    input.type = "text";
    input.innerText = `${i}   ${rows[i - 1].content} `;

    input.style.width = "80%"; // Taille de l'input
    input.style.padding = "5px"; // Espacement interne
    input.style.fontSize = "14px";

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
  verticalCluesContainer.style.marginTop = "100px";

  // Titre
  const verticalTitle = document.createElement("h2");
  verticalTitle.innerText = "Verticalement";
  verticalCluesContainer.appendChild(verticalTitle);

  // Liste des indices
  const verticalCluesList = document.createElement("ul");
  verticalCluesList.style.listStyle = "none"; // Retirer les puces
  verticalCluesList.style.padding = "0";

  for (let i = 1; i <= rows.length; i++) {
    const listItem = document.createElement("li");
    listItem.style.marginBottom = "10px"; // Espacement entre les éléments

    const input = document.createElement("h3");
    input.type = "text";
    input.innerText = `${i}   ${rows[i - 1].content} `;

    input.style.width = "80%"; // Taille de l'input
    input.style.padding = "5px"; // Espacement interne
    input.style.fontSize = "14px";

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

function compareGrids(grid1, grid2) {
  let isCorrect = true;
  let i = 0;

  while (i < grid1.length) {
    const cell1 = grid1[i];
    const cell2 = grid2[i];

    // Comparez les contenus de chaque case
    if (cell1.content !== cell2.content) {
      isCorrect = false; // Une différence a été trouvée
      break; // Sortez immédiatement de la boucle
    }

    i++; // Passez à l'élément suivant
  }

  // Affichez le message en fonction du résultat de la comparaison
  if (isCorrect) {
    alert("Bravo ! Vous avez réeussi le défi ");
  } else {
    alert(`Il semblerait que y'a des erreurs  :)`);
  }
}
