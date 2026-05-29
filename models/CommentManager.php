<?php

    /**
 * Cette classe sert à gérer les commentaires. 
 */
class CommentManager extends AbstractEntityManager
{
        /**
     * Récupère tous les commentaires d'un article.
     * @param int $idArticle: l'id de l'article.
     * @return array        : un tableau d'objets Comment.
     */
    public function getAllCommentsByArticleId(int $idArticle): array
    {
        $sql      = "SELECT * FROM comment WHERE id_article = :idArticle ORDER BY date_creation DESC";
        $result   = $this->db->query($sql, ['idArticle' => $idArticle]);
        $comments = [];

        while ($comment = $result->fetch()) {
            $comments[] = new Comment($comment);
        }

        return $comments;
    }

        /**
     * Récupère tous les commentaires avec le titre de l'article associé.
     * @return array
     */
    public function getAllCommentsWithArticleTitle(): array
    {
        $sql = "
            SELECT 
                comment.id,
                comment.id_article,
                comment.pseudo,
                comment.content,
                comment.date_creation,
                article.title AS article_title
            FROM comment
            INNER JOIN article ON article.id = comment.id_article
            ORDER BY comment.date_creation DESC
        ";

        $result   = $this->db->query($sql);
        $comments = [];

        while ($comment = $result->fetch()) {
            $comments[] = $comment;
        }

        return $comments;
    }

        /**
     * Compte le nombre total de commentaires.
     * @return int
     */
    public function countAllComments(): int
    {
        $sql    = "SELECT COUNT(*) AS total FROM comment";
        $result = $this->db->query($sql);
        $row    = $result->fetch();

        return (int) $row['total'];
    }

        /**
     * Récupère le dernier commentaire avec le titre de l'article associé.
     * @return array|null
     */
    public function getLatestCommentWithArticleTitle(): ?array
    {
        $sql = "
            SELECT 
                comment.id,
                comment.id_article,
                comment.pseudo,
                comment.content,
                comment.date_creation,
                article.title AS article_title
            FROM comment
            INNER JOIN article ON article.id = comment.id_article
            ORDER BY comment.date_creation DESC
            LIMIT 1
        ";

        $result  = $this->db->query($sql);
        $comment = $result->fetch();

        if ($comment) {
            return $comment;
        }

        return null;
    }

        /**
     * Récupère un commentaire par son id.
     * @param int $id : l'id du commentaire.
     * @return Comment|null : un objet Comment ou null si le commentaire n'existe pas.
     */
    public function getCommentById(int $id): ?Comment
    {
        $sql     = "SELECT * FROM comment WHERE id = :id";
        $result  = $this->db->query($sql, ['id' => $id]);
        $comment = $result->fetch();

        if ($comment) {
            return new Comment($comment);
        }

        return null;
    }

        /**
     * Ajoute un commentaire.
     * @param Comment $comment : l'objet Comment à ajouter.
     * @return bool : true si l'ajout a réussi, false sinon.
     */
    public function addComment(Comment $comment): bool
    {
        $sql    = "INSERT INTO comment (pseudo, content, id_article, date_creation) VALUES (:pseudo, :content, :idArticle, NOW())";
        $result = $this->db->query($sql, [
            'pseudo'    => $comment->getPseudo(),
            'content'   => $comment->getContent(),
            'idArticle' => $comment->getIdArticle()
        ]);

        return $result->rowCount() > 0;
    }

        /**
     * Supprime un commentaire.
     * @param Comment $comment : l'objet Comment à supprimer.
     * @return bool: true si la suppression a réussi, false sinon.
     */
    public function deleteComment(Comment $comment): bool
    {
        $sql    = "DELETE FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $comment->getId()]);

        return $result->rowCount() > 0;
    }
}
