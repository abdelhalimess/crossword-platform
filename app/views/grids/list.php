<h1>Liste des grilles</h1>

<?php foreach ($grids as $grid): ?>
    <div>
        <h3><?= $grid['name'] ?></h3>
        <p>Dimensions : <?= $grid['num_rows'] ?> x <?= $grid['num_columns'] ?></p>
        <p>Difficulté : <?= $grid['difficulty'] ?></p>
        <a href="/grid/<?= $grid['id'] ?>">Voir la grille</a>
    </div>
<?php endforeach; ?>
