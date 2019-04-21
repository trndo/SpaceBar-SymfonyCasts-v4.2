<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Services\MarkDownHelper;
use App\Services\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Michelf\MarkdownInterface;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ArticleController extends AbstractController
{
    /**
     * @var bool
     */
    private $isDebug;

    public function __construct(bool $isDebug)
    {

        $this->isDebug = $isDebug;
    }
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {

        $articles = $repository->findAllPublishedOrdByNewest();

        return $this->render('article/homepage.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}",name="article_show")
     */
    public function show(Article $article,SlackClient $slack)
    {
        if($article->getSlug() == 'ginger'){

            $slack->sendMessage('popov','Hello',':taco:');
        }

        return $this->render('article/show.html.twig',[
            'article' => $article
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart")
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger,EntityManagerInterface $em)
    {
        $article->incrementHertCount();

        $em->flush();
        // TODO - actually heart/unheart the article!

        $logger->info('Article is being hearted');

        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }
}
