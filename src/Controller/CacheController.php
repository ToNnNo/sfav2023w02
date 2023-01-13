<?php

namespace App\Controller;

use Michelf\Markdown;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/cache", name="cache_")
 */
class CacheController extends AbstractController
{
    private $markdown;
    private $contentFile;

    public function __construct(KernelInterface $kernel)
    {
        $this->markdown = new Markdown();
        $this->contentFile = file_get_contents($kernel->getProjectDir()."/public/markdown/file.md");
    }

    /**
     * @Route("/psr6", name="psr6")
     */
    public function psr6(CacheItemPoolInterface $cache): Response
    {
        $item = $cache->getItem( md5($this->contentFile) );
        if(!$item->isHit()) {
            $item->set( $this->markdown->transform($this->contentFile) );
            $item->expiresAfter(60);
            $cache->save($item);
        }

        $markdownToHTML = $item->get();

        return $this->render('cache/index.html.twig', [
            'title' => 'Cache PSR-6',
            'markdownToHTML' => $markdownToHTML
        ]);
    }


    /**
     * @Route("/contract", name="contract")
     */
    public function contract(CacheInterface $cache): Response
    {
        $markdownToHTML = $cache->get( 'convert_markdown_to_html', function(ItemInterface $item){
            $item->expiresAfter(60);
            return $this->markdown->transform($this->contentFile);
        });

        return $this->render('cache/index.html.twig', [
            'title' => 'Cache Contract',
            'markdownToHTML' => $markdownToHTML
        ]);
    }
}
