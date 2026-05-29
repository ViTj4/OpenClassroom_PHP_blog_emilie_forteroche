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
  <section class="adminSection" id="mostViewedArticlesTable">
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
              <td class="adminTableSmallColumn">
                <?= $article->getViews(); ?>
              </td>
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

  <section class="adminSection" id="articlesTable">
    <h2>Suivi des articles</h2>
    <table class="adminTable">
      <thead>
        <tr>
          <th>
            <a class="adminSortLink" href="<?= Utils::getArticleSortUrl('title', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Article <span><?= Utils::getSortArrow($articleSort, $articleOrder, 'title'); ?></span>
            </a>
          </th>
          <th class="adminTableDateColumn">
            <a class="adminSortLink" href="<?= Utils::getArticleSortUrl('date_creation', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Publication <span><?= Utils::getSortArrow($articleSort, $articleOrder, 'date_creation'); ?></span>
            </a>
          </th>
          <th class="adminTableDateColumn">
            <a class="adminSortLink" href="<?= Utils::getArticleSortUrl('date_update', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Modification <span><?= Utils::getSortArrow($articleSort, $articleOrder, 'date_update'); ?></span>
            </a>
          </th>
          <th class="adminTableSmallColumn">
            <a class="adminSortLink" href="<?= Utils::getArticleSortUrl('total_comments', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Commentaires <span><?= Utils::getSortArrow($articleSort, $articleOrder, 'total_comments'); ?></span>
            </a>
          </th>
          <th class="adminTableSmallColumn">
            <a class="adminSortLink" href="<?= Utils::getArticleSortUrl('views', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Vues <span><?= Utils::getSortArrow($articleSort, $articleOrder, 'views'); ?></span>
            </a>
          </th>
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
              <td class="adminTableSmallColumn">
                <?= (int) $article['total_comments']; ?>
              </td>
              <td class="adminTableSmallColumn">
                <?= (int) $article['views']; ?>
              </td>
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

  <section class="adminSection" id="commentsTable">
    <h2>Gestion des commentaires</h2>
    <table class="adminTable commentsMonitoringTable">
      <thead>
        <tr>
          <th class="adminTableDateColumn">
            <a class="adminSortLink" href="<?= Utils::getCommentSortUrl('date_creation', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Date <span><?= Utils::getSortArrow($commentSort, $commentOrder, 'date_creation'); ?></span>
            </a>
          </th>
          <th>
            <a class="adminSortLink" href="<?= Utils::getCommentSortUrl('article_title', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Article <span><?= Utils::getSortArrow($commentSort, $commentOrder, 'article_title'); ?></span>
            </a>
          </th>
          <th class="adminTablePseudoColumn">
            <a class="adminSortLink" href="<?= Utils::getCommentSortUrl('pseudo', $articleSort, $articleOrder, $commentSort, $commentOrder); ?>">
              Pseudo <span><?= Utils::getSortArrow($commentSort, $commentOrder, 'pseudo'); ?></span>
            </a>
          </th>
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