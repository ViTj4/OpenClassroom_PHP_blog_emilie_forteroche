<?php

/**
 * Page admin de monitoring du blog.
 */
?>

<div class="adminPage">
  <div class="adminHeader">
    <h2>Monitoring du blog</h2>
    <div class="adminNavigation">
      <a class="adminButton" href="index.php?action=admin">Articles</a>
      <a class="adminButton" href="index.php?action=monitoring">Monitoring</a>
    </div>
  </div>
  <section class="monitoringCards">
    <article class="monitoringCard">
      <span class="monitoringCardValue"><?= $totalArticles; ?></span>
      <span class="monitoringCardLabel">Article<?= $totalArticles > 1 ? 's' : ''; ?> publié<?= $totalArticles > 1 ? 's' : ''; ?></span>
    </article>
    <article class="monitoringCard">
      <span class="monitoringCardValue"><?= $totalComments; ?></span>
      <span class="monitoringCardLabel">Commentaire<?= $totalComments > 1 ? 's' : ''; ?></span>
    </article>
    <article class="monitoringCard">
      <span class="monitoringCardValue"><?= $totalViews; ?></span>
      <span class="monitoringCardLabel">Vue<?= $totalViews > 1 ? 's' : ''; ?> au total</span>
    </article>
    <article class="monitoringCard">
      <span class="monitoringCardValue">
        <?= $latestArticle ? htmlspecialchars($latestArticle->getTitle(), ENT_QUOTES) : 'Aucun article'; ?>
      </span>
      <span class="monitoringCardLabel">Dernier article publié</span>
    </article>
  </section>
  <section class="adminSection">
    <h2>Articles les plus consultés</h2>
    <table class="adminTable">
      <thead>
        <tr>
          <th>Article</th>
          <th class="adminTableSmallColumn">Vues</th>
          <th class="adminTableActionColumn">Accès</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($mostViewedArticles)) { ?>
          <tr>
            <td colspan="3">Aucun article publié.</td>
          </tr>
        <?php } else { ?>
          <?php foreach ($mostViewedArticles as $article) { ?>
            <tr>
              <td><?= htmlspecialchars($article->getTitle(), ENT_QUOTES); ?></td>
              <td class="adminTableSmallColumn"><?= $article->getViews(); ?></td>
              <td class="adminTableActionColumn">
                <a class="adminButton" href="index.php?action=showArticle&id=<?= $article->getId(); ?>">
                  Voir
                </a>
              </td>
            </tr>
          <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </section>
  <section class="adminSection">
    <h2>Suivi des articles</h2>
    <table class="adminTable">
      <thead>
        <tr>
          <th>Article</th>
          <th class="adminTableDateColumn">Publication</th>
          <th class="adminTableDateColumn">Modification</th>
          <th class="adminTableSmallColumn">Commentaires</th>
          <th class="adminTableSmallColumn">Vues</th>
          <th class="adminTableActionColumn">Accès</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articlesWithCommentCount)) { ?>
          <tr>
            <td colspan="6">Aucun article publié.</td>
          </tr>
        <?php } else { ?>
          <?php foreach ($articlesWithCommentCount as $article) { ?>
            <tr>
              <td><?= htmlspecialchars($article['title'], ENT_QUOTES); ?></td>
              <td class="adminTableDateColumn">
                <?= Utils::convertDateToFrenchFormat(new DateTime($article['date_creation'])); ?>
              </td>
              <td class="adminTableDateColumn">
                <?php if ($article['date_update']) { ?>
                  <?= Utils::convertDateToFrenchFormat(new DateTime($article['date_update'])); ?>
                <?php } else { ?>
                  Non modifié
                <?php } ?>
              </td>
              <td class="adminTableSmallColumn"><?= (int) $article['total_comments']; ?></td>
              <td class="adminTableSmallColumn"><?= (int) $article['views']; ?></td>
              <td class="adminTableActionColumn">
                <a class="adminButton" href="index.php?action=showArticle&id=<?= (int) $article['id']; ?>">
                  Voir
                </a>
              </td>
            </tr>
          <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </section>
  <section class="adminSection">
    <h2>Gestion des commentaires</h2>
    <table class="adminTable commentsMonitoringTable">
      <thead>
        <tr>
          <th class="adminTableDateColumn">Date</th>
          <th>Article</th>
          <th class="adminTablePseudoColumn">Pseudo</th>
          <th>Commentaire</th>
          <th class="adminTableActionColumn">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($comments)) { ?>
          <tr>
            <td colspan="5">Aucun commentaire publié.</td>
          </tr>
        <?php } else { ?>
          <?php foreach ($comments as $comment) { ?>
            <tr>
              <td class="adminTableDateColumn">
                <?= Utils::convertDateToFrenchFormat(new DateTime($comment['date_creation'])); ?>
              </td>
              <td>
                <a class="adminTableLink" href="index.php?action=showArticle&id=<?= (int) $comment['id_article']; ?>">
                  <?= htmlspecialchars($comment['article_title'], ENT_QUOTES); ?>
                </a>
              </td>
              <td class="adminTablePseudoColumn">
                <?= htmlspecialchars($comment['pseudo'], ENT_QUOTES); ?>
              </td>
              <td>
                <?= htmlspecialchars($comment['content'], ENT_QUOTES); ?>
              </td>
              <td class="adminTableActionColumn">
                <a
                  class="adminButton adminButtonDanger"
                  href="index.php?action=deleteComment&id=<?= (int) $comment['id']; ?>"
                  <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?"); ?>>
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