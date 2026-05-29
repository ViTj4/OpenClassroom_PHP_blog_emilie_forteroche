<?php

/** 
 * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun.
 */
?>
<div class="adminPage">
    <div class="adminHeader">
        <h2>Gestion des articles</h2>
        <div class="adminNavigation">
            <a class="adminButton" href="index.php?action=admin">Articles</a>
            <a class="adminButton" href="index.php?action=monitoring">Monitoring</a>
            <a class="adminButton" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>
        </div>
    </div>
    <section class="adminSection">
        <table class="adminTable">
            <thead>
                <tr>
                    <th class="adminTableTitleColumn">Titre</th>
                    <th>Contenu</th>
                    <th class="adminTableActionColumn">Modifier</th>
                    <th class="adminTableActionColumn">Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)) { ?>
                    <tr>
                        <td colspan="4">Aucun article publié.</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($articles as $article) { ?>
                        <tr>
                            <td class="adminTableTitleColumn">
                                <?= htmlspecialchars($article->getTitle(), ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($article->getContent(250), ENT_QUOTES); ?>
                            </td>
                            <td class="adminTableActionColumn">
                                <a class="adminButton" href="index.php?action=showUpdateArticleForm&id=<?= $article->getId(); ?>">
                                    Modifier
                                </a>
                            </td>
                            <td class="adminTableActionColumn">
                                <a
                                    class="adminButton adminButtonDanger"
                                    href="index.php?action=deleteArticle&id=<?= $article->getId(); ?>"
                                    <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?"); ?>>
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </section>
</div>