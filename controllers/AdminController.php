<?php

  /**
 * Contrôleur de la partie admin.
 */
class AdminController
{
      /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin(): void
    {
        $this->checkIfUserIsConnected();

        $articleManager = new ArticleManager();
        $articles       = $articleManager->getAllArticles();

        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

      /**
     * Affiche la page de monitoring du blog.
     * @return void
     */
    public function showMonitoring(): void
    {
        $this->checkIfUserIsConnected();

        $articleManager = new ArticleManager();
        $commentManager = new CommentManager();

        $articleSort  = Utils::request("articleSort", "date_creation");
        $articleOrder = Utils::request("articleOrder", "desc");

        $commentSort  = Utils::request("commentSort", "date_creation");
        $commentOrder = Utils::request("commentOrder", "desc");

        $selectedArticleId = (int) Utils::request("articleId", 0);

        $totalArticles            = $articleManager->countAllArticles();
        $totalComments            = $commentManager->countAllComments();
        $totalViews               = $articleManager->countAllViews();
        $latestArticle            = $articleManager->getLatestArticle();
        $latestComment            = $commentManager->getLatestCommentWithArticleTitle();
        $articlesWithCommentCount = $articleManager->getArticlesWithCommentCount($articleSort, $articleOrder);
        $mostViewedArticles       = $articleManager->getMostViewedArticles();
        $articlesForCommentFilter = $articleManager->getArticlesForCommentFilter();
        $comments                 = [];

        if ($selectedArticleId > 0) {
            $comments = $commentManager->getAllCommentsWithArticleTitle($commentSort, $commentOrder, $selectedArticleId);
        }

        $view = new View("Monitoring");
        $view->render("monitoring", [
            'totalArticles'            => $totalArticles,
            'totalComments'            => $totalComments,
            'totalViews'               => $totalViews,
            'latestArticle'            => $latestArticle,
            'latestComment'            => $latestComment,
            'articlesWithCommentCount' => $articlesWithCommentCount,
            'mostViewedArticles'       => $mostViewedArticles,
            'articlesForCommentFilter' => $articlesForCommentFilter,
            'comments'                 => $comments,
            'articleSort'              => $articleSort,
            'articleOrder'             => $articleOrder,
            'commentSort'              => $commentSort,
            'commentOrder'             => $commentOrder,
            'selectedArticleId'        => $selectedArticleId
        ]);
    }

      /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected(): void
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

      /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

      /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        $login    = Utils::request("login");
        $password = Utils::request("password");

        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $userManager = new UserManager();
        $user        = $userManager->getUserByLogin($login);

        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new Exception("Le mot de passe est incorrect.");
        }

        $_SESSION['user']   = $user;
        $_SESSION['idUser'] = $user->getId();

        Utils::redirect("admin");
    }

      /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        unset($_SESSION['user']);
        unset($_SESSION['idUser']);

        Utils::redirect("home");
    }

      /**
     * Affichage du formulaire d'ajout ou de modification d'un article.
     * @return void
     */
    public function showUpdateArticleForm(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        $articleManager = new ArticleManager();
        $article        = $articleManager->getArticleById($id);

        if (!$article) {
            $article = new Article();
        }

        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

      /**
     * Ajout et modification d'un article.
     * @return void
     */
    public function updateArticle(): void
    {
        $this->checkIfUserIsConnected();

        $id      = Utils::request("id", -1);
        $title   = Utils::request("title");
        $content = Utils::request("content");

        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $article = new Article([
            'id'      => $id,
            'title'   => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        Utils::redirect("admin");
    }

      /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);

        Utils::redirect("admin");
    }

      /**
     * Suppression d'un commentaire depuis la page de monitoring.
     * @return void
     */
    public function deleteComment(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        $commentManager = new CommentManager();
        $comment        = $commentManager->getCommentById($id);

        if (!$comment) {
            throw new Exception("Le commentaire demandé n'existe pas.");
        }

        $commentManager->deleteComment($comment);

        Utils::redirect("monitoring");
    }
}
