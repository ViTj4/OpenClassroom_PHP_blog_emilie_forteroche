<?php

  /**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager
{
      /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles(): array
    {
        $sql      = "SELECT * FROM article ORDER BY date_creation DESC";
        $result   = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }

        return $articles;
    }

      /**
     * Récupère un article par son id.
     * @param int $id      : l'id de l'article.
     * @return Article|null: un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id): ?Article
    {
        $sql     = "SELECT * FROM article WHERE id = :id";
        $result  = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();

        if ($article) {
            return new Article($article);
        }

        return null;
    }

      /**
     * Incrémente le nombre de vues d'un article.
     * @param int $id: l'id de l'article.
     * @return void
     */
    public function incrementArticleViews(int $id): void
    {
        $sql = "UPDATE article SET views = views + 1 WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

      /**
     * Compte le nombre total d'articles.
     * @return int
     */
    public function countAllArticles(): int
    {
        $sql    = "SELECT COUNT(*) AS total FROM article";
        $result = $this->db->query($sql);
        $row    = $result->fetch();

        return (int) $row['total'];
    }

      /**
     * Compte le nombre total de vues du blog.
     * @return int
     */
    public function countAllViews(): int
    {
        $sql    = "SELECT SUM(views) AS total FROM article";
        $result = $this->db->query($sql);
        $row    = $result->fetch();

        return (int) ($row['total'] ?? 0);
    }

      /**
     * Récupère le dernier article publié.
     * @return Article|null
     */
    public function getLatestArticle(): ?Article
    {
        $sql     = "SELECT * FROM article ORDER BY date_creation DESC LIMIT 1";
        $result  = $this->db->query($sql);
        $article = $result->fetch();

        if ($article) {
            return new Article($article);
        }

        return null;
    }

      /**
     * Récupère les articles avec leur nombre de commentaires et de vues.
     * Le tri est volontairement sécurisé avec une liste blanche.
     * @param string $sort : colonne demandée pour le tri.
     * @param string $order: asc ou desc.
     * @return array
     */
    public function getArticlesWithCommentCount(string $sort = "date_creation", string $order = "desc"): array
    {
        $allowedSorts = [
            'title'          => 'article.title',
            'date_creation'  => 'article.date_creation',
            'date_update'    => 'article.date_update',
            'total_comments' => 'total_comments',
            'views'          => 'article.views'
        ];

        $sortColumn     = $allowedSorts[$sort] ?? $allowedSorts['date_creation'];
        $orderDirection = strtolower($order) === 'asc' ? 'ASC' : 'DESC';

        $sql = "
            SELECT 
                article.id,
                article.title,
                article.date_creation,
                article.date_update,
                article.views,
                COUNT(comment.id) AS total_comments
            FROM article
            LEFT JOIN comment ON comment.id_article = article.id
            GROUP BY article.id, article.title, article.date_creation, article.date_update, article.views
            ORDER BY $sortColumn $orderDirection
        ";

        $result   = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = $article;
        }

        return $articles;
    }

      /**
     * Récupère les articles les plus consultés.
     * @return array
     */
    public function getMostViewedArticles(): array
    {
        $sql = "
            SELECT *
            FROM article
            ORDER BY views DESC, date_creation DESC
            LIMIT 5
        ";

        $result   = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }

        return $articles;
    }




      /**
     * Récupère les articles pour le filtre de gestion des commentaires.
     * @return array
     */
    public function getArticlesForCommentFilter(): array
    {
        $sql = "
        SELECT 
            article.id,
            article.title,
            COUNT(comment.id) AS total_comments
        FROM article
        LEFT JOIN comment ON comment.id_article = article.id
        GROUP BY article.id, article.title
        ORDER BY article.title ASC
    ";

        $result   = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = $article;
        }

        return $articles;
    }

      /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article): void
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

      /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article): void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation, views) VALUES (:id_user, :title, :content, NOW(), 0)";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title'   => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

      /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article): void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title'   => $article->getTitle(),
            'content' => $article->getContent(),
            'id'      => $article->getId()
        ]);
    }

      /**
     * Supprime un article.
     * @param int $id: l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id): void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
}
