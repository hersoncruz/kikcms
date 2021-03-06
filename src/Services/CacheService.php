<?php declare(strict_types=1);

namespace KikCMS\Services;


use KikCMS\Classes\Cache\CacheNode;
use KikCMS\Models\Page;
use KikCMS\ObjectLists\CacheNodeMap;
use KikCmsCore\Services\DbService;
use KikCMS\Config\CacheConfig;
use Phalcon\Cache\Backend;
use KikCMS\Classes\Phalcon\Injectable;

/**
 * @property DbService $dbService
 * @property Backend $cache
 */
class CacheService extends Injectable
{
    /**
     * @param string $prefix
     */
    public function clear(string $prefix = '')
    {
        if ( ! $this->cache) {
            return;
        }

        $keys = $this->getKeys($prefix);

        foreach ($keys as $cacheKey) {
            $this->cache->delete($cacheKey);
        }
    }

    /**
     * @param Page $page
     */
    public function clearForPage(Page $page)
    {
        $offspring = $this->pageService->getOffspring($page);

        foreach ($offspring as $item){
            $this->cacheService->clear(CacheConfig::getUrlKeyForId($item->getId()));
        }

        $this->cacheService->clear(CacheConfig::getUrlKeyForId($page->getId()));
        $this->cacheService->clear(CacheConfig::MENU);
        $this->cacheService->clear(CacheConfig::PAGE_LANGUAGE_FOR_URL);
    }

    /**
     * Clears all caches related to pages
     */
    public function clearPageCache()
    {
        $this->clear(CacheConfig::URL);
        $this->clear(CacheConfig::MENU);
        $this->clear(CacheConfig::MENU_PAGES);
        $this->clear(CacheConfig::PAGE_LANGUAGE_FOR_URL);
    }

    /**
     * Clears all cached menu's
     */
    public function clearMenuCache()
    {
        $this->clear(CacheConfig::MENU);
        $this->clear(CacheConfig::MENU_PAGES);
    }

    /**
     * @param string $cacheKey
     * @param callable $function
     * @param float|int $ttl
     *
     * @return mixed|null
     */
    public function cache(string $cacheKey, callable $function, $ttl = CacheConfig::ONE_DAY)
    {
        if ( ! $this->cache) {
            return $function();
        }

        if ($this->cache->exists($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $result = $function();

        if ($result !== null) {
            $this->cache->save($cacheKey, $result, $ttl);
        }

        return $result;
    }

    /**
     * @param array $args
     * @return string
     */
    public function createKey(...$args): string
    {
        return implode(':', $args);
    }

    /**
     * Get a CacheNodeMap, which recursively contains all categories with their values
     *
     * @param string $prefix
     * @return CacheNodeMap
     */
    public function getCacheNodeMap(string $prefix = ''): CacheNodeMap
    {
        $cacheCategoryMap = new CacheNodeMap();

        $allKeys = $this->getKeys($prefix);

        sort($allKeys);

        foreach ($allKeys as $key) {
            $keyParts = explode(':', $key);

            $subMap       = $cacheCategoryMap;
            $fullKeyParts = [];

            foreach ($keyParts as $keyPart) {
                $fullKeyParts[] = $keyPart;

                if ( ! $cacheNode = $subMap->get($keyPart)) {
                    $cacheNode = new CacheNode();
                    $subMap->add($cacheNode, $keyPart);
                }

                $cacheNode->setKey($keyPart);
                $cacheNode->setFullKey(implode(':', $fullKeyParts));

                if ($keyPart == last($keyParts)) {
                    $cacheNode->setValue($this->cache->get($key));
                } else {
                    $subMap = $cacheNode->getCacheNodeMap();
                }
            }
        }

        foreach ($cacheCategoryMap as $key => $cacheNode){
            $cacheNode->flattenSingleNodes();
        }

        return $cacheCategoryMap;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getKeys(string $prefix = ''): array
    {
        $mainPrefix = $this->getMainPrefix();

        // fix inconsistency of phalcon caching, see https://github.com/phalcon/cphalcon/issues/14503
        if($this->cache instanceof Backend\File){
            $keys = $this->cache->queryKeys(preg_quote($prefix, '/'));
        } else {
            $keys = $this->cache->queryKeys($mainPrefix . preg_quote($prefix, '/'));
        }

        if ( ! $mainPrefix) {
            return $keys;
        }

        foreach ($keys as &$key) {
            if($prefix && ! strstr($key, $prefix . ':') && $key !== $mainPrefix . $prefix){
                unset($key);
            } else {
                $key = substr($key, strlen($mainPrefix));
            }
        }

        return $keys;
    }

    /**
     * Get the caches' main prefix
     *
     * @return string|null
     */
    private function getMainPrefix(): ?string
    {
        if ( ! $this->cache->getOptions()) {
            return null;
        }

        if ( ! array_key_exists('prefix', $this->cache->getOptions())) {
            return null;
        }

        return $this->cache->getOptions()['prefix'];
    }
}