<?php


namespace App\Services;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

class MarkDownHelper
{
    private $cache;
    private $markdown;
    private $logger;
    /**
     * @var bool
     */
    private $isDebug;
    /**
     * @var Security
     */
    private $security;

    public function __construct(AdapterInterface $cache,MarkdownInterface $markdown,LoggerInterface $markdownLogger,bool $isDebug,Security $security)
    {

        $this->logger = $markdownLogger;
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->isDebug = $isDebug;
        $this->security = $security;
    }

    public function parse(string $source):string
    {
        if(stripos($source,'bacon') !== false){
            $this->logger->info('They are talking about bacon',[
               'user' => $this->security->getUser()
            ]);
        }
        if($this->isDebug){
            return $this->markdown->transform($source);
        }
        $item =$this->cache->getItem('markdown_'.md5($source));
        if(!$item->isHit()){
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}